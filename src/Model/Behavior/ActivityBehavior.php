<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use ArrayObject;

/**
 * Phase 13 - ActivityBehavior
 * 
 * Automatically logs model-level activities (create, update, delete) 
 * directly into the ActivityLogs table. Useful for models where you
 * need a granular log of every data change regardless of controller context.
 */
class ActivityBehavior extends Behavior
{
    /**
     * Default configuration.
     */
    protected array $_defaultConfig = [
        'actions' => ['create', 'update', 'delete'],
    ];

    /**
     * Hook into the afterSave event to log create/update actions.
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $action = $entity->isNew() ? 'create' : 'update';
        
        // Skip logging if the action is not configured
        if (!in_array($action, $this->getConfig('actions'))) {
            return;
        }

        // We can check if specific fields changed to avoid noisy logs
        if ($action === 'update' && empty($entity->getDirty())) {
            return; 
        }

        $this->logActivity($entity, ucfirst($action));
    }

    /**
     * Hook into the afterDelete event to log delete actions.
     */
    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if (in_array('delete', $this->getConfig('actions'))) {
            $this->logActivity($entity, 'Delete');
        }
    }

    /**
     * Helper to write the activity log.
     */
    protected function logActivity(EntityInterface $entity, string $action): void
    {
        try {
            $table = $this->table();
            $tableName = $table->getTable();
            
            // Get primary key value(s)
            $primaryKey = (array)$table->getPrimaryKey();
            $id = $entity->get($primaryKey[0] ?? 'id');
            
            $activityTable = TableRegistry::getTableLocator()->get('ActivityLogs');
            $log = $activityTable->newEmptyEntity();
            
            $log->action = "{$tableName}.{$action}";
            $log->description = "System performed {$action} on {$tableName} record ID: {$id}";
            
            // If user_id is passed via options (from controller), we can use it
            // Otherwise it's logged as a system action
            $log->ip_address = php_sapi_name() === 'cli' ? 'CLI' : ($_SERVER['REMOTE_ADDR'] ?? 'Unknown');
            
            $activityTable->save($log);
        } catch (\Exception $e) {
            \Cake\Log\Log::error('ActivityBehavior failed to log: ' . $e->getMessage());
        }
    }
}
