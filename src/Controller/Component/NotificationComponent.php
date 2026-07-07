<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\EventInterface;

/**
 * Phase 11 - NotificationComponent
 * 
 * A central component to manage application notifications, integrating
 * with Flash messages and potentially a database-backed notifications table.
 */
class NotificationComponent extends Component
{
    protected array $components = ['Flash'];

    /**
     * Send a success notification (Flash message for now, could be expanded).
     */
    public function success(string $message): void
    {
        $this->Flash->success($message);
    }

    /**
     * Send an error notification.
     */
    public function error(string $message): void
    {
        $this->Flash->error($message);
    }

    /**
     * Send a warning notification.
     */
    public function warning(string $message): void
    {
        $this->Flash->warning($message);
    }
    
    /**
     * Send an info notification.
     */
    public function info(string $message): void
    {
        $this->Flash->info($message);
    }
    
    /**
     * Example of a method that could save a notification to the database for a user.
     */
    public function notifyUser(int $userId, string $title, string $message): void
    {
        // $notificationsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Notifications');
        // $notification = $notificationsTable->newEntity([
        //     'user_id' => $userId,
        //     'title' => $title,
        //     'message' => $message,
        //     'is_read' => false
        // ]);
        // $notificationsTable->save($notification);
        
        \Cake\Log\Log::info("Notification sent to User $userId: $title - $message");
    }
}
