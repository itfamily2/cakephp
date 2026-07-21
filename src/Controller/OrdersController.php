<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use App\Service\OrderService;

/**
 * OrdersController — Phase 6: Practical ORM Implementation
 *
 * This controller replaces the baked CRUD with practical e-commerce logic,
 * heavily annotated to demonstrate Phase 6 ORM concepts in a real-world context.
 *
 * PHASE 6 CONCEPTS IMPLEMENTED HERE:
 *   ✅ Transactions     → checkout() method wraps order creation + stock reduction
 *   ✅ Eager Loading    → contain() in index() and view()
 *   ✅ Filtering        → matching() to filter orders by specific products
 *   ✅ Joins            → leftJoinWith() for complex sorting without data loading
 *   ✅ Marshalling      → patchEntity() with nested associations in checkout()
 *   ✅ Query Builder    → updateAll() for bulk status updates
 */
class OrdersController extends AppController
{
    /**
     * skip auth for testing these phase 6 features
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authorization->skipAuthorization();
        $this->Authentication->addUnauthenticatedActions(['index', 'view', 'checkout', 'bulkUpdateStatus', 'advancedSearch']);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $this->getEventManager()->off($this->FormProtection);
        }
    }

    /**
     * index() — Phase 6: Eager Loading (contain)
     *
     * URL: /orders
     */
    public function index()
    {
        // 1. Eager Loading via contain()
        // Prevents N+1 query problem by fetching related User data efficiently.
        $query = $this->Orders->find()
            ->contain(['Users' => ['fields' => ['id', 'username', 'email']]]) // Only fetch specific fields
            ->orderBy(['Orders.created' => 'DESC']);

        $orders = $this->paginate($query);
        $this->set(compact('orders'));
        
        // Optionally return JSON if requested
        if ($this->request->is('json')) {
            $this->viewBuilder()->setClassName('Json');
            $this->viewBuilder()->setOption('serialize', ['orders']);
        }
    }

    /**
     * view() — Phase 6: Deep Eager Loading
     *
     * URL: /orders/view/1
     */
    public function view($id = null)
    {
        // Deep eager loading: Order -> OrderItems -> Products
        $order = $this->Orders->get($id, [
            'contain' => [
                'Users',
                'OrderItems' => ['Products'], 
                'Payments'
            ]
        ]);

        $this->set(compact('order'));
        
        if ($this->request->is('json')) {
            $this->viewBuilder()->setClassName('Json');
            $this->viewBuilder()->setOption('serialize', ['order']);
        }
    }

