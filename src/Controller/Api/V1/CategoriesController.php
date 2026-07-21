<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

/** API v1 — Categories Controller */
class CategoriesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

    public function index(): void
    {
        $categories = $this->paginate($this->Categories->find(), ['limit' => 50]);
        $paging = $this->request->getAttribute('paging')['Categories'] ?? [];
        $this->set('success', true);
        $this->set('data', $categories);
        $this->set('pagination', ['page' => $paging['page'] ?? 1, 'count' => $paging['count'] ?? 0]);
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'pagination']);
    }

    public function view(int $id): void
    {
        $this->jsonSuccess($this->Categories->get($id, contain: ['Products']));
    }

    public function add(): void
    {
        $this->request->allowMethod(['post']);
        $category = $this->Categories->patchEntity($this->Categories->newEmptyEntity(), $this->request->getData());
        if ($this->Categories->save($category)) {
            $this->jsonSuccess($category, 'Category created', 201);
        } else {
            $this->jsonError('Validation failed', $category->getErrors());
        }
    }

    public function edit(int $id): void
    {
        $this->request->allowMethod(['put', 'patch']);
        $category = $this->Categories->patchEntity($this->Categories->get($id), $this->request->getData());
        if ($this->Categories->save($category)) {
            $this->jsonSuccess($category, 'Category updated');
        } else {
            $this->jsonError('Validation failed', $category->getErrors());
        }
    }

    public function delete(int $id): void
    {
        $this->request->allowMethod(['delete']);
        if ($this->Categories->delete($this->Categories->get($id))) {
            $this->jsonSuccess([], 'Category deleted');
        } else {
            $this->jsonError('Could not delete category', [], 500);
        }
    }
}
