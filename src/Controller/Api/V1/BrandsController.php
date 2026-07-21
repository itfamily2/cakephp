<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

/** API v1 — Brands Controller */
class BrandsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

    public function index(): void
    {
        $brands = $this->paginate($this->Brands->find(), ['limit' => 50]);
        $paging = $this->request->getAttribute('paging')['Brands'] ?? [];
        $this->set('success', true);
        $this->set('data', $brands);
        $this->set('pagination', ['page' => $paging['page'] ?? 1, 'count' => $paging['count'] ?? 0]);
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'pagination']);
    }

    public function view(int $id): void
    {
        $this->jsonSuccess($this->Brands->get($id, contain: ['Products']));
    }

    public function add(): void
    {
        $this->request->allowMethod(['post']);
        $brand = $this->Brands->patchEntity($this->Brands->newEmptyEntity(), $this->request->getData());
        if ($this->Brands->save($brand)) {
            $this->jsonSuccess($brand, 'Brand created', 201);
        } else {
            $this->jsonError('Validation failed', $brand->getErrors());
        }
    }

    public function edit(int $id): void
    {
        $this->request->allowMethod(['put', 'patch']);
        $brand = $this->Brands->patchEntity($this->Brands->get($id), $this->request->getData());
        if ($this->Brands->save($brand)) {
            $this->jsonSuccess($brand, 'Brand updated');
        } else {
            $this->jsonError('Validation failed', $brand->getErrors());
        }
    }

    public function delete(int $id): void
    {
        $this->request->allowMethod(['delete']);
        if ($this->Brands->delete($this->Brands->get($id))) {
            $this->jsonSuccess([], 'Brand deleted');
        } else {
            $this->jsonError('Could not delete brand', [], 500);
        }
    }
}
