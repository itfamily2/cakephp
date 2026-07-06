<?php
declare(strict_types=1);

namespace Audit\Event;

use Cake\Event\EventListenerInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Datasource\EntityInterface;

/**
 * Enterprise Audit Listener
 * Automatically intercepts ORM events globally to log data mutations securely.
 */
class AuditListener implements EventListenerInterface
{
    use LocatorAwareTrait;

    public function implementedEvents(): array
    {
        return [
            'Model.afterSave' => 'afterSave',
            'Model.afterDelete' => 'afterDelete',
        ];
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options): void
    {
        if ($entity->getSource() === 'AuditLogs') {
            return; // Prevent infinite loop
        }

        $action = $entity->isNew() ? 'CREATE' : 'UPDATE';
        $changes = $entity->extractOriginalChanged($entity->getVisible());

        // Only log if there are actual changes
        if (empty($changes) && $action === 'UPDATE') {
            return;
        }

        $this->logAction($entity->getSource(), $entity->id, $action, [
            'original' => $changes,
            'new' => $entity->extract(array_keys($changes))
        ]);
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, \ArrayObject $options): void
    {
        if ($entity->getSource() === 'AuditLogs') return;

        $this->logAction($entity->getSource(), $entity->id, 'DELETE', $entity->toArray());
    }

    private function logAction(string $model, $foreignKey, string $action, array $meta): void
    {
        $auditTable = $this->fetchTable('AuditLogs');
        $log = $auditTable->newEntity([
            'model' => $model,
            'foreign_key' => $foreignKey,
            'action' => $action,
            'meta' => json_encode($meta),
            'created' => new \DateTime()
        ]);
        $auditTable->save($log);
    }
}
