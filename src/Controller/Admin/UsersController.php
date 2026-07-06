<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Admin\UsersController — Admin Prefix Controller (Phase 3: Admin/Prefix Route)
 *
 * WHAT THIS DEMONSTRATES:
 *   When a route uses prefix('Admin'), CakePHP automatically looks for the
 *   controller in the sub-namespace App\Controller\Admin\ instead of
 *   App\Controller\. This enforces architectural separation between
 *   public-facing and admin-only actions.
 *
 * URL MAPPING:
 *   GET  /admin/users          → Admin\UsersController::index()
 *   GET  /admin/users/{id}     → Admin\UsersController::view($id)
 *   POST /admin/users          → Admin\UsersController::add()
 *   POST /admin/users/{id}     → Admin\UsersController::edit($id)
 *
 * NAMESPACE RULE:
 *   Route prefix  → Admin
 *   PHP namespace → App\Controller\Admin
 *   File path     → src/Controller/Admin/UsersController.php
 *
 * INTERVIEW TALKING POINT:
 *   "The Admin prefix namespace is NOT just a URL convention — it actually
 *    loads a completely different PHP class from a different folder. An
 *    attacker who finds /users cannot automatically access /admin/users
 *    because they map to entirely separate controller classes with separate
 *    authorization checks."
 */
class UsersController extends AppController
{
    /**
     * Admin index — list ALL users with full admin controls.
     * Unlike the public users listing, admins see inactive/unverified users.
     *
     * URL: GET /admin/users
     */
    public function index(): ?\Cake\Http\Response
    {
        // 1. Load the Users table via TableRegistry (or via $this->Users if loaded)
        $usersTable = $this->fetchTable('Users');

        // 2. Build base query — admins see ALL users including inactive ones
        $query = $usersTable->find()
            ->contain(['UserRoles.Roles', 'GroupUsers.Groups'])
            ->order(['Users.created' => 'DESC']);

        // 3. Admin search filter — search by username or email
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'Users.username LIKE' => '%' . $search . '%',
                    'Users.email LIKE'    => '%' . $search . '%',
                ]
            ]);
        }

        // 4. Status filter — can filter active/inactive/all
        $status = $this->request->getQuery('status');
        if ($status !== null && $status !== '') {
            $query->where(['Users.is_active' => (bool)$status]);
        }

        // 5. Paginate results — admins see 20 per page with full sorting
        $users = $this->paginate($query, [
            'limit' => 20,
            'sortableFields' => ['Users.username', 'Users.email', 'Users.created', 'Users.is_active'],
        ]);

        // 6. Return AJAX partial or full page
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }

        $this->set(compact('users', 'search', 'status'));
    }

    /**
     * Admin view — view single user's complete profile and history.
     *
     * URL: GET /admin/users/{id}
     * @param int|null $id User ID from URL parameter
     */
    public function view(?int $id = null): ?\Cake\Http\Response
    {
        // 1. Load user with all associated data for admin view
        $user = $this->fetchTable('Users')->get($id, contain: [
            'UserRoles.Roles',
            'GroupUsers.Groups',
            'ActivityLogs' => ['limit' => 10, 'sort' => ['ActivityLogs.created' => 'DESC']],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Admin add — create a new user bypassing email verification.
     *
     * URL: POST /admin/users
     */
    public function add(): ?\Cake\Http\Response
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Admin-created accounts are auto-verified
            $data['is_active']      = true;
            $data['email_verified'] = true;

            $user = $usersTable->patchEntity($user, $data);

            if ($usersTable->save($user)) {
                $this->Notification->success(__('User created successfully by admin.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('Could not create user.'));
        }

        $roles  = $this->fetchTable('Roles')->find('list')->all();
        $groups = $this->fetchTable('Groups')->find('list')->all();

        $this->set(compact('user', 'roles', 'groups'));
    }

    /**
     * Admin edit — edit any user's data including password reset.
     *
     * URL: POST /admin/users/{id}
     * @param int|null $id User ID
     */
    public function edit(?int $id = null): ?\Cake\Http\Response
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id, contain: []);

        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();

            // Only hash password if a new one was provided
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user = $usersTable->patchEntity($user, $data);

            if ($usersTable->save($user)) {
                $this->Notification->success(__('User updated by admin.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('Could not update user.'));
        }

        $this->set(compact('user'));
    }

    /**
     * Admin delete — hard-delete a user account.
     *
     * URL: DELETE /admin/users/{id}
     * @param int|null $id User ID
     */
    public function delete(?int $id = null): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post', 'delete']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if ($usersTable->delete($user)) {
            $this->Notification->success(__('User deleted.'));
        } else {
            $this->Notification->error(__('User could not be deleted.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Admin toggleActive — activate or deactivate a user account.
     *
     * URL: POST /admin/users/toggle-active/{id}
     * @param int|null $id User ID
     */
    public function toggleActive(?int $id = null): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        // Toggle the is_active boolean
        $user->is_active = !$user->is_active;

        if ($usersTable->save($user)) {
            $state = $user->is_active ? 'activated' : 'deactivated';
            $this->Notification->success(__("User has been {0}.", $state));
        } else {
            $this->Notification->error(__('Could not update user status.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
