<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * Phase 11 - ActivityComponent
 * 
 * Reusable component to log user activities from any controller.
 * Replaces the protected logActivity method in AppController for better encapsulation.
 */
class ActivityComponent extends Component
{
    /**
     * Log an activity.
     *
     * @param string $action The action performed (e.g. 'Created Order')
     * @param string $description Detailed description
     * @param int|null $userId User ID, if null attempts to get from Auth
     * @return bool True if saved, false otherwise
     */
    public function logActivity(string $action, string $description, ?int $userId = null): bool
    {
        try {
            if ($userId === null) {
                // Try to get from Authentication component if loaded
                $controller = $this->getController();
                if ($controller->components()->has('Authentication')) {
                    $identity = $controller->Authentication->getIdentity();
                    if ($identity) {
                        $userId = $identity->getIdentifier();
                    }
                }
            }

            $ipAddress = $this->getController()->getRequest()->clientIp() ?: '127.0.0.1';

            $activityLogsTable = TableRegistry::getTableLocator()->get('ActivityLogs');
            $log = $activityLogsTable->newEmptyEntity();
            $log->user_id = $userId;
            $log->action = $action;
            $log->description = $description;
            $log->ip_address = $ipAddress;
            
            return (bool)$activityLogsTable->save($log);
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Failed to log activity: ' . $e->getMessage());
            return false;
        }
    }
}
