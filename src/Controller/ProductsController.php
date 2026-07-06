<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

/**
 * ProductsController — Phase 6: Deep ORM Implementations
 *
 * Demonstrates the power of CakePHP's Query Builder in a catalog context.
 *
 * PHASE 6 CONCEPTS IMPLEMENTED HERE:
 *   ✅ Query Builder (Select)  → custom finders, aggregate queries
 *   ✅ Eager Loading           → contain() with conditions
 *   ✅ SaveMany                → bulk price updates via entity saving
 *   ✅ NewEntity / Marshal     → handling complex product creation
 */
class ProductsController extends AppController
{
    /**
     * skip auth for testing phase 6 features
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authorization->skipAuthorization();
        $this->Authentication->addUnauthenticatedActions(['index', 'view', 'bulkPriceIncrease', 'outOfStock']);
    }

    /**
     * index() — Phase 6: Eager Loading and Custom Finders
     *
     * URL: /products
     */
    public function index()
    {
        // PHASE 6: Custom Finders
        // find('available') is defined in ProductsTable::findAvailable()
        $query = $this->Products->find('available')
            ->contain([
                'Categories', // belongsTo
                'Brands'      // belongsTo
            ])
            ->orderBy(['Products.name' => 'ASC']);

        // Check if filtering by category (e.g. ?category_id=5)
        $categoryId = $this->request->getQuery('category_id');
        if ($categoryId) {
            $query->find('byCategory', ['category_id' => $categoryId]);
        }

        $products = $this->paginate($query);
        
        // Add Pagination metadata for frontend
        $paging = $this->request->getAttribute('paging');
        
        $this->set(compact('products', 'paging'));
        
        if ($this->request->is('json') || $this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Json');
            $this->viewBuilder()->setOption('serialize', ['products', 'paging']);
        }
    }

