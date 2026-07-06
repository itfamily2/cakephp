<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * DashboardController — Phase 4: Dedicated Dashboard Controller
 *
 * WHAT THIS DEMONSTRATES:
 *   Separates dashboard logic from UsersController (where it lived before).
 *   A dedicated DashboardController follows the Single Responsibility Principle:
 *   UsersController manages users, DashboardController manages the admin portal.
 *
 * WHY A SEPARATE CONTROLLER:
 *   - Dashboard is not a CRUD operation on Users — it aggregates multiple models
 *   - Keeps UsersController focused on user management actions
 *   - Allows different authorization policies (any logged-in user can see the
 *     dashboard, but not all can manage users)
 *
 * PHASE 4 CONCEPTS DEMONSTRATED:
 *   - initialize()      → loading components, setting per-action config
 *   - beforeRender()    → injecting shared view variables to all actions
 *   - paginate()        → built-in controller pagination
 *   - Flash component   → session-based user notifications
 *   - Session access    → via Authentication component
 *   - Request access    → $this->request->getQuery(), getParam()
 *   - JSON response     → $this->response->withType('application/json')
 *
 * INTERVIEW TALKING POINT:
 *   "I separated the Dashboard into its own controller because it aggregates
 *    data from Users, Orders, ActivityLogs, and ContactEnquiries — it has no
 *    business being inside UsersController. Each controller should own one
 *    domain concept."
 */
class DashboardController extends AppController
{
    /**
     * initialize() — runs once per request before any action.
     *
     * PHASE 4: initialize() hook
     * Use this for: loading components, setting controller-wide config,
     * registering custom table locators, or applying auth bypass rules.
     *
     * ORDER: AppController::initialize() → DashboardController::initialize()
     */
    public function initialize(): void
    {
        // 1. Always call parent to load Flash, Auth, Authorization components
        parent::initialize();

        // 2. Controller-level pagination config (overrides global config)
        //    All paginate() calls in this controller default to 5 records
        $this->paginate = [
            'limit' => 5,
        ];
    }

    /**
     * Main dashboard — aggregates KPIs and recent data across all modules.
     *
     * URL: GET /
     * URL: GET /dashboard
     *
     * PHASE 4 CONCEPTS:
     *   - Cache::remember() for expensive aggregation queries
     *   - Multiple table lookups via fetchTable()
     *   - beforeRender() injects $currentUser to layout
     */
    public function index(): void
    {
        // Skip authorization — all logged-in users can see the dashboard
        $this->Authorization->skipAuthorization();

        // -------------------------------------------------------------------
        // 1. KPI Counts — cached in Redis to avoid recalculating on every load
        //    Cache key invalidated in afterSave() via clearDashboardCache()
        // -------------------------------------------------------------------
        $totalUsers = \Cake\Cache\Cache::remember('dashboard_total_users', function () {
            return $this->fetchTable('Users')->find()->count();
        }, 'redis');

        $totalOrders = \Cake\Cache\Cache::remember('dashboard_total_orders', function () {
            return $this->fetchTable('Orders')->find()->count();
        }, 'redis');

        $totalProducts = \Cake\Cache\Cache::remember('dashboard_total_products', function () {
            return $this->fetchTable('Products')->find()->count();
        }, 'redis');

        $pendingEnquiries = \Cake\Cache\Cache::remember('dashboard_pending_enquiries', function () {
            return $this->fetchTable('ContactEnquiries')->find()
                ->where(['ContactEnquiries.replied' => false])
                ->count();
        }, 'redis');

        // -------------------------------------------------------------------
        // 2. Recent Activity Table — last 5 user actions
        // -------------------------------------------------------------------
        $recentActivities = \Cake\Cache\Cache::remember('dashboard_recent_activities', function () {
            return $this->fetchTable('ActivityLogs')->find()
                ->contain(['Users'])
                ->order(['ActivityLogs.created' => 'DESC'])
                ->limit(5)
                ->toArray();
        }, 'redis');

        // -------------------------------------------------------------------
        // 3. Recent Users — last 5 registered users
        // -------------------------------------------------------------------
        $recentUsers = \Cake\Cache\Cache::remember('dashboard_recent_users', function () {
            return $this->fetchTable('Users')->find()
                ->order(['Users.created' => 'DESC'])
                ->limit(5)
                ->toArray();
        }, 'redis');

        // -------------------------------------------------------------------
        // 4. Recent Orders — last 5 orders
        // -------------------------------------------------------------------
        $recentOrders = \Cake\Cache\Cache::remember('dashboard_recent_orders', function () {
            return $this->fetchTable('Orders')->find()
                ->contain(['Users'])
                ->order(['Orders.created' => 'DESC'])
                ->limit(5)
                ->toArray();
        }, 'redis');

        // -------------------------------------------------------------------
        // 5. Pass all data to the view using set() + compact()
        //    compact() is a PHP function that creates ['key' => $key] arrays
        // -------------------------------------------------------------------
        $this->set(compact(
            'totalUsers',
            'totalOrders',
            'totalProducts',
            'pendingEnquiries',
            'recentActivities',
            'recentUsers',
            'recentOrders'
        ));
    }

    /**
     * beforeRender() — runs AFTER action, BEFORE view template is rendered.
     *
     * PHASE 4: beforeRender() hook
     * Use this for: injecting variables needed by EVERY template (layout vars),
     * switching view class (JSON/XML), appending CSS/JS per controller.
     *
     * @param \Cake\Event\EventInterface $event The beforeRender event
     */
    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        parent::beforeRender($event);

        // Inject current timestamp into every Dashboard view
        // Available as $renderTime in all Dashboard templates
        $this->set('renderTime', new \DateTime());
    }

    /**
     * Live stats — AJAX endpoint returning JSON KPIs for chart updates.
     *
     * URL: GET /dashboard/live-stats
     * Returns: JSON { users: N, orders: N, products: N }
     *
     * PHASE 4: JSON response pattern
     */
    public function liveStats(): void
    {
        $this->Authorization->skipAuthorization();

        // Set view class to JSON — no template needed
        $this->viewBuilder()->setClassName('Json');

        $stats = [
            'users'    => $this->fetchTable('Users')->find()->count(),
            'orders'   => $this->fetchTable('Orders')->find()->count(),
            'products' => $this->fetchTable('Products')->find()->count(),
        ];

        // _serialize tells JsonView which variables to serialize
        $this->set(compact('stats'));
        $this->viewBuilder()->setOption('serialize', ['stats']);
    }

    /**
     * clearCache — Admin action to flush all dashboard caches.
     *
     * URL: GET /dashboard/clear-cache
     *
     * PHASE 4: redirect() pattern + Flash component usage
     */
    public function clearCache(): void
    {
        $this->Authorization->skipAuthorization();

        // Clear all Redis dashboard cache keys
        $keys = [
            'dashboard_total_users',
            'dashboard_total_orders',
            'dashboard_total_products',
            'dashboard_pending_enquiries',
            'dashboard_recent_activities',
            'dashboard_recent_users',
            'dashboard_recent_orders',
        ];

        foreach ($keys as $key) {
            \Cake\Cache\Cache::delete($key, 'redis');
        }

        // Flash: one-time session message shown after redirect
        $this->Notification->success(__('Dashboard cache cleared successfully.'));

        // redirect() returns a Response object — always return it
        return $this->redirect(['action' => 'index']);
    }
}
