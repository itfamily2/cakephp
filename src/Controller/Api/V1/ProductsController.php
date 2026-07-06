<?php
declare(strict_types=1);

namespace App\Controller\Api\V1;

use Cake\Http\Exception\NotFoundException;

/**
 * Phase 16 - RESTful Products API
 * Demonstrates CRUD, Pagination, Sorting, Filtering, and JSON serialization.
 */
class ProductsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // For testing/demo purposes, we allow unauthenticated read access
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->Authorization->skipAuthorization();
    }

    /**
     * GET /api/v1/products
     * Pagination, Sorting, and Filtering
     */
    public function index()
    {
        $query = $this->Products->find();

        // Filtering
        if ($this->request->getQuery('category_id')) {
            $query->where(['category_id' => $this->request->getQuery('category_id')]);
        }
        if ($this->request->getQuery('status')) {
            $query->where(['status' => $this->request->getQuery('status')]);
        }

        // Sorting (handled automatically by Paginator if passed in query string like ?sort=price&direction=desc)
        
        // Pagination
        $products = $this->paginate($query, [
            'limit' => 20,
            'maxLimit' => 100
        ]);

        $this->set([
            'success' => true,
            'data' => $products,
            'pagination' => $this->request->getAttribute('paging')['Products'],
            '_serialize' => ['success', 'data', 'pagination']
        ]);
    }

    /**
     * GET /api/v1/products/{id}
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id);
        $this->set([
            'success' => true,
            'data' => $product,
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * POST /api/v1/products
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $product = $this->Products->newEmptyEntity();
        $product = $this->Products->patchEntity($product, $this->request->getData());

        if ($this->Products->save($product)) {
            $this->set([
                'success' => true,
                'message' => 'Product created',
                'data' => $product,
                '_serialize' => ['success', 'message', 'data']
            ]);
        } else {
            $this->response = $this->response->withStatus(422); // Unprocessable Entity
            $this->set([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $product->getErrors(),
                '_serialize' => ['success', 'message', 'errors']
            ]);
        }
    }

    /**
     * PUT /api/v1/products/{id}
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['put', 'patch']);
        $product = $this->Products->get($id);
        $product = $this->Products->patchEntity($product, $this->request->getData());

        if ($this->Products->save($product)) {
            $this->set([
                'success' => true,
                'message' => 'Product updated',
                'data' => $product,
                '_serialize' => ['success', 'message', 'data']
            ]);
        } else {
            $this->response = $this->response->withStatus(422);
            $this->set([
                'success' => false,
                'errors' => $product->getErrors(),
                '_serialize' => ['success', 'errors']
            ]);
        }
    }

    /**
     * DELETE /api/v1/products/{id}
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $product = $this->Products->get($id);

        if ($this->Products->delete($product)) {
            $this->set([
                'success' => true,
                'message' => 'Product deleted',
                '_serialize' => ['success', 'message']
            ]);
        } else {
            $this->response = $this->response->withStatus(500);
            $this->set([
                'success' => false,
                'message' => 'Failed to delete product',
                '_serialize' => ['success', 'message']
            ]);
        }
    }
}
