<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * SoftDeleteBehavior — Phase 5: Soft Delete
 *
 * WHAT IS SOFT DELETE?
 *   Instead of permanently removing a database row with DELETE SQL,
 *   soft delete sets a `deleted_at` timestamp column. The row stays in the
 *   database but is filtered out of all normal queries automatically.
 *
 * WHY USE SOFT DELETE?
 *   1. Audit trail: you can see what was "deleted" and when
 *   2. Recovery: accidentally deleted records can be restored
 *   3. Foreign key safety: child records referencing this row stay intact
 *
 * HOW IT WORKS IN CAKEPHP:
 *   - Attach to any Table via $this->addBehavior('SoftDelete')
 *   - The table must have a nullable `deleted_at` DATETIME column
 *   - Overrides the Model.beforeDelete event to set deleted_at instead of DELETE
 *   - Adds a Model.beforeFind event to filter out soft-deleted records
 *   - Provides restore() method to un-delete a record
 *
 * INTERVIEW TALKING POINT:
 *   "SoftDeleteBehavior demonstrates CakePHP's Behavior system — a way to
 *    share model logic across multiple tables without inheritance. I attach it
 *    to any table that has a deleted_at column, and it handles all the event
 *    hooks transparently."
 *
 * USAGE:
 *   $this->addBehavior('SoftDelete');          // In Table::initialize()
 *   $this->Products->delete($product);         // Sets deleted_at, no SQL DELETE
 *   $this->Products->restore($product);        // Clears deleted_at
 *   $this->Products->find()->withTrashed()     // Includes soft-deleted records
 */
class SoftDeleteBehavior extends Behavior
{
    /**
     * Default configuration for this behavior.
     * Can be overridden when attaching: addBehavior('SoftDelete', ['field' => 'removed_at'])
     */
    protected array $_defaultConfig = [
        // The database column that stores the deletion timestamp
        'field' => 'deleted_at',
        // Whether to auto-filter deleted records in find() queries
        'autoFilter' => true,
    ];

    /**
     * beforeFind() — Model Event Hook
     *
     * PHASE 5: Events — Model.beforeFind
     *
     * Fires BEFORE any SELECT query runs on the table.
     * We use it to automatically exclude soft-deleted records
     * unless the query explicitly sets 'withTrashed' option.
     *
     * LIFECYCLE POSITION:
     *   $this->find() → [Model.beforeFind fires here] → SQL executes → results returned
     *
     * @param EventInterface $event   The event object
     * @param SelectQuery    $query   The query being built
     * @param ArrayObject    $options Options array passed to find()
     */
    public function beforeFind(
        EventInterface $event,
        \Cake\ORM\Query\SelectQuery $query,
        ArrayObject $options
    ): void {
        // Don't filter if caller explicitly wants trashed records
        // Usage: $table->find()->setOptions(['withTrashed' => true])
        if (!empty($options['withTrashed'])) {
            return;
        }

        if (!$this->getConfig('autoFilter')) {
            return;
        }

        $field = $this->_table->getAlias() . '.' . $this->getConfig('field');

        // Only return records where deleted_at IS NULL (not deleted)
        $query->where([$field . ' IS' => null]);
    }

    /**
     * beforeDelete() — Model Event Hook
     *
     * PHASE 5: Events — Model.beforeDelete
     *
     * Fires BEFORE a DELETE SQL statement would execute.
     * We stop the default deletion and instead set the deleted_at timestamp.
     * Returning false from beforeDelete aborts the delete operation.
     *
     * LIFECYCLE POSITION:
     *   $table->delete($entity) → [Model.beforeDelete fires here] → (SQL blocked) → afterDelete
     *
     * @param EventInterface  $event   The event object
     * @param EntityInterface $entity  The entity being deleted
     * @param ArrayObject     $options Options passed to delete()
     */
    public function beforeDelete(
        EventInterface $event,
        EntityInterface $entity,
        ArrayObject $options
    ): void {
        $field = $this->getConfig('field');

        // 1. Set the deleted_at timestamp to NOW()
        $entity->set($field, new \Cake\I18n\DateTime());

        // 2. Save the entity with the deleted_at timestamp
        $this->_table->save($entity, ['checkRules' => false]);

        // 3. STOP the actual DELETE SQL from executing
        //    stopPropagation() prevents CakePHP from running the real DELETE
        $event->stopPropagation();
    }

    /**
     * restore() — Undo a soft delete
     *
     * Usage: $this->Products->restore($product);
     *
     * @param EntityInterface $entity The soft-deleted entity to restore
     * @return EntityInterface|bool
     */
    public function restore(EntityInterface $entity): EntityInterface|bool
    {
        $field = $this->getConfig('field');

        // Clear the deleted_at field
        $entity->set($field, null);

        return $this->_table->save($entity, ['checkRules' => false]);
    }

    /**
     * findWithTrashed() — Custom Finder
     *
     * PHASE 5: Custom Finder
     * Usage: $this->Products->find('withTrashed') — shows ALL including deleted
     *
     * CakePHP custom finders are named findXxx() and called as find('xxx')
     */
    public function findWithTrashed(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        // Remove the deleted_at filter added by beforeFind()
        // by passing withTrashed option (handled in beforeFind above)
        return $query->setOptions(['withTrashed' => true]);
    }

    /**
     * findOnlyTrashed() — Custom Finder
     *
     * Usage: $this->Products->find('onlyTrashed') — shows ONLY deleted records
     */
    public function findOnlyTrashed(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        $field = $this->_table->getAlias() . '.' . $this->getConfig('field');

        // First include trashed in the base query...
        $query->setOptions(['withTrashed' => true]);

        // ...then filter to ONLY show records that ARE soft-deleted
        return $query->where([$field . ' IS NOT' => null]);
    }
}
