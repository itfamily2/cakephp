<?php
declare(strict_types=1);

namespace App\Service;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Datasource\ConnectionManager;
use App\Model\Entity\Order;
use Cake\I18n\FrozenTime;
use Exception;

/**
 * Order Service
 *
 * Handles the complete lifecycle of Orders according to the ERP business rules,
 * keeping the controllers thin and ensuring robust database transactions.
 */
class OrderService
{
    use LocatorAwareTrait;

    // Allowed status transitions
    protected const WORKFLOW_TRANSITIONS = [
        'Draft'      => ['Pending', 'Cancelled'],
        'Pending'    => ['Confirmed', 'Rejected', 'Cancelled'],
        'Confirmed'  => ['Accepted', 'Cancelled'],
        'Accepted'   => ['Processing', 'Cancelled'],
        'Processing' => ['Completed', 'Cancelled'],
        'Completed'  => ['Invoiced'],
        'Invoiced'   => ['Closed'],
        'Rejected'   => ['Draft', 'Cancelled'], // Can be moved back to draft
        'Cancelled'  => [],
        'Closed'     => [],
    ];

    /**
     * Creates an Order, saving headers and items inside a single transaction.
     *
     * @param array $data Raw request data
     * @return Order
     * @throws Exception On failure
     */
    public function createOrder(array $data): Order
    {
        $ordersTable = $this->fetchTable('Orders');
        
        // Calculate totals BEFORE marshalling to satisfy OrdersTable validation
        $subTotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;

        if (empty($data['order_items']) || !is_array($data['order_items'])) {
            throw new Exception("Order must contain at least one item.");
        }

        foreach ($data['order_items'] as &$item) {
            $subTotal += ($item['price'] * $item['quantity']);
            $totalTax += (float)($item['tax'] ?? 0);
            $totalDiscount += (float)($item['discount'] ?? 0);
            $item['line_total'] = ($item['price'] * $item['quantity']) + ($item['tax'] ?? 0) - ($item['discount'] ?? 0);
        }

        $data['total'] = $subTotal;
        $data['tax'] = $totalTax;
        $data['discount'] = $totalDiscount;
        $data['grand_total'] = $subTotal + $totalTax - $totalDiscount;
        
        $order = $ordersTable->newEntity($data, [
            'associated' => ['OrderItems']
        ]);

        if ($order->hasErrors()) {
            throw new Exception("Validation failed: " . json_encode($order->getErrors()));
        }

        // Force initial status to Draft or Pending depending on business rule
        $order->status = $data['status'] ?? 'Draft';
        if (!in_array($order->status, ['Draft', 'Pending'])) {
            $order->status = 'Draft';
        }

        // Default calculations if not provided


        $connection = ConnectionManager::get('default');
        
        return $connection->transactional(function () use ($ordersTable, $order) {
            $saved = $ordersTable->save($order);
            if (!$saved) {
                throw new Exception("Failed to save Order header.");
            }
            
            // If the order immediately goes to Confirmed/Accepted, we need to adjust stock.
            if ($order->status === 'Confirmed' || $order->status === 'Accepted') {
                $this->deductInventory($saved);
            }
            
            return $saved;
        });
    }

    /**
     * Changes the status of an order, enforcing workflow rules and inventory adjustments.
     *
     * @param Order|int $order Order entity or ID
     * @param string $newStatus The target status
     * @param int|null $userId User ID performing the action (for accepted_by, etc)
     * @param string|null $notes Optional notes for rejection/cancellation
     * @return Order
     * @throws Exception
     */
    public function transitionStatus($order, string $newStatus, ?int $userId = null, ?string $notes = null): Order
    {
        $ordersTable = $this->fetchTable('Orders');
        
        if (!$order instanceof Order) {
            $order = $ordersTable->get($order, contain: ['OrderItems']);
        } else if (empty($order->order_items)) {
            $order = $ordersTable->get($order->id, contain: ['OrderItems']);
        }

        $currentStatus = $order->status;
        
        // Enforce workflow rules
        if (!isset(self::WORKFLOW_TRANSITIONS[$currentStatus]) || !in_array($newStatus, self::WORKFLOW_TRANSITIONS[$currentStatus])) {
            throw new Exception("Invalid transition from {$currentStatus} to {$newStatus}.");
        }

        $order->status = $newStatus;
        if ($notes !== null) {
            $order->notes = $order->notes ? $order->notes . "\n" . $notes : $notes;
        }

        // Special state hooks
        if ($newStatus === 'Accepted') {
            $order->accepted_by = $userId;
            $order->accepted_at = new FrozenTime();
        } else if ($newStatus === 'Cancelled') {
            $order->cancelled_by = $userId;
            $order->cancelled_at = new FrozenTime();
        }

        $connection = ConnectionManager::get('default');
        
        return $connection->transactional(function () use ($ordersTable, $order, $currentStatus, $newStatus) {
            
            // INVENTORY LOGIC
            // If moving INTO a committed state from an uncommitted state
            if (!in_array($currentStatus, ['Confirmed', 'Accepted', 'Processing', 'Completed', 'Invoiced', 'Closed']) &&
                 in_array($newStatus, ['Confirmed', 'Accepted'])) {
                $this->deductInventory($order);
            }
            
            // If moving OUT of a committed state into Cancelled
            if (in_array($currentStatus, ['Confirmed', 'Accepted', 'Processing', 'Completed']) && $newStatus === 'Cancelled') {
                $this->restoreInventory($order);
            }

            $saved = $ordersTable->save($order);
            if (!$saved) {
                throw new Exception("Failed to transition order status.");
            }
            return $saved;
        });
    }

    /**
     * Deducts inventory for an order
     */
    protected function deductInventory(Order $order): void
    {
        $productsTable = $this->fetchTable('Products');
        foreach ($order->order_items as $item) {
            $product = $productsTable->get($item->product_id);
            if ($product->stock < $item->quantity) {
                throw new Exception("Insufficient stock for Product ID {$product->id}. Available: {$product->stock}, Requested: {$item->quantity}.");
            }
            $product->stock -= $item->quantity;
            $productsTable->saveOrFail($product);
        }
    }

    /**
     * Restores inventory for an order
     */
    protected function restoreInventory(Order $order): void
    {
        $productsTable = $this->fetchTable('Products');
        foreach ($order->order_items as $item) {
            $product = $productsTable->get($item->product_id);
            $product->stock += $item->quantity;
            $productsTable->saveOrFail($product);
        }
    }

    /**
     * Modifies an existing order.
     * Allowed only if Draft, Pending, or Rejected.
     */
    public function updateOrder(Order $order, array $data): Order
    {
        if (!in_array($order->status, ['Draft', 'Pending', 'Rejected'])) {
            throw new Exception("Order modification is not allowed in {$order->status} status.");
        }
        
        $ordersTable = $this->fetchTable('Orders');
        $order = $ordersTable->patchEntity($order, $data);
        
        return $ordersTable->saveOrFail($order);
    }
}
