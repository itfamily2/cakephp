<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

/**
 * Phase 11 - AuditComponent
 * 
 * Automatically audits specific controller actions, recording metadata
 * like request payload, user agent, etc.
 */
class AuditComponent extends Component
{
    protected array $_defaultConfig = [
        'auditActions' => ['edit', 'delete', 'add', 'checkout'], // Actions to audit automatically
    ];

    /**
     * Startup callback.
     * We hook into startup to check if the current action should be audited.
     */
    public function startup(EventInterface $event): void
    {
        $controller = $this->getController();
        $action = $controller->getRequest()->getParam('action');
        
        if (in_array($action, $this->getConfig('auditActions'))) {
            $this->recordAudit($controller);
        }
    }

    /**
     * Record the audit log.
     */
    protected function recordAudit($controller): void
    {
        $request = $controller->getRequest();
        
        // Only audit POST/PUT/DELETE requests automatically usually, 
        // but can be configured
        if (!$request->is(['post', 'put', 'patch', 'delete'])) {
            return;
        }

        $userId = null;
        if ($controller->components()->has('Authentication')) {
            $identity = $controller->Authentication->getIdentity();
            if ($identity) {
                $userId = $identity->getIdentifier();
            }
        }

        $auditData = [
            'controller' => $request->getParam('controller'),
            'action' => $request->getParam('action'),
            'payload' => json_encode($request->getData()),
            'user_id' => $userId,
            'ip_address' => $request->clientIp(),
            'user_agent' => $request->getHeaderLine('User-Agent'),
        ];

        try {
            $auditLogsTable = TableRegistry::getTableLocator()->get('AuditLogs');
            
            // Assuming AuditLogs schema has a generic text field or similar for this demo
            // if schema differs, adjust accordingly
            $log = $auditLogsTable->newEmptyEntity();
            $log->user_id = $auditData['user_id'];
            $log->table_name = $auditData['controller']; // using table_name for controller
            $log->record_id = 0; // N/A
            $log->action = 'ACTION_' . strtoupper($auditData['action']);
            $log->old_data = $auditData['user_agent'];
            $log->new_data = $auditData['payload'];
            
            $auditLogsTable->save($log);
            
            \Cake\Log\Log::debug('AuditComponent recorded action: ' . $auditData['action']);
        } catch (\Exception $e) {
            \Cake\Log\Log::error('AuditComponent failed: ' . $e->getMessage());
        }
    }
}
