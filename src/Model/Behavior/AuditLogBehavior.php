<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use ArrayObject;

class AuditLogBehavior extends Behavior
{
    /**
     * afterSave callback
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $table = $this->_table->getTable();
        $isNew = $entity->isNew();
        $action = $isNew ? 'CREATE' : 'UPDATE';
        
        $dirty = $entity->getDirty();
        if (empty($dirty) && !$isNew) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($dirty as $field) {
            if ($field === 'password' || $field === 'remember_token') {
                continue;
            }
            $oldValues[$field] = $entity->getOriginal($field);
            $newValues[$field] = $entity->get($field);
        }

        if ($isNew) {
            foreach ($entity->toArray() as $field => $val) {
                if ($field === 'password' || $field === 'remember_token') {
                    continue;
                }
                $newValues[$field] = $val;
            }
        }

        $this->logAudit($action, $table, (int)$entity->id, $oldValues, $newValues);
    }

    /**
     * afterDelete callback
     */
    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $table = $this->_table->getTable();
        $old = $entity->toArray();
        if (isset($old['password'])) {
            unset($old['password']);
        }
        $this->logAudit('DELETE', $table, (int)$entity->id, $old, null);
    }

    /**
     * Save to audit log table
     */
    protected function logAudit(string $action, string $table, int $rowId, ?array $oldValues, ?array $newValues): void
    {
        try {
            $request = Router::getRequest();
            $ip = $request ? $request->clientIp() : '127.0.0.1';
            
            $userId = null;
            if ($request) {
                // Read from Identity
                $identity = $request->getAttribute('identity');
                if ($identity) {
                    $userId = $identity->get('id');
                }
            }

            $auditLogsTable = TableRegistry::getTableLocator()->get('AuditLogs');
            $log = $auditLogsTable->newEmptyEntity();
            $log->user_id = $userId;
            $log->table_name = $table;
            $log->row_id = $rowId;
            $log->action = $action;
            $log->old_values = $oldValues ? json_encode($oldValues) : null;
            $log->new_values = $newValues ? json_encode($newValues) : null;
            $log->ip_address = $ip;
            
            $auditLogsTable->save($log);
        } catch (\Exception $e) {
            // Fail silently
        }
    }
}
