<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Service\OrderService;
use Exception;

/**
 * API v1 — Orders Controller
 *
 * INTERVIEW NOTE:
 *   - 'associated' => ['OrderItems'] in patchEntity() allows nested saving in one call.
 *   - viewBuilder()->setOption('serialize', [...]) is the CakePHP 5 way to emit JSON.
 *   - $this->request->getAttribute('paging') carries pagination metadata post-paginate().
 */
class OrdersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

    public function index(): void
    {
        $query = $this->Orders->find()->contain(['Users']);

        if ($status = $this->request->getQuery('status')) {
            $query->where(['Orders.status' => $status]);
        }
        if ($userId = $this->request->getQuery('user_id')) {
            $query->where(['Orders.user_id' => (int)$userId]);
        }

        $orders = $this->paginate($query, ['limit' => 20, 'maxLimit' => 100]);
        $paging = $this->request->getAttribute('paging')['Orders'] ?? [];

        $this->set('success', true);
        $this->set('data', $orders);
        $this->set('pagination', [
            'page'      => $paging['page'] ?? 1,
            'count'     => $paging['count'] ?? 0,
            'perPage'   => $paging['perPage'] ?? 20,
            'pageCount' => $paging['pageCount'] ?? 1,
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'pagination']);
    }

    public function view(int $id): void
    {
        // INTERVIEW NOTE: Deep eager loading with contain() prevents N+1 queries.
        // OrderItems.Products means: load OrderItems, then for each item load its Product.
        $order = $this->Orders->get($id, contain: ['Users', 'OrderItems' => ['Products'], 'Payments']);
        $this->jsonSuccess($order);
    }

    public function add(OrderService $orderService): void
    {
        $this->request->allowMethod(['post']);

        try {
            // Service Layer completely abstracts the save, validation, and queue pushing logic.
            // This is a pure "Thin Controller" implementation.
            $order = $orderService->placeOrder($this->request->getData());

            $this->jsonSuccess($order, 'Order created successfully', 201);
        } catch (Exception $e) {
            $this->jsonError($e->getMessage(), [], 422);
        }
    }

    public function edit(int $id): void
    {
        $this->request->allowMethod(['put', 'patch']);
        $order = $this->Orders->get($id);
        $order = $this->Orders->patchEntity($order, $this->request->getData());

        if ($this->Orders->save($order)) {
            $this->jsonSuccess($order, 'Order updated successfully');
        } else {
            $this->jsonError('Validation failed', $order->getErrors());
        }
    }

    public function delete(int $id): void
    {
        $this->request->allowMethod(['delete']);
        if ($this->Orders->delete($this->Orders->get($id))) {
            $this->jsonSuccess([], 'Order deleted successfully');
        } else {
            $this->jsonError('Could not delete order', [], 500);
        }
    }
}
