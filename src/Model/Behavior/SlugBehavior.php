<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * SlugBehavior — Phase 5: Behavior + Model Events
 *
 * WHAT IS A SLUG?
 *   A slug is a URL-friendly version of a string.
 *   "My Awesome Product!" → "my-awesome-product"
 *
 * WHAT THIS BEHAVIOR DOES:
 *   - Automatically generates a `slug` value before saving any entity
 *   - If slug is already provided manually, it validates and formats it
 *   - Ensures slug uniqueness by appending -1, -2 etc. when needed
 *   - Works on ANY table that has a `slug` column
 *
 * PHASE 5 CONCEPTS:
 *   - Behavior: Reusable model plugin attached via addBehavior()
 *   - Model.beforeMarshal: Fires before request data enters the entity
 *   - Model.beforeSave: Fires before SQL INSERT/UPDATE runs
 *
 * INTERVIEW TALKING POINT:
 *   "Behaviors are CakePHP's equivalent of PHP traits but for model-layer logic.
 *    They listen to model events and can add custom finders. A single Behavior
 *    file gives slug functionality to Products, CmsPages, Categories, and Brands
 *    without any code duplication. That's the Composition over Inheritance principle."
 *
 * USAGE:
 *   In any Table::initialize():
 *   $this->addBehavior('Slug', [
 *       'field'  => 'name',   // Source field to generate slug from
 *       'slug'   => 'slug',   // Target slug column
 *       'unique' => true,     // Enforce uniqueness
 *   ]);
 */
class SlugBehavior extends Behavior
{
    /**
     * Default configuration.
     */
    protected array $_defaultConfig = [
        'field'     => 'name',  // Column to generate slug from
        'slug'      => 'slug',  // Column to store slug in
        'unique'    => true,    // Whether to enforce uniqueness in the DB
        'separator' => '-',     // Separator character
    ];

    /**
     * beforeMarshal() — Phase 5: Model.beforeMarshal Event
     *
     * Fires BEFORE request data is applied to the entity.
     * This is the earliest point to normalize raw input data.
     *
     * LIFECYCLE:
     *   POST data received → [beforeMarshal] → Entity created → [beforeSave] → SQL
     *
     * @param EventInterface $event The event
     * @param ArrayObject    $data  Raw request data (mutable)
     * @param ArrayObject    $options Options
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        $sourceField = $this->getConfig('field');
        $slugField   = $this->getConfig('slug');

        // Only generate a slug if the source field has a value
        // AND the slug field is either empty or not yet set
        if (!empty($data[$sourceField]) && empty($data[$slugField])) {
            // Generate slug from source field value
            $data[$slugField] = $this->generateSlug((string)$data[$sourceField]);
        } elseif (!empty($data[$slugField])) {
            // If slug was provided manually, normalize its format
            $data[$slugField] = $this->generateSlug((string)$data[$slugField]);
        }
    }

    /**
     * beforeSave() — Phase 5: Model.beforeSave Event
     *
     * Fires AFTER marshalling, BEFORE the INSERT/UPDATE SQL runs.
     * Here we ensure uniqueness — if slug already exists, append a counter.
     *
     * @param EventInterface  $event   The event
     * @param EntityInterface $entity  The entity about to be saved
     * @param ArrayObject     $options Save options
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $slugField = $this->getConfig('slug');

        // If entity has no slug set, generate from the source field
        if (empty($entity->get($slugField))) {
            $sourceField = $this->getConfig('field');
            $sourceValue = $entity->get($sourceField) ?? '';
            $entity->set($slugField, $this->generateSlug($sourceValue));
        }

        // Ensure uniqueness if configured
        if ($this->getConfig('unique')) {
            $baseSlug  = $entity->get($slugField);
            $uniqueSlug = $this->makeUnique($baseSlug, $entity->id ?? null);
            $entity->set($slugField, $uniqueSlug);
        }
    }

    /**
     * generateSlug() — Core slug generation logic.
     *
     * "My Product (Special Edition)!" → "my-product-special-edition"
     *
     * @param string $text Source text
     * @return string Generated slug
     */
    private function generateSlug(string $text): string
    {
        $sep = $this->getConfig('separator');

        // 1. Convert to lowercase
        $slug = strtolower($text);

        // 2. Transliterate special characters (é → e, ü → u etc.)
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug) ?: $slug;

        // 3. Replace any non-alphanumeric characters with the separator
        $slug = preg_replace('/[^a-z0-9]+/', $sep, $slug);

        // 4. Remove leading/trailing separators
        $slug = trim($slug, $sep);

        return $slug;
    }

    /**
     * makeUnique() — Ensures slug does not duplicate existing records.
     *
     * If "my-product" exists, this returns "my-product-1", then "my-product-2" etc.
     *
     * @param string   $slug      Base slug to make unique
     * @param int|null $currentId ID of the entity being saved (exclude from check)
     * @return string Unique slug
     */
    private function makeUnique(string $slug, ?int $currentId = null): string
    {
        $slugField = $this->getConfig('slug');
        $counter = 1;
        $testSlug = $slug;

        while (true) {
            // Check if this slug already exists in the database
            $query = $this->_table->find()->where([$slugField => $testSlug]);

            // Exclude current entity (editing scenario)
            if ($currentId !== null) {
                $query->where([$this->_table->getPrimaryKey() . ' !=' => $currentId]);
            }

            if ($query->count() === 0) {
                // Slug is unique — use it
                break;
            }

            // Slug exists — append counter and try again
            $testSlug = $slug . $this->getConfig('separator') . $counter;
            $counter++;
        }

        return $testSlug;
    }
}
