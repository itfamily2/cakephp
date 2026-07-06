<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController as BaseAppController;

/**
 * Admin\AppController — Base controller for ALL Admin prefix controllers
 *
 * WHAT IT DOES:
 *   Acts as the shared foundation for Admin\UsersController,
 *   Admin\OrdersController, etc. Centralizes admin-specific auth checks,
 *   layout configuration, and shared before-hooks so they don't repeat
 *   in every admin controller.
 *
 * ARCHITECTURE PATTERN:
 *   App\Controller\AppController          ← Global base (all controllers)
 *       └── App\Controller\Admin\AppController  ← Admin base (admin only)
 *               └── Admin\UsersController
 *               └── Admin\OrdersController
 *
 * PHASE 4 TECHNIQUES DEMONSTRATED:
 *   - initialize()   → Load admin-specific components and configuration
 *   - beforeFilter() → Gate access to admin prefix (role check)
 *   - beforeRender() → Inject admin layout variables
 */
class AppController extends BaseAppController
{
    /**
     * initialize() — Phase 4 Pattern
     *
     * Runs once at the start of each request, before the action executes.
     * Load components here, NOT in constructors.
     *
     * EXECUTION ORDER: AppController::initialize() → Admin\AppController::initialize()
     */
    public function initialize(): void
    {
        // 1. Always call parent — loads Flash, Authentication, Authorization, FormProtection
        parent::initialize();

        // 2. Set the admin layout for all admin controllers
        //    Templates will use templates/layout/admin.php if it exists
        //    (falls back to default.php if not found)
        $this->viewBuilder()->setLayout('default');
    }

    /**
     * beforeFilter() — Phase 4 Pattern
     *
     * Runs AFTER initialize(), BEFORE the action method.
     * Use for: access control checks, setting action-specific config,
     * loading request data, applying CSRF exceptions.
     *
     * EXECUTION ORDER:
     *   initialize() → [middleware] → beforeFilter() → action() → beforeRender() → render()
     *
     * @param \Cake\Event\EventInterface $event
     */
    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        // 1. Run parent beforeFilter (handles authentication redirect)
        parent::beforeFilter($event);

        // 2. ADMIN GATE: Verify the logged-in user has Administrator role (role_id = 1)
        //    If not, deny access with a flash message and redirect to home
        $identity = $this->Authentication->getIdentity();

        if ($identity) {
            $user = $identity->getOriginalData();

            // Check admin role in UserRoles
            $userRolesTable = $this->fetchTable('UserRoles');
            $isAdmin = $userRolesTable->find()
                ->where(['user_id' => $user->id, 'role_id' => 1])
                ->count() > 0;

            if (!$isAdmin) {
                // Flash message stored in session, shown after redirect
                $this->Flash->error(__('Access denied. Administrator role required.'));
                return $this->redirect('/');
            }
        }

        // 3. Pass current prefix to all admin views for breadcrumbs etc.
        $this->set('adminPrefix', true);
    }

    /**
     * beforeRender() — Phase 4 Pattern
     *
     * Fires after the action completes but BEFORE the template renders.
     * Use for: adding view-layer data, switching response type,
     * injecting shared sidebar data.
     *
     * @param \Cake\Event\EventInterface $event
     */
    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        parent::beforeRender($event);

        // Inject admin-specific sidebar counts visible on every admin page
        $this->set('pendingOrderCount', $this->fetchTable('Orders')
            ->find()->where(['Orders.status' => 'Pending'])->count());
    }
}