    /**
     * autocomplete() — Phase 15: AJAX Autocomplete
     * URL: /products/autocomplete?term=xyz
     */
    public function autocomplete()
    {
        $this->request->allowMethod(['get']);
        $term = $this->request->getQuery('term', '');
        
        $query = $this->Products->find()
            ->select(['id', 'name'])
            ->where(['name LIKE' => "%$term%"])
            ->limit(10);
            
        $this->set('suggestions', $query->toArray());
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['suggestions']);
    }

    /**
     * view() — Phase 6: contain() with conditions
     *
     * URL: /products/view/1
     */
    public function view($id = null)
    {
        // Fetch product, its category, and only completed orders containing this product
        $product = $this->Products->get($id, [
            'contain' => [
                'Categories',
                'Brands',
                'OrderItems' => function ($q) {
                    return $q->contain(['Orders'])
                             ->where(['Orders.status' => 'Completed']);
                }
            ]
        ]);

        $this->set(compact('product'));
        if ($this->request->is('json')) {
            $this->viewBuilder()->setClassName('Json');
            $this->viewBuilder()->setOption('serialize', ['product']);
        }
    }

    /**
     * outOfStock() — Phase 6: Query Builder Selects & NotMatching
     *
     * URL: /products/out-of-stock
     */
    public function outOfStock()
    {
        // 1. Query Builder Select: just fetch names and SKUs of OOS products
        $oosProducts = $this->Products->find()
            ->select(['id', 'name', 'sku', 'price'])
            ->where(['stock <=' => 0])
            ->toArray();

        // 2. PHASE 6: notMatching()
        // Find products that have never been ordered
        $neverOrdered = $this->Products->find()
            ->notMatching('OrderItems')
            ->select(['id', 'name'])
            ->toArray();

        $this->set(compact('oosProducts', 'neverOrdered'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['oosProducts', 'neverOrdered']);
    }

    /**
     * bulkPriceIncrease() — Phase 6: SaveMany vs UpdateAll
     *
     * URL: POST /products/bulk-price-increase
     *
     * INTERVIEW: "While updateAll() is faster because it executes a single SQL query,
     * it bypasses ORM events (like setting the 'modified' timestamp or triggering
     * Cache invalidation in afterSave). Using saveMany() retrieves the entities,
     * updates them, and saves them in a transaction, ensuring all model events fire."
     */
    public function bulkPriceIncrease()
    {
        $this->request->allowMethod(['post']);

        $percentage = (float) $this->request->getData('percentage', 10);
        $multiplier = 1 + ($percentage / 100);

        // Fetch all products in a specific category
        $categoryId = $this->request->getData('category_id');
        $products = $this->Products->find()
            ->where(['category_id' => $categoryId])
            ->toArray();

        // Update the entities in memory
        foreach ($products as $product) {
            // Because price is a decimal, cast to float for calculation, then round
            $product->price = round((float)$product->price * $multiplier, 2);
            // The Entity mutator tracks that 'price' has been modified
        }

        // PHASE 6: saveMany()
        // This wraps all the UPDATE statements in a single transaction
        // AND fires beforeSave/afterSave for each entity.
        if ($this->Products->saveMany($products)) {
            $message = count($products) . ' products updated successfully.';
        } else {
            $message = 'Failed to update products.';
        }

        $this->set(compact('message', 'products'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['message', 'products']);
    }

    /**
     * add() — Phase 6: newEntity and Marshal
     *
     * URL: POST /products/add
     */
    public function add()
    {
        // 1. Create a new empty entity (or marshal from POST data)
        $product = $this->Products->newEmptyEntity();

        if ($this->request->is('post')) {
            // PHASE 6: Marshal data into the entity.
            // Validation rules are applied here. If validation fails,
            // $product->hasErrors() becomes true.
            $product = $this->Products->patchEntity($product, $this->request->getData());

            if ($this->Products->save($product)) {
                $this->Notification->success(__('The product has been saved.'));
                
                // PHASE 15: AJAX CRUD Response
                if ($this->request->is('json') || $this->request->is('ajax')) {
                    $this->set('success', true);
                    $this->set('product', $product);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'product']);
                    return;
                }
                
                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The product could not be saved. Please, try again.'));
            
            // PHASE 15: AJAX Validation Errors
            if ($this->request->is('json') || $this->request->is('ajax')) {
                $this->set('success', false);
                $this->set('errors', $product->getErrors());
                $this->viewBuilder()->setClassName('Json');
                $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
                return;
            }
        }

        // Phase 6: Query Builder — Fetch lists for dropdowns
        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $brands = $this->Products->Brands->find('list', limit: 200)->all();
        
        $this->set(compact('product', 'categories', 'brands'));
    }

    /**
     * edit() — Phase 6: patchEntity and Update
     *
     * URL: POST /products/edit/{id}
     */
    public function edit($id = null)
    {
        // Fetch the existing entity
        $product = $this->Products->get($id, contain: []);

        if ($this->request->is(['patch', 'post', 'put'])) {
            // PHASE 6: patchEntity merges new request data into the existing entity,
            // tracking which fields became 'dirty' (changed).
            $product = $this->Products->patchEntity($product, $this->request->getData());

            if ($this->Products->save($product)) {
                $this->Notification->success(__('The product has been updated.'));
                
                // PHASE 15: AJAX CRUD Update
                if ($this->request->is('json') || $this->request->is('ajax')) {
                    $this->set('success', true);
                    $this->set('product', $product);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success', 'product']);
                    return;
                }
                
                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('The product could not be updated. Please, try again.'));
            
            if ($this->request->is('json') || $this->request->is('ajax')) {
                $this->set('success', false);
                $this->set('errors', $product->getErrors());
                $this->viewBuilder()->setClassName('Json');
                $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
                return;
            }
        }

        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $brands = $this->Products->Brands->find('list', limit: 200)->all();
        
        $this->set(compact('product', 'categories', 'brands'));
    }

    /**
     * delete() — Phase 6: Delete & SoftDelete Behavior
     *
     * URL: POST /products/delete/{id}
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);

        // PHASE 6: delete() triggers Model.beforeDelete.
        // Because we attached SoftDeleteBehavior to the ProductsTable,
        // this will NOT execute a DELETE statement. It will execute an UPDATE
        $success = false;
        
        if ($this->Products->delete($product)) {
            $success = true;
            $this->Notification->success(__('The product has been deleted.'));
        } else {
            $this->Notification->error(__('The product could not be deleted.'));
        }

        // PHASE 15: AJAX Delete Response
        if ($this->request->is('json') || $this->request->is('ajax')) {
            $this->set(compact('success'));
            $this->viewBuilder()->setClassName('Json');
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        return $this->redirect(['action' => 'index']);
    }
}
