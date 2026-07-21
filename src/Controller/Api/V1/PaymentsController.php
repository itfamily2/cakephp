<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

/** API v1 — Payments Controller */
class PaymentsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

    public function index(): void
    {
        $query = $this->Payments->find()->contain(['Orders']);
        if ($orderId = $this->request->getQuery('order_id')) {
            $query->where(['Payments.order_id' => (int)$orderId]);
        }
        $payments = $this->paginate($query, ['limit' => 20]);
        $paging   = $this->request->getAttribute('paging')['Payments'] ?? [];
        $this->set('success', true);
        $this->set('data', $payments);
        $this->set('pagination', ['page' => $paging['page'] ?? 1, 'count' => $paging['count'] ?? 0]);
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'pagination']);
    }

    public function view(int $id): void
    {
        $this->jsonSuccess($this->Payments->get($id, contain: ['Orders']));
    }

    public function add(): void
    {
        $this->request->allowMethod(['post']);
        $payment = $this->Payments->patchEntity($this->Payments->newEmptyEntity(), $this->request->getData());
        if ($this->Payments->save($payment)) {
            $this->jsonSuccess($payment, 'Payment recorded', 201);
        } else {
            $this->jsonError('Validation failed', $payment->getErrors());
        }
    }

    public function edit(int $id): void
    {
        $this->request->allowMethod(['put', 'patch']);
        $payment = $this->Payments->patchEntity($this->Payments->get($id), $this->request->getData());
        if ($this->Payments->save($payment)) {
            $this->jsonSuccess($payment, 'Payment updated');
        } else {
            $this->jsonError('Validation failed', $payment->getErrors());
        }
    }

    public function delete(int $id): void
    {
        $this->request->allowMethod(['delete']);
        if ($this->Payments->delete($this->Payments->get($id))) {
            $this->jsonSuccess([], 'Payment deleted');
        } else {
            $this->jsonError('Could not delete payment', [], 500);
        }
    }
}
