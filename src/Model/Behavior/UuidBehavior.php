<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Text;
use ArrayObject;

/**
 * Phase 13 - UuidBehavior
 * 
 * Automatically generates a UUID for a specified field when creating new records.
 * This is useful for exposing public IDs without revealing sequential database IDs.
 */
class UuidBehavior extends Behavior
{
    /**
     * Default configuration.
     * 'field' sets the column name to populate with the UUID.
     */
    protected array $_defaultConfig = [
        'field' => 'uuid',
    ];

    /**
     * Hook into beforeSave to generate the UUID.
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $field = $this->getConfig('field');
        
        // Only generate for new entities if the field doesn't already have a value
        if ($entity->isNew() && !$entity->has($field)) {
            $entity->set($field, Text::uuid());
        }
    }
}
