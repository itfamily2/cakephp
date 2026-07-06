<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * OrmDemosController — Phase 6: Complete ORM Demonstration
 *
 * This controller serves as an interactive tutorial and interview cheat sheet
 * for every CakePHP 5 ORM feature. Each action demonstrates a specific ORM concept.
 *
 * PHASE 6 CONCEPTS DEMONSTRATED:
 *   ✅ Associations    → belongsTo, hasOne, hasMany, belongsToMany
 *   ✅ Query Builder   → Select, Insert, Update, Delete
 *   ✅ Eager Loading   → contain()
 *   ✅ Filtering       → matching(), notMatching(), innerJoinWith(), leftJoinWith()
 *   ✅ Entity State    → newEntity(), patchEntity(), saveMany()
 *   ✅ Transactions    → ConnectionManager::get('default')->transactional()
 *
 * INTERVIEW TALKING POINT:
 *   "CakePHP's ORM is a Data Mapper, not Active Record. Queries return ResultSets
 *    containing Entities. The Query Builder uses method chaining and is lazy-evaluated
 *    — SQL is only executed when you iterate the result or call methods like toArray(),
 *    first(), or count(). This allows complex query composition across methods."
 */
class OrmDemosController extends AppController
{
    /**
     * skip authorization for all tutorial actions
     */
    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(["index", "eagerLoading", "filtering", "bulkOperations", "marshalling", "transactions"]);
        $this->Authorization->skipAuthorization();
    }

    /**
     * Index page for ORM Demos
     */
    public function index(): void
    {
        // Just renders the template with links to the demos
    }

    /**
     * 1. Query Builder: Select, Eager Loading (contain)
     * URL: /orm-demos/eager-loading
     *
     * INTERVIEW: "Contain() is CakePHP's eager loading mechanism. It prevents the
     *   N+1 query problem by loading related data in a few efficient queries rather
     *   than one query per row. Under the hood, hasMany/belongsToMany use a separate
     *   IN() query, while belongsTo/hasOne use LEFT JOINs automatically."
     */
    public function eagerLoading(): void
    {
        $usersTable = $this->fetchTable('Users');

        // Fetch users with their related roles, orders, and order items
        $query = $usersTable->find()
            ->contain([
                'Roles', // belongsToMany
                'Orders' => function ($q) {
                    // Pass a closure to filter or contain nested associations
                    return $q->select(['id', 'user_id', 'status', 'total'])
                             ->where(['Orders.status !=' => 'Cancelled'])
                             ->contain(['OrderItems.Products']); // Nested contain
                }
            ])
            ->limit(5);
            
        // SQL only executes here when we call toArray()
        $users = $query->toArray();

        $this->set('data', $users);
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * 2. Advanced: matching(), notMatching(), innerJoinWith(), leftJoinWith()
     * URL: /orm-demos/filtering
     *
     * INTERVIEW: "While contain() includes related data in the SELECT clause,
     *   matching() filters the main query based on related data using an INNER JOIN.
     *   innerJoinWith() does the join without selecting the related data, useful for
     *   filtering only. notMatching() uses a LEFT JOIN with a NULL check."
     */
    public function filtering(): void
    {
        $productsTable = $this->fetchTable('Products');

        // matching(): Find products that have at least one OrderItem,
        // AND that OrderItem belongs to a specific Order status.
        // It injects the related data into an '_matchingData' property on the entity.
        $orderedProducts = $productsTable->find()
            ->matching('OrderItems.Orders', function ($q) {
                return $q->where(['Orders.status' => 'Completed']);
            })
            ->limit(5)
            ->toArray();

        // notMatching(): Find products that have NEVER been ordered
        // Uses LEFT JOIN order_items ON ... WHERE order_items.id IS NULL
        $neverOrdered = $productsTable->find()
            ->notMatching('OrderItems')
            ->limit(5)
            ->toArray();

        // innerJoinWith(): Filter by association without eager loading the data.
        // Good for performance when you only need the main entity.
        $categoryProducts = $productsTable->find()
            ->innerJoinWith('Categories', function ($q) {
                return $q->where(['Categories.name' => 'Homeopathy']);
            })
            ->limit(5)
            ->toArray();

        $this->set('data', compact('orderedProducts', 'neverOrdered', 'categoryProducts'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * 3. Query Builder: Insert, Update, Delete (Bulk Operations)
     * URL: /orm-demos/bulk-operations
     *
     * INTERVIEW: "Bulk updates and deletes using the query builder bypass
     *   the ORM event system (beforeSave, afterSave). They translate directly
     *   to UPDATE/DELETE SQL statements. This is much faster for thousands of rows
     *   but you lose application-level events and rules."
     */
    public function bulkOperations(): void
    {
        $productsTable = $this->fetchTable('Products');

        // Bulk Update: Increase price of all products in category 1 by 10%
        // Executes: UPDATE products SET price = price * 1.10 WHERE category_id = 1;
        $updatedCount = $productsTable->updateAll(
            ['price = price * 1.10'], // fields to update (expressions allowed)
            ['category_id' => 1]      // conditions
        );

        // Bulk Delete: Delete all products with 0 stock
        // Note: Because Products uses SoftDelete behavior, we might want to bypass it
        // but normally deleteAll executes raw SQL DELETE.
        $deletedCount = $productsTable->deleteAll(['stock' => 0]);

        // Bulk Insert using Query (Cake 5 style)
        // Usually, saveMany is preferred for ORM events, but insert() is faster.
        $query = $productsTable->insertQuery();
        $query->insert(['name', 'sku', 'price', 'stock', 'slug'])
            ->values([
                'name' => 'Bulk Product 1', 'sku' => 'BLK-01', 'price' => 10, 'stock' => 100, 'slug' => 'bulk-1'
            ])
            ->values([
                'name' => 'Bulk Product 2', 'sku' => 'BLK-02', 'price' => 20, 'stock' => 200, 'slug' => 'bulk-2'
            ])
            ->execute(); // Returns statement

        $this->set('data', compact('updatedCount', 'deletedCount'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * 4. Entities: newEntity, patchEntity, saveMany, Marshal
     * URL: /orm-demos/marshalling
     *
     * INTERVIEW: "Marshalling is the process of converting raw request arrays
     *   (like POST data) into Entity objects. patchEntity() merges data into an
     *   existing entity, applying Validation rules. If validation fails, errors
     *   are attached to the entity and saving is blocked."
     */
    public function marshalling(): void
    {
        $usersTable = $this->fetchTable('Users');

        // 1. newEntity (Marshal raw data into a new Entity)
        $rawData = [
            'username' => 'orm_test_user',
            'email' => 'orm@example.com',
            'password' => 'secret123',
            // Associated data can be marshalled too!
            'user_roles' => [
                ['_joinData' => ['assigned_at' => date('Y-m-d H:i:s')], 'role_id' => 2]
            ]
        ];
        
        $newUser = $usersTable->newEntity($rawData, [
            'associated' => ['UserRoles'] // Tell marshaller to parse nested array
        ]);

        // Check if validation failed during marshalling
        $errors = $newUser->getErrors();

        if (empty($errors)) {
            $usersTable->save($newUser);
        }

        // 2. patchEntity (Update existing entity)
        $existingUser = $usersTable->find()->first();
        if ($existingUser) {
            $usersTable->patchEntity($existingUser, ['is_active' => false]);
            $usersTable->save($existingUser);
        }

        // 3. saveMany (Save multiple entities in one transaction)
        $entities = $usersTable->newEntities([
            ['username' => 'bulk1', 'email' => 'bulk1@example.com', 'password' => 'pass1'],
            ['username' => 'bulk2', 'email' => 'bulk2@example.com', 'password' => 'pass2'],
        ]);
        
        // saveMany wraps all saves in a single database transaction automatically
        $savedEntities = $usersTable->saveMany($entities);

        $this->set('data', [
            'new_user' => $newUser,
            'errors' => $errors,
            'patched_user' => $existingUser,
            'saved_many' => $savedEntities
        ]);
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * 5. Transactions (ConnectionManager)
     * URL: /orm-demos/transactions
     *
     * INTERVIEW: "While save(), delete(), and saveMany() automatically wrap
     *   their operations in transactions, manual transactions are needed when
     *   coordinating multiple unrelated table saves. The transactional() method
     *   takes a closure; if an exception is thrown or it returns false, it rolls back."
     */
    public function transactions(): void
    {
        $ordersTable = $this->fetchTable('Orders');
        $productsTable = $this->fetchTable('Products');

        $connection = ConnectionManager::get('default');

        try {
            // transactional() executes the closure inside a DB transaction.
            // It automatically commits on success, or rolls back on Exception.
            $result = $connection->transactional(function ($conn) use ($ordersTable, $productsTable) {
                
                // 1. Create an Order
                $order = $ordersTable->newEntity([
                    'user_id' => 1,
                    'order_number' => 'ORD-TX-001',
                    'total' => 500.00,
                    'status' => 'Pending',
                ]);
                
                if (!$ordersTable->save($order)) {
                    return false; // Rolls back
                }

                // 2. Deduct stock from a product
                $product = $productsTable->get(1);
                $product->stock -= 1;
                
                if (!$productsTable->save($product)) {
                    return false; // Rolls back
                }

                // If we get here, both saves succeeded.
                return true; // Commits
            });
            
            $status = $result ? 'Transaction Committed' : 'Transaction Rolled Back (Save failed)';

        } catch (\Exception $e) {
            // Exception thrown inside closure -> Auto Rollback
            $status = 'Transaction Rolled Back (Exception: ' . $e->getMessage() . ')';
        }

        $this->set('data', ['status' => $status]);
        $this->viewBuilder()->setOption('serialize', ['data']);
    }
}
