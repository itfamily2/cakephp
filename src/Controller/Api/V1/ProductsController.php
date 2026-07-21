<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

        // Allow unauthenticated read access for demo purposes
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

/**
 * GET /api/v1/products.json
 * Supports: ?page=1 &limit=20 &category_id=3 &sort=price &direction=asc
 */
public function index(): void
{
    $query = $this->Products->find()
        ->contain(['Categories', 'Brands']);

    // Optional filters
    if ($categoryId = $this->request->getQuery('category_id')) {
        $query->where(['Products.category_id' => (int)$categoryId]);
    }
    if ($brandId = $this->request->getQuery('brand_id')) {
        $query->where(['Products.brand_id' => (int)$brandId]);
    }
    if ($this->request->getQuery('in_stock')) {
        $query->where(['Products.stock >' => 0]);
    }

    $products = $this->paginate($query, ['limit' => 20, 'maxLimit' => 100]);
    $paging   = $this->request->getAttribute('paging')['Products'] ?? [];

    $this->set('success', true);
    $this->set('data', $products);
    $this->set('pagination', [
        'page'      => $paging['page'] ?? 1,
        'count'     => $paging['count'] ?? 0,
        'perPage'   => $paging['perPage'] ?? 20,
        'pageCount' => $paging['pageCount'] ?? 1,
    ]);
    $this->viewBuilder()->setOption('serialize', ['success', 'data', 'pagination']);
}

/**
 * GET /api/v1/products/{id}.json
 */
public function view(int $id): void
{
    $product = $this->Products->get($id, contain: ['Categories', 'Brands']);
    $this->jsonSuccess($product);
}

/**
 * POST /api/v1/products.json
 */
public function add(): void
{
    $this->request->allowMethod(['post']);
    $product = $this->Products->newEmptyEntity();
    $product = $this->Products->patchEntity($product, $this->request->getData());

    if ($this->Products->save($product)) {
        $this->jsonSuccess($product, 'Product created successfully', 201);
    } else {
        $this->jsonError('Validation failed', $product->getErrors());
    }
}

/**
 * PUT /api/v1/products/{id}.json
 */
public function edit(int $id): void
{
    $this->request->allowMethod(['put', 'patch']);
    $product = $this->Products->get($id);
    $product = $this->Products->patchEntity($product, $this->request->getData());

    if ($this->Products->save($product)) {
        $this->jsonSuccess($product, 'Product updated successfully');
    } else {
        $this->jsonError('Validation failed', $product->getErrors());
    }
}

/**
 * DELETE /api/v1/products/{id}.json
 */
public function delete(int $id): void
{
    $this->request->allowMethod(['delete']);
    $product = $this->Products->get($id);

    if ($this->Products->delete($product)) {
        $this->jsonSuccess([], 'Product deleted successfully');
    } else {
        $this->jsonError('Could not delete product', [], 500);
    }
}
}