    /**
     * advancedSearch() — Phase 6: matching(), innerJoinWith(), leftJoinWith()
     *
     * URL: /orders/advanced-search?product_id=5
     *
     * INTERVIEW: "I use matching() when I need to filter the main table (Orders)
     * based on conditions in a related table (OrderItems/Products). I use
     * leftJoinWith() when I need to sort by a related column but don't need
     * the related data loaded into memory."
     */
    public function advancedSearch()
    {
        $query = $this->Orders->find();

        $productId = $this->request->getQuery('product_id');
        
        if ($productId) {
            // PHASE 6: matching()
            // Finds all orders that contain a specific product.
            // Generates an INNER JOIN to order_items.
            $query->matching('OrderItems', function ($q) use ($productId) {
                return $q->where(['OrderItems.product_id' => $productId]);
            });
        }

        // PHASE 6: leftJoinWith()
        // Join with payments to find orders with no payments (unpaid)
        // without eager-loading all payment records.
        $unpaidOrders = $this->Orders->find()
            ->leftJoinWith('Payments')
            ->where(['Payments.id IS' => null])
            ->toArray();

        $results = $query->all();

        $this->set(compact('results', 'unpaidOrders'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['results', 'unpaidOrders']);
    }

    /**
     * checkout() — Phase 6: Transactions, Marshalling, Nested Saves
     *
     * URL: POST /orders/checkout
     *
     * INTERVIEW: "Checkout is the classic transaction example. You cannot create
     * an order unless you also successfully deduct stock from products. By wrapping
     * the logic in ConnectionManager::transactional(), I guarantee that if the stock
     * deduction fails, the order creation is rolled back."
     */
    public function checkout(OrderService $orderService)
    {
        $this->request->allowMethod(['post']);

        // Example simulated cart payload
        $cartData = $this->request->getData();
        if (empty($cartData)) {
            throw new \Exception("Cart data is required.");
        }

        try {
            $order = $orderService->createOrder($cartData);
            $message = 'Checkout successful';
            $data = $order;
        } catch (\Exception $e) {
            $message = 'Checkout failed: ' . $e->getMessage();
            $data = null;
        }

        $this->set(compact('message', 'data'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message', 'data']);
    }

    /**
     * changeStatus() — Transitions order status using OrderService
     *
     * URL: POST /orders/change-status/:id
     */
    public function changeStatus($id, OrderService $orderService)
    {
        $this->request->allowMethod(['post']);
        
        $newStatus = $this->request->getData('status');
        $notes = $this->request->getData('notes');
        $userId = $this->Authentication->getIdentity()?->getIdentifier();

        try {
            $order = $orderService->transitionStatus($id, $newStatus, $userId, $notes);
            $message = 'Status transitioned to ' . $newStatus;
            $data = $order;
        } catch (\Exception $e) {
            $message = 'Status transition failed: ' . $e->getMessage();
            $data = null;
        }

        $this->set(compact('message', 'data'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message', 'data']);
    }

    /**
     * bulkUpdateStatus() — Phase 6: Bulk Update via Query Builder
     *
     * URL: POST /orders/bulk-update-status
     *
     * INTERVIEW: "When updating hundreds of records, patching and saving entities
     * in a loop is too slow. The Query Builder's updateAll() method executes a single
     * UPDATE SQL statement, bypassing the ORM event lifecycle for maximum performance."
     */
    public function bulkUpdateStatus()
    {
        $this->request->allowMethod(['post']);
        
        $newStatus = $this->request->getData('status', 'Processing');
        $orderIds = $this->request->getData('order_ids', []);

        if (empty($orderIds)) {
            $updatedCount = 0;
        } else {
            // PHASE 6: Query Builder Update
            // Executes: UPDATE orders SET status = 'Processing' WHERE id IN (...)
            $updatedCount = $this->Orders->updateAll(
                ['status' => $newStatus], // Fields to update
                ['id IN' => $orderIds]    // Conditions
            );
        }

        $this->set(compact('updatedCount', 'newStatus'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['updatedCount', 'newStatus']);
    }

    /**
     * add() — Phase 6: newEntity and Associated Data
     *
     * URL: POST /orders/add
     */
    public function add(OrderService $orderService)
    {
        $order = $this->Orders->newEmptyEntity();
        if ($this->request->is('post')) {
            try {
                $cartData = $this->request->getData();
                $cartData['user_id'] = $this->Authentication->getIdentity()?->getIdentifier();
                
                $createdOrder = $orderService->createOrder($cartData);
                
                // Dispatch CakePHP Event
                $event = new \Cake\Event\Event('Model.Order.created', $this, [
                    'order' => $createdOrder
                ]);
                $this->getEventManager()->dispatch($event);
                
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    $this->set([
                        'success' => true,
                        'message' => 'The order has been created successfully.',
                        'order' => $createdOrder
                    ]);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'message', 'order']);
                    return;
                }

                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The order has been created successfully.'));
                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    $this->set([
                        'success' => false,
                        'message' => 'Order failed: ' . $e->getMessage()
                    ]);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                    return;
                }
                $this->Notification->error(__('Order failed: ') . $e->getMessage());
            }
        }
        $users = $this->Orders->Users->find('list', limit: 200)->all();
        $products = TableRegistry::getTableLocator()->get('Products')->find('list', [
            'keyField' => 'id',
            'valueField' => function ($product) {
                return $product->name . ' - $' . number_format((float)$product->price, 2) . ' (' . $product->stock . ' in stock)';
            }
        ])->where(['stock >' => 0])->all();

        $this->set(compact('order', 'users', 'products'));
    }

    /**
     * edit() — Phase 6: patchEntity and Associated Data
     *
     * URL: POST /orders/edit/{id}
     */
    public function edit($id = null, OrderService $orderService)
    {
        $order = $this->Orders->get($id, contain: ['OrderItems' => ['Products']]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $cartData = $this->request->getData();
                
                // Set the default saveStrategy to replace so missing items are deleted
                $this->Orders->OrderItems->setSaveStrategy('replace');
                
                $updatedOrder = $orderService->updateOrder($order, $cartData);
                
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    $this->set([
                        'success' => true,
                        'message' => 'The order has been updated successfully.',
                        'order' => $updatedOrder
                    ]);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'message', 'order']);
                    return;
                }

                
                if ($this->request->is('ajax')) {
                    return $this->response->withType('application/json')->withStringBody(json_encode([
                        'success' => true,
                        'message' => __('Record saved successfully.')
                    ]));
                }

$this->Notification->success(__('The order has been saved.'));
                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    $this->set([
                        'success' => false,
                        'message' => 'Update failed: ' . $e->getMessage()
                    ]);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                    return;
                }
                $this->Notification->error(__('Update failed: ') . $e->getMessage());
            }
        }
        
        $users = $this->Orders->Users->find('list', limit: 200)->all();
        $products = \Cake\ORM\TableRegistry::getTableLocator()->get('Products')->find('list', [
            'keyField' => 'id',
            'valueField' => function ($product) {
                return $product->name . ' - $' . number_format((float)$product->price, 2) . ' (' . $product->stock . ' in stock)';
            }
        ])->where(['stock >' => 0])->all();
        
        $this->set(compact('order', 'users', 'products'));
    }

    /**
     * delete() — Phase 6: Delete & Dependencies
     *
     * URL: POST /orders/delete/{id}
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $order = $this->Orders->get($id);
        
        // PHASE 6: delete()
        // If dependent => true was set on OrderItems, deleting this order
        // would automatically delete its items.
        if ($this->Orders->delete($order)) {
            $this->Notification->success(__('The order has been deleted.'));
        } else {
            $this->Notification->error(__('The order could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
