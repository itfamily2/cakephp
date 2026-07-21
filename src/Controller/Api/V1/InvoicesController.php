<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Service\InvoiceService;
use Exception;

/**
 * API v1 — Invoices Controller
 *
 * INTERVIEW NOTE:
 *   - CakePHP 5 supports Dependency Injection in action methods.
 *     InvoiceService is auto-injected by the DI container into add().
 *   - This keeps business logic out of the controller (thin controller principle).
 */
class InvoicesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

    public function index(): void
    {
        $query = $this->Invoices->find()->contain(['Orders']);
        if ($status = $this->request->getQuery('status')) {
            $query->where(['Invoices.status' => $status]);
        }
        $invoices = $this->paginate($query, ['limit' => 20]);
        $paging   = $this->request->getAttribute('paging')['Invoices'] ?? [];

        $this->set('success', true);
        $this->set('data', $invoices);
        $this->set('pagination', ['page' => $paging['page'] ?? 1, 'count' => $paging['count'] ?? 0]);
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'pagination']);
    }

    public function view(int $id): void
    {
        $invoice = $this->Invoices->get($id, contain: ['Orders' => ['OrderItems' => ['Products']]]);
        $this->jsonSuccess($invoice);
    }

    /** POST /api/v1/invoices — Generate invoice from an order via InvoiceService */
    public function add(InvoiceService $invoiceService): void
    {
        $this->request->allowMethod(['post']);
        try {
            $invoice = $invoiceService->generateFromOrder(
                (int)$this->request->getData('order_id'),
                $this->request->getData('notes'),
            );
            $this->jsonSuccess($invoice, 'Invoice generated successfully', 201);
        } catch (Exception $e) {
            $this->jsonError($e->getMessage(), [], 422);
        }
    }

    public function edit(int $id): void
    {
        $this->request->allowMethod(['put', 'patch']);
        $invoice = $this->Invoices->patchEntity($this->Invoices->get($id), $this->request->getData());
        if ($this->Invoices->save($invoice)) {
            $this->jsonSuccess($invoice, 'Invoice updated');
        } else {
            $this->jsonError('Validation failed', $invoice->getErrors());
        }
    }

    public function delete(int $id): void
    {
        $this->request->allowMethod(['delete']);
        if ($this->Invoices->delete($this->Invoices->get($id))) {
            $this->jsonSuccess([], 'Invoice deleted');
        } else {
            $this->jsonError('Could not delete invoice', [], 500);
        }
    }
}
