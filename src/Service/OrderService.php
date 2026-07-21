<?php
declare(strict_types=1);

namespace App\Service;

use App\Job\OrderEmailJob;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Cake\Queue\QueueManager;
use Exception;

/**
 * OrderService
 *
 * Demonstrates the Service Layer Pattern.
 * Controllers should only handle HTTP requests/responses.
 * Complex business logic belongs here.
 */
class OrderService
{
    /**
     * Creates an order, processes business rules, and dispatches background jobs.
     *
     * @param array $data The POST data from the request
     * @return \Cake\Datasource\EntityInterface The saved Order entity
     * @throws \Exception If validation or save fails
     */
    public function placeOrder(array $data): EntityInterface
    {
        $ordersTable = TableRegistry::getTableLocator()->get('Orders');

        // 1. Create a new empty entity
        $order = $ordersTable->newEmptyEntity();

        // 2. Patch data with associated OrderItems
        $order = $ordersTable->patchEntity($order, $data, [
            'associated' => ['OrderItems'],
        ]);

        // 3. Save the entity (this triggers beforeSave and afterSave callbacks in OrdersTable)
        if (!$ordersTable->save($order)) {
            $errors = json_encode($order->getErrors());
            throw new Exception('Order validation failed: ' . $errors);
        }

        // 4. Dispatch a background job to send the confirmation email asynchronously
        // This ensures the API responds immediately without waiting for SMTP
        QueueManager::push(OrderEmailJob::class, ['order_id' => $order->id]);

        return $order;
    }
}
