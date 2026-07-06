<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Admin\OrdersController — Admin Prefix Controller (Phase 3: /admin/orders/{id})
 *
 * WHAT THIS DEMONSTRATES:
 *   Admin-prefix controllers manage sensitive order operations that regular
 *   users cannot perform — status overrides, refunds, manual assignments.
 *
 * URL MAPPING:
 *   GET  /admin/orders              → index()  — list all orders (all users)
 *   GET  /admin/orders/{id}         → view($id) — view full order detail
 *   POST /admin/orders/{id}/status  → updateStatus($id) — override order status
 *
 * KEY DIFFERENCE from public OrdersController:
 *   - Admin version shows ALL users' orders (no user_id scope filter)
 *   - Admin can change any order status manually
 *   - Admin can assign orders to different users
 *
 * INTERVIEW TALKING POINT:
 *   "The admin prefix lets me have two controllers for the same model:
 *    one scoped to the current user (/orders → only my orders), and one
 *    with full data access (/admin/orders → all orders). Same ORM query
 *    builder, different WHERE clauses and authorization scope."
 */
class OrdersController extends AppController
{
    /**
     * List ALL orders across all users — admin-only view.
     *
     * URL: GET /admin/orders
     */
    public function index(): void
    {
        $ordersTable = $this->fetchTable('Orders');

        // Admin sees ALL orders, not just current user's
        $query = $ordersTable->find()
            ->contain(['Users'])
            ->order(['Orders.created' => 'DESC']);

        // Search filter by order number or user email
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'Orders.order_number LIKE' => '%' . $search . '%',
                    'Users.email LIKE'         => '%' . $search . '%',
                ]
            ]);
        }

        // Status filter — filter by order status
        $status = $this->request->getQuery('status');
        if (!empty($status)) {
            $query->where(['Orders.status' => $status]);
        }

        $orders = $this->paginate($query, [
            'limit' => 25,
            'sortableFields' => ['Orders.created', 'Orders.total', 'Orders.status', 'Orders.order_number'],
        ]);

        // AJAX partial rendering
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        // Pass distinct statuses for the filter dropdown
        $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Refunded'];

        $this->set(compact('orders', 'search', 'status', 'statuses'));
    }

    /**
     * View a single order's complete detail.
     *
     * URL: GET /admin/orders/{id}
     *
     * NOTE: The {id} in the URL is passed as the $id parameter here.
     * This is the "Dynamic Route" pattern — {id} is declared in routes.php
     * with 'pass' => ['id'] which injects it as a positional argument.
     *
     * @param int|null $id Order ID from URL parameter /admin/orders/{id}
     */
    public function view(?int $id = null): void
    {
        // 1. Load order with ALL related data for the admin detail view
        $order = $this->fetchTable('Orders')->get($id, contain: [
            'Users',      // Who placed the order
            'OrderItems' => ['Products'],  // What items, with product details
            'Payments',   // Payment history
        ]);

        $this->set(compact('order'));
    }

    /**
     * Admin update order status — manually override any order status.
     *
     * URL: POST /admin/orders/{id}/update-status
     * @param int|null $id Order ID
     */
    public function updateStatus(?int $id = null): void
    {
        $this->request->allowMethod(['post', 'put']);

        $ordersTable = $this->fetchTable('Orders');
        $order = $ordersTable->get($id);

        $newStatus = $this->request->getData('status');

        // 1. Apply the new status
        $order->status = $newStatus;

        if ($ordersTable->save($order)) {
            $this->Notification->success(__('Order #{0} status updated to {1}.', $order->order_number, $newStatus));
        } else {
            $this->Notification->error(__('Could not update order status.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Admin delete order — hard delete (use with caution).
     *
     * URL: DELETE /admin/orders/{id}
     * @param int|null $id Order ID
     */
    public function delete(?int $id = null): void
    {
        $this->request->allowMethod(['post', 'delete']);

        $ordersTable = $this->fetchTable('Orders');
        $order = $ordersTable->get($id);

        if ($ordersTable->delete($order)) {
            $this->Notification->success(__('Order deleted.'));
        } else {
            $this->Notification->error(__('Order could not be deleted.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
