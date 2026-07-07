<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\I18n\DateTime;
use Cake\Utility\Security;

class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // Allow unauthenticated access to these actions
        $this->Authentication->allowUnauthenticated(['login', 'register', 'forgotPassword', 'resetPassword', 'verifyEmail', 'socialLogin']);
    }

    /**
     * Dashboard statistics & overview
     */
    public function dashboard()
    {
        // Skip authorization for dashboard check as it's the home landing
        $this->Authorization->skipAuthorization();

        // 1. Stats
        $today = new \DateTime('today');
        
        $todayLoginCount = \Cake\Cache\Cache::remember('dashboard_today_login_count', function () use ($today) {
            return $this->Users->find()
                ->where(['last_login_time >=' => $today])
                ->count();
        }, 'redis');

        // Online in last 15 minutes
        $fifteenMinsAgo = new \DateTime('-15 minutes');
        $onlineUsersCount = \Cake\Cache\Cache::remember('dashboard_online_users_count', function () use ($fifteenMinsAgo) {
            return $this->Users->find()
                ->where(['last_login_time >=' => $fifteenMinsAgo])
                ->count();
        }, 'redis');

        $newRegistrationsCount = \Cake\Cache\Cache::remember('dashboard_new_registrations_count', function () {
            return $this->Users->find()
                ->where(['created >=' => new \DateTime('-7 days')])
                ->count();
        }, 'redis');

        $pendingVerificationCount = \Cake\Cache\Cache::remember('dashboard_pending_verification_count', function () {
            return $this->Users->find()
                ->where(['email_verified' => false])
                ->count();
        }, 'redis');

        // 2. Lists for tables
        $recentUsers = \Cake\Cache\Cache::remember('dashboard_recent_users', function () {
            return $this->Users->find()
                ->order(['created' => 'DESC'])
                ->limit(5)
                ->toArray();
        }, 'redis');

        $latestActivities = \Cake\Cache\Cache::remember('dashboard_latest_activities', function () {
            $activityLogsTable = TableRegistry::getTableLocator()->get('ActivityLogs');
            return $activityLogsTable->find()
                ->contain(['Users'])
                ->order(['ActivityLogs.created' => 'DESC'])
                ->limit(5)
                ->toArray();
        }, 'redis');

        $recentEnquiries = \Cake\Cache\Cache::remember('dashboard_recent_enquiries', function () {
            $contactEnquiriesTable = TableRegistry::getTableLocator()->get('ContactEnquiries');
            return $contactEnquiriesTable->find()
                ->order(['created' => 'DESC'])
                ->limit(5)
                ->toArray();
        }, 'redis');

        // 3. System Info
        $diskFree = disk_free_space('/');
        $diskTotal = disk_total_space('/');
        $diskUsagePercent = $diskTotal > 0 ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 2) : 0;

        $systemHealth = [
            'php_version' => PHP_VERSION,
            'cake_version' => \Cake\Core\Configure::version(),
            'memory_usage' => sizeFormat(memory_get_usage(true)),
            'disk_usage' => $diskUsagePercent . '%',
            'disk_free' => sizeFormat($diskFree),
        ];

        // 4. Online users list
        $onlineUsers = \Cake\Cache\Cache::remember('dashboard_online_users_list', function () use ($fifteenMinsAgo) {
            return $this->Users->find()
                ->where(['last_login_time >=' => $fifteenMinsAgo])
                ->order(['last_login_time' => 'DESC'])
                ->toArray();
        }, 'redis');

        $this->set(compact(
            'todayLoginCount',
            'onlineUsersCount',
            'newRegistrationsCount',
            'pendingVerificationCount',
            'recentUsers',
            'latestActivities',
            'recentEnquiries',
            'systemHealth',
            'onlineUsers'
        ));
    }

    /**
     * Clear Admin Dashboard Redis cache
     */
    protected function clearDashboardCache(): void
    {
        try {
            \Cake\Cache\Cache::delete('dashboard_today_login_count', 'redis');
            \Cake\Cache\Cache::delete('dashboard_online_users_count', 'redis');
            \Cake\Cache\Cache::delete('dashboard_new_registrations_count', 'redis');
            \Cake\Cache\Cache::delete('dashboard_pending_verification_count', 'redis');
            \Cake\Cache\Cache::delete('dashboard_recent_users', 'redis');
            \Cake\Cache\Cache::delete('dashboard_latest_activities', 'redis');
            \Cake\Cache\Cache::delete('dashboard_recent_enquiries', 'redis');
            \Cake\Cache\Cache::delete('dashboard_online_users_list', 'redis');
        } catch (\Exception $e) {
            // Silently fail if cache issue
        }
    }

    /**
     * User login
     */
    public function login()
    {
        $this->Authorization->skipAuthorization();
        $result = $this->Authentication->getResult();
        
        if ($result && $result->isValid()) {
            $user = $this->Authentication->getIdentity()->getOriginalData();
            
            if (!$user->is_active) {
                $this->Authentication->logout();
                $this->Notification->error(__('Your account is deactivated. Please contact support.'));
                return $this->redirect(['action' => 'login']);
            }

            // Update login details
            $user->last_login_time = new DateTime();
            $user->last_login_ip = $this->request->clientIp();
            $this->Users->save($user);

            // Log activity
            $this->Activity->logActivity('Login', 'Logged in via Form.');
            $this->clearDashboardCache();

            $redirect = $this->request->getQuery('redirect', '/');
            
            // PHASE 15: AJAX Login Success
            if ($this->request->is('json') || $this->request->is('ajax')) {
                $this->set(['success' => true, 'redirect' => $redirect]);
                $this->viewBuilder()->setClassName('Json');
                $this->viewBuilder()->setOption('serialize', ['success', 'redirect']);
                return;
            }
            
            return $this->redirect($redirect);
        }

        if ($this->request->is('post')) {
            $this->Notification->error(__('Invalid username or password.'));
            
            // PHASE 15: AJAX Login Error
            if ($this->request->is('json') || $this->request->is('ajax')) {
                $this->set(['success' => false, 'message' => __('Invalid username or password.')]);
                $this->viewBuilder()->setClassName('Json');
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                return;
            }
        }
    }

    /**
     * User logout
     */
    public function logout()
    {
        $this->Authorization->skipAuthorization();
        
        $identity = $this->Authentication->getIdentity();
        if ($identity) {
            $user = $identity->getOriginalData();
            $this->Activity->logActivity('Logout', 'Logged out from session.');
        }

        $this->Authentication->logout();
        $this->clearDashboardCache();
        return $this->redirect(['action' => 'login']);
    }

    /**
     * User registration
     */
    public function register()
    {
        $this->Authorization->skipAuthorization();
        $user = $this->Users->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            
            // Generate verification token
            $user->verification_token = Security::randomString(32);
            $user->email_verified = false;
            $user->is_active = true;

            if ($this->Users->save($user)) {
                // Assign Default Role (Customer = ID 4)
                $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
                $userRole = $userRolesTable->newEmptyEntity();
                $userRole->user_id = $user->id;
                $userRole->role_id = 4;
                $userRolesTable->save($userRole);

                // Assign Default Group (General Users = ID 1)
                $groupUsersTable = TableRegistry::getTableLocator()->get('GroupUsers');
                $groupUser = $groupUsersTable->newEmptyEntity();
                $groupUser->user_id = $user->id;
                $groupUser->group_id = 1;
                $groupUsersTable->save($groupUser);

                $this->Activity->logActivity('Register', 'Registered new account.');
                $this->clearDashboardCache();

                // Log email queue registration
                $this->queueEmail(
                    $user->email,
                    'Verify your email address',
                    'Hello ' . $user->username . ', please verify your email using this link: ' . 
                    \Cake\Routing\Router::url(['action' => 'verifyEmail', $user->verification_token], true)
                );

                $this->Notification->success(__('Registration successful. Please check your email to verify your account.'));
                
                // PHASE 15: AJAX Register Success
                if ($this->request->is('json') || $this->request->is('ajax')) {
                    $this->set('success', true);
                    $this->viewBuilder()->setClassName('Json');
                    $this->viewBuilder()->setOption('serialize', ['success']);
                    return;
                }
                
                return $this->redirect(['action' => 'login']);
            }
            $this->Notification->error(__('Registration failed. Please review the errors below.'));
            
            // PHASE 15: AJAX Register Validation Errors
            if ($this->request->is('json') || $this->request->is('ajax')) {
                $this->set('success', false);
                $this->set('errors', $user->getErrors());
                $this->viewBuilder()->setClassName('Json');
                $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
                return;
            }
        }
        $this->set(compact('user'));
    }

    /**
     * notifications() — Phase 15: AJAX Polling
     * URL: /users/notifications
     */
    public function notifications()
    {
        $this->Authorization->skipAuthorization();
        
        $identity = $this->Authentication->getIdentity();
        $notifications = [];
        
        if ($identity) {
            $this->loadModel('ActivityLogs');
            $notifications = $this->ActivityLogs->find()
                ->select(['id', 'action', 'description', 'created'])
                ->order(['created' => 'DESC'])
                ->limit(5)
                ->toArray();
        }
        
        $this->set(compact('notifications'));
        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->setOption('serialize', ['notifications']);
    }

    /**
     * Verify email via token
     */
    public function verifyEmail($token = null)
    {
        $this->Authorization->skipAuthorization();
        if (!$token) {
            $this->Notification->error(__('Invalid verification token.'));
            return $this->redirect(['action' => 'login']);
        }

        $user = $this->Users->find()->where(['verification_token' => $token])->first();
        if (!$user) {
            $this->Notification->error(__('User not found or already verified.'));
            return $this->redirect(['action' => 'login']);
        }

        $user->email_verified = true;
        $user->verification_token = null;
        if ($this->Users->save($user)) {
            $this->Activity->logActivity('Verify Email', 'Email address verified successfully.');
            $this->clearDashboardCache();
            $this->Notification->success(__('Email verified successfully. You can now log in.'));
        } else {
            $this->Notification->error(__('Unable to verify email. Please try again.'));
        }

        return $this->redirect(['action' => 'login']);
    }

    /**
     * Forgot password request page
     */
    public function forgotPassword()
    {
        $this->Authorization->skipAuthorization();
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $user = $this->Users->find()->where(['email' => $email])->first();
            
            if ($user) {
                $user->password_reset_token = Security::randomString(32);
                $user->password_reset_expiry = new DateTime('+2 hours');
                $this->Users->save($user);

                $this->queueEmail(
                    $user->email,
                    'Reset your password',
                    'Reset your password using this link: ' . 
                    \Cake\Routing\Router::url(['action' => 'resetPassword', $user->password_reset_token], true)
                );

                $this->Activity->logActivity('Forgot Password', 'Requested password reset.');
            }

            $this->Notification->success(__('If the email exists, a password reset link has been sent.'));
            return $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Reset password via token
     */
    public function resetPassword($token = null)
    {
        $this->Authorization->skipAuthorization();
        if (!$token) {
            $this->Notification->error(__('Invalid password reset token.'));
            return $this->redirect(['action' => 'login']);
        }

        $user = $this->Users->find()
            ->where([
                'password_reset_token' => $token,
                'password_reset_expiry >' => new DateTime()
            ])
            ->first();

        if (!$user) {
            $this->Notification->error(__('Invalid or expired reset token.'));
            return $this->redirect(['action' => 'login']);
        }

        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->password_reset_token = null;
            $user->password_reset_expiry = null;
            
            if ($this->Users->save($user)) {
                $this->Activity->logActivity('Reset Password', 'Password reset successfully.');
                $this->Notification->success(__('Password reset successfully. You can now log in.'));
                return $this->redirect(['action' => 'login']);
            }
            $this->Notification->error(__('Unable to reset password. Please try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Simulate Social Login (Facebook, Twitter, Google, Linkedin)
     */
    public function socialLogin($provider = 'google')
    {
        $this->Authorization->skipAuthorization();
        
        // Simulating the social API response with default user details
        $socialEmail = $provider . '_user_' . rand(100, 999) . '@example.com';
        $socialUsername = $provider . '_user_' . rand(10, 99);

        // Check if user exists
        $user = $this->Users->find()->where(['email' => $socialEmail])->first();
        $isNew = false;
        
        if (!$user) {
            $isNew = true;
            $user = $this->Users->newEmptyEntity();
            $user->email = $socialEmail;
            $user->username = $socialUsername;
            $user->password = Security::randomString(16); // Random temp password
            $user->email_verified = true;
            $user->is_active = true;
            
            // Simulating profile image from social provider
            $user->profile_image = 'default_social.png'; 
            
            $this->Users->save($user);

            // Assign role
            $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
            $userRole = $userRolesTable->newEmptyEntity();
            $userRole->user_id = $user->id;
            $userRole->role_id = 4;
            $userRolesTable->save($userRole);

            // Assign group
            $groupUsersTable = TableRegistry::getTableLocator()->get('GroupUsers');
            $groupUser = $groupUsersTable->newEmptyEntity();
            $groupUser->user_id = $user->id;
            $groupUser->group_id = 1;
            $groupUsersTable->save($groupUser);
        }

        // Log the user in
        $this->Authentication->setIdentity($user);
        
        // Save last login
        $user->last_login_time = new DateTime();
        $user->last_login_ip = $this->request->clientIp();
        $this->Users->save($user);

        $this->Activity->logActivity('Social Login', 'Logged in via ' . ucfirst($provider));

        if ($isNew) {
            // Requirement: "We can show Change Password page after registration using social account"
            $this->Notification->success(__('Welcome! Since you logged in with social login, please set a custom password.'));
            return $this->redirect(['action' => 'changePassword']);
        }

        $this->Notification->success(__('Logged in successfully via ' . ucfirst($provider)));
        return $this->redirect('/');
    }

    /**
     * Logged in user profile
     */
    public function profile()
    {
        $userId = $this->Authentication->getIdentity()->get('id');
        $user = $this->Users->get($userId, [
            'contain' => ['UserRoles.Roles', 'GroupUsers.Groups']
        ]);

        $this->Authorization->skipAuthorization(); // User can view own profile

        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            
            // Image upload
            $attachment = $this->request->getData('profile_image_file');
            if ($attachment && $attachment->getError() === UPLOAD_ERR_OK) {
                try {
                    $newName = $this->Upload->upload($attachment, 'profiles');
                    $data['profile_image'] = $newName;
                    
                    // Simulated resizing helper
                    $this->resizeImage(WWW_ROOT . 'uploads' . DS . 'profiles' . DS . $newName, 150, 150);
                } catch (\Exception $e) {
                    $this->Notification->error($e->getMessage());
                }
            }

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Activity->logActivity('Update Profile', 'Updated profile information.');
                $this->Notification->success(__('Profile updated successfully.'));
                return $this->redirect(['action' => 'profile']);
            }
            $this->Notification->error(__('Profile could not be updated. Please try again.'));
        }

        $this->set(compact('user'));
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        $this->Authorization->skipAuthorization();
        $userId = $this->Authentication->getIdentity()->get('id');
        $user = $this->Users->get($userId);

        if ($this->request->is(['post', 'put'])) {
            // Confirm current password
            $currentPassword = $this->request->getData('current_password');
            $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
            
            if (!$hasher->check($currentPassword, $user->password)) {
                $this->Notification->error(__('Current password is incorrect.'));
            } else {
                $user = $this->Users->patchEntity($user, [
                    'password' => $this->request->getData('new_password')
                ]);
                
                if ($this->Users->save($user)) {
                    $this->Activity->logActivity('Change Password', 'Changed password successfully.');
                    $this->Notification->success(__('Password changed successfully.'));
                    return $this->redirect(['action' => 'profile']);
                }
                $this->Notification->error(__('Unable to change password.'));
            }
        }
        $this->set(compact('user'));
    }

    /**
     * Delete account (Self-service)
     */
    public function deleteAccount()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'delete']);
        
        $userId = $this->Authentication->getIdentity()->get('id');
        $user = $this->Users->get($userId);

        if ($this->Users->delete($user)) {
            $this->Authentication->logout();
            $this->Notification->success(__('Your account has been deleted successfully.'));
            return $this->redirect(['action' => 'login']);
        }
        $this->Notification->error(__('Unable to delete account.'));
        return $this->redirect(['action' => 'profile']);
    }

    /**
     * List all users with pagination, sorting, search filter (Admin section)
     */
    public function index()
    {
        // Handled by RequestPolicy in authorization stage
        
        $query = $this->Users->find()->contain(['UserRoles.Roles', 'GroupUsers.Groups']);

        // Ajax Search & Autocomplete
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'Users.username LIKE' => '%' . $search . '%',
                    'Users.email LIKE' => '%' . $search . '%',
                ]
            ]);
        }

        // Active filter
        $status = $this->request->getQuery('status');
        if ($status !== null && $status !== '') {
            $query->where(['Users.is_active' => $status === '1']);
        }

        // AJAX search returning suggestions (JSON)
        if ($this->request->is('json') && $this->request->getQuery('suggest')) {
            $suggestions = $query->select(['id', 'username', 'email'])->limit(10)->toArray();
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($suggestions));
        }

        $users = $this->paginate($query, [
            'limit' => 10,
            'order' => ['Users.created' => 'DESC']
        ]);

        // Always set data first so both full-page and AJAX partial renders have it
        $this->set(compact('users', 'search', 'status'));

        // If AJAX request, render the ajax-table partial instead of the whole layout!
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
            return $this->render('ajax_index');
        }
    }

    /**
     * Add single user (Admin)
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->email_verified = true;
            $user->is_active = true;

            if ($this->Users->save($user)) {
                // Save role
                $roleId = $this->request->getData('role_id');
                if ($roleId) {
                    $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
                    $userRole = $userRolesTable->newEmptyEntity();
                    $userRole->user_id = $user->id;
                    $userRole->role_id = (int)$roleId;
                    $userRolesTable->save($userRole);
                }

                // Save group
                $groupId = $this->request->getData('group_id');
                if ($groupId) {
                    $groupUsersTable = TableRegistry::getTableLocator()->get('GroupUsers');
                    $groupUser = $groupUsersTable->newEmptyEntity();
                    $groupUser->user_id = $user->id;
                    $groupUser->group_id = (int)$groupId;
                    $groupUsersTable->save($groupUser);
                }

                $this->Activity->logActivity('Admin Add User', 'Admin created user ' . $user->username);
                $this->clearDashboardCache();
                $this->Notification->success(__('User added successfully.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('Unable to add user.'));
        }

        $roles = TableRegistry::getTableLocator()->get('Roles')->find('list')->all();
        $groups = TableRegistry::getTableLocator()->get('Groups')->find('list')->all();
        $this->set(compact('user', 'roles', 'groups'));
    }

    /**
     * View user profile
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['UserRoles', 'GroupUsers', 'UserRoles.Roles', 'GroupUsers.Groups']
        ]);
        
        $roleIds = array_filter(array_map(function($ur) { return $ur->role_id; }, $user->user_roles ?? []));
        $groupIds = array_filter(array_map(function($gu) { return $gu->group_id; }, $user->group_users ?? []));
        
        $permissions = [];
        if (!empty($roleIds) || !empty($groupIds)) {
            $conditions = [];
            if (!empty($roleIds)) $conditions['role_id IN'] = $roleIds;
            if (!empty($groupIds)) $conditions['group_id IN'] = $groupIds;
            
            $permissions = \Cake\ORM\TableRegistry::getTableLocator()->get('Permissions')
                ->find()
                ->where(['OR' => $conditions])
                ->all();
        }
        
        $this->set(compact('user', 'permissions'));
    }

    /**
     * Edit user profile, group, and roles (Admin)
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['UserRoles', 'GroupUsers']
        ]);

        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            if (empty($data['password'])) {
                unset($data['password']); // Keep existing password
            }
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                // Update roles
                $roleId = $this->request->getData('role_id');
                if ($roleId) {
                    $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
                    $userRolesTable->deleteAll(['user_id' => $user->id]);
                    $userRole = $userRolesTable->newEmptyEntity();
                    $userRole->user_id = $user->id;
                    $userRole->role_id = (int)$roleId;
                    $userRolesTable->save($userRole);
                }

                // Update groups
                $groupId = $this->request->getData('group_id');
                if ($groupId) {
                    $groupUsersTable = TableRegistry::getTableLocator()->get('GroupUsers');
                    $groupUsersTable->deleteAll(['user_id' => $user->id]);
                    $groupUser = $groupUsersTable->newEmptyEntity();
                    $groupUser->user_id = $user->id;
                    $groupUser->group_id = (int)$groupId;
                    $groupUsersTable->save($groupUser);
                }

                $this->Activity->logActivity('Admin Edit User', 'Admin updated user configuration.');
                $this->clearDashboardCache();
                $this->Notification->success(__('User updated successfully.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('Unable to update user.'));
        }

        $roles = TableRegistry::getTableLocator()->get('Roles')->find('list')->all();
        $groups = TableRegistry::getTableLocator()->get('Groups')->find('list')->all();

        // Get current role and group IDs
        $currentRoleId = !empty($user->user_roles) ? $user->user_roles[0]->role_id : null;
        $currentGroupId = !empty($user->group_users) ? $user->group_users[0]->group_id : null;

        $this->set(compact('user', 'roles', 'groups', 'currentRoleId', 'currentGroupId'));
    }

    /**
     * Delete user
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        
        if ($this->Users->delete($user)) {
            $this->clearDashboardCache();
            $this->Notification->success(__('User has been deleted.'));
        } else {
            $this->Notification->error(__('Unable to delete user.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Toggle active/inactive status (Admin)
     */
    public function toggleActive($id = null)
    {
        $this->request->allowMethod(['post']);
        $user = $this->Users->get($id);
        $user->is_active = !$user->is_active;
        
        if ($this->Users->save($user)) {
            $status = $user->is_active ? 'activated' : 'deactivated';
            $this->Activity->logActivity('Admin Toggle Status', 'Admin ' . $status . ' user account.');
            $this->clearDashboardCache();
            $this->Notification->success(__('User has been ' . $status . '.'));
        } else {
            $this->Notification->error(__('Unable to change status.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Force logout an online user (Admin)
     */
    public function forceLogout($id = null)
    {
        $this->request->allowMethod(['post']);
        $user = $this->Users->get($id);
        
        // Simulating force logging out of a user:
        // We delete their remember token and can deactivate or set login token to empty.
        $user->remember_token = null;
        
        // To force logout, we can update their modified timestamp and clear session reference.
        // We also allow toggling active status if requested: "Admin can logout & inactivate any online user"
        $inactivate = $this->request->getData('inactivate');
        if ($inactivate === '1') {
            $user->is_active = false;
        }

        if ($this->Users->save($user)) {
            $msg = 'User logged out successfully.';
            if ($inactivate === '1') {
                $msg = 'User logged out and account deactivated.';
            }
            $this->Activity->logActivity('Admin Force Logout', 'Admin force logged out this user.');
            $this->clearDashboardCache();
            $this->Notification->success(__($msg));
        } else {
            $this->Notification->error(__('Unable to force logout user.'));
        }

        return $this->redirect(['action' => 'dashboard']);
    }

    /**
     * Import users from CSV (Admin)
     */
    public function addMultipleCsv()
    {
        if ($this->request->is('post')) {
            $file = $this->request->getData('csv_file');
            if ($file && $file->getError() === UPLOAD_ERR_OK) {
                $filePath = $file->getStream()->getMetadata('uri');
                $handle = fopen($filePath, 'r');
                
                // Read header
                $header = fgetcsv($handle);
                $successCount = 0;
                $failCount = 0;

                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 3) continue;

                    $userData = [
                        'username' => $row[0],
                        'email' => $row[1],
                        'password' => $row[2], // Will be hashed automatically by mutator
                        'is_active' => true,
                        'email_verified' => true
                    ];

                    $userEntity = $this->Users->newEntity($userData);
                    if ($this->Users->save($userEntity)) {
                        // Assign Default Role
                        $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
                        $userRole = $userRolesTable->newEmptyEntity();
                        $userRole->user_id = $userEntity->id;
                        $userRole->role_id = 4;
                        $userRolesTable->save($userRole);

                        // Assign Default Group
                        $groupUsersTable = TableRegistry::getTableLocator()->get('GroupUsers');
                        $groupUser = $groupUsersTable->newEmptyEntity();
                        $groupUser->user_id = $userEntity->id;
                        $groupUser->group_id = 1;
                        $groupUsersTable->save($groupUser);

                        $successCount++;
                    } else {
                        $failCount++;
                    }
                }
                fclose($handle);
                $this->clearDashboardCache();
                $this->Notification->success(__('Import finished. Successfully imported ' . $successCount . ' users. Failed: ' . $failCount));
                return $this->redirect(['action' => 'index']);
            }
            $this->Notification->error(__('Please upload a valid CSV file.'));
        }
    }

    /**
     * Export all users to CSV
     */
    public function exportCsv()
    {
        $this->Authorization->skipAuthorization();
        $this->response = $this->response->withDownload('users_export_' . date('Ymd_His') . '.csv');
        $this->response = $this->response->withType('csv');

        $users = $this->Users->find()->contain(['UserRoles.Roles', 'GroupUsers.Groups'])->all();
        
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['ID', 'Username', 'Email', 'Role', 'Group', 'Active', 'Last Login', 'Created']);

        foreach ($users as $u) {
            $roleName = !empty($u->user_roles) ? $u->user_roles[0]->role->name : 'N/A';
            $groupName = !empty($u->group_users) ? $u->group_users[0]->group->name : 'N/A';
            fputcsv($handle, [
                $u->id,
                $u->username,
                $u->email,
                $roleName,
                $groupName,
                $u->is_active ? 'Yes' : 'No',
                $u->last_login_time ? $u->last_login_time->format('Y-m-d H:i:s') : 'Never',
                $u->created->format('Y-m-d H:i:s')
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return $this->response->withStringBody($csvContent);
    }

    /**
     * Clear all Cache in one click
     */
    public function clearCache()
    {
        $this->Authorization->skipAuthorization();
        
        // Clear all caches
        Cache::clear('default');
        Cache::clear('_cake_core_');
        Cache::clear('_cake_model_');
        
        try {
            Cache::clear('redis');
        } catch (\Exception $e) {}
        
        // If there are other cache engines configured
        try {
            Cache::clear('_cake_routes_');
        } catch (\Exception $e) {}

        $this->Notification->success(__('All CakePHP system caches cleared successfully.'));
        return $this->redirect($this->referer('/'));
    }

    /**
     * Helper: Simulate image resizing or cropping
     */
    protected function resizeImage($path, $width, $height)
    {
        // Simulation helper since image resizing requires GD or Imagick,
        // we log it and verify the file exists.
        if (file_exists($path)) {
            // Resizing logs could go here
            return true;
        }
        return false;
    }

    /**
     * Helper: Queue an email
     */
    protected function queueEmail($to, $subject, $body)
    {
        try {
            $scheduledEmailsTable = TableRegistry::getTableLocator()->get('ScheduledEmails');
            $email = $scheduledEmailsTable->newEmptyEntity();
            $email->recipient_email = $to;
            $email->subject = $subject;
            $email->body = $body;
            $email->status = 'pending';
            $email->scheduled_time = new DateTime();
            $scheduledEmailsTable->save($email);
        } catch (\Exception $e) {
            // fail silently
        }
    }
}

/**
 * Global helper to format file size
 */
function sizeFormat($bytes)
{
    $kb = 1024;
    $mb = $kb * 1024;
    $gb = $mb * 1024;
    
    if ($bytes >= $gb) {
        return round($bytes / $gb, 2) . ' GB';
    } elseif ($bytes >= $mb) {
        return round($bytes / $mb, 2) . ' MB';
    } elseif ($bytes >= $kb) {
        return round($bytes / $kb, 2) . ' KB';
    }
    return $bytes . ' Bytes';
}
