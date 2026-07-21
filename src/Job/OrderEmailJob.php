<?php
declare(strict_types=1);

namespace App\Job;

use Cake\Log\Log;
use Cake\Queue\Job\JobInterface;
use Cake\Queue\Job\Message;
use Cake\ORM\TableRegistry;

class OrderEmailJob implements JobInterface
{
    /**
     * Executes the background job.
     * This runs asynchronously in the CLI worker process.
     *
     * @param \Cake\Queue\Job\Message $message The message containing job payload.
     * @return void
     */
    public function execute(Message $message): ?string
    {
        $orderId = $message->getArgument('order_id');
        Log::info("STARTING: Processing Order Email Job for Order ID: {$orderId}", ['scope' => ['queue']]);

        try {
            $ordersTable = TableRegistry::getTableLocator()->get('Orders');
            $order = $ordersTable->get($orderId, contain: ['Users']);
            
            // Simulating a heavy external API call or SMTP connection (e.g., SendGrid/AWS SES)
            sleep(2); // Fake delay to prove asynchronous nature
            
            Log::info("SUCCESS: Sent order confirmation email to {$order->user->email} for Order {$order->order_number}", ['scope' => ['queue']]);
            
        } catch (\Exception $e) {
            Log::error("FAILED: Order Email Job failed for Order ID {$orderId}. Reason: " . $e->getMessage(), ['scope' => ['queue']]);
            
            // Re-throw exception so the QueueManager knows the job failed
            // and can put it in the FailedJobs (Dead Letter Queue) or retry it.
            throw $e;
        }
        
        return null;
    }
}
