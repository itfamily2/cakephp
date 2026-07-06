<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Firebase\JWT\JWT;

/**
 * Users API Controller
 */
class UsersController extends ApiController
{
    /**
     * initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
        
        // Allow public access to public endpoints
        $this->Authentication->allowUnauthenticated(['login', 'register', 'forgotPassword', 'resetPassword', 'verifyEmail']);
    }

    /**
     * User login - Authenticate credentials and return JWT token
     */
    public function login()
    {
        $this->request->allowMethod(['post']);
        
        $username = $this->request->getData('username');
        $password = $this->request->getData('password');

        if (empty($username) || empty($password)) {
            return $this->jsonError('Username/email and password are required.', 400);
        }

        // Find user by username or email
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->find()
            ->where([
                'OR' => [
                    'username' => $username,
                    'email' => $username
                ]
            ])
            ->first();

        if (!$user) {
            return $this->jsonError('Invalid username or email.', 401);
        }

        // Verify password
        $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
        if (!$hasher->check($password, $user->password)) {
            return $this->jsonError('Invalid password.', 401);
        }

        if (!$user->is_active) {
            return $this->jsonError('Your account is deactivated. Please contact support.', 403);
        }

        // Generate JWT Token
        $secret = Configure::read('Security.jwt_secret');
        $payload = [
            'sub' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7) // 7 days expiration
        ];
        
        $token = JWT::encode($payload, $secret, 'HS256');

        // Update last login metadata
        $user->last_login_time = new \DateTime();
        $user->last_login_ip = $this->request->clientIp() ?: '127.0.0.1';
        $usersTable->save($user);

        // Log activity
        $this->Activity->logActivity('API Login', 'Logged in via API JWT.');

        return $this->jsonSuccess([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'created' => $user->created
            ]
        ], 'Login successful');
    }

    /**
     * User registration - Create new customer account via API
     */
    public function register()
    {
        $this->request->allowMethod(['post']);
        
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->newEmptyEntity();
        
        $user = $usersTable->patchEntity($user, $this->request->getData());
        
        // Generate verification token
        $user->verification_token = Security::randomString(32);
        $user->email_verified = false;
        $user->is_active = true;

        if ($usersTable->save($user)) {
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

            // Log activity
            $this->Activity->logActivity('API Register', 'Registered new account via API.');

            // Queue Verification Email
            $this->queueEmail(
                $user->email,
                'Verify your email address',
                'Hello ' . $user->username . ', please verify your email using this link: ' . 
                \Cake\Routing\Router::url(['prefix' => false, 'controller' => 'Users', 'action' => 'verifyEmail', $user->verification_token], true)
            );

            return $this->jsonSuccess([
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created' => $user->created
                ]
            ], 'Registration successful. Please verify your email.', 210);
        }

        // Handle validation errors
        return $this->jsonError('Registration validation failed.', 422, $user->getErrors());
    }

    /**
     * Get profile of authenticated user
     */
    public function profile()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get']);

        $identity = $this->Authentication->getIdentity();
        $user = TableRegistry::getTableLocator()->get('Users')->get($identity->get('id'), [
            'contain' => ['UserRoles.Roles', 'GroupUsers.Groups']
        ]);

        return $this->jsonSuccess([
            'user' => $user
        ], 'Profile retrieved successfully');
    }

    /**
     * Edit profile details of authenticated user
     */
    public function edit()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'put']);

        $identity = $this->Authentication->getIdentity();
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->get($identity->get('id'));

        $data = $this->request->getData();
        
        // Don't allow changing username or email via this endpoint (or check if wanted)
        unset($data['username']);
        
        // Handle password update if passed
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user = $usersTable->patchEntity($user, $data);
        if ($usersTable->save($user)) {
            $this->Activity->logActivity('API Edit Profile', 'Updated profile details via API.');
            return $this->jsonSuccess([
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'modified' => $user->modified
                ]
            ], 'Profile updated successfully');
        }

        return $this->jsonError('Profile update failed validation.', 422, $user->getErrors());
    }

    /**
     * List all users (Admin only)
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $query = $usersTable->find()->contain(['UserRoles.Roles', 'GroupUsers.Groups']);

        // Search filter
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

        $users = $this->paginate($query, [
            'limit' => 10,
            'order' => ['Users.created' => 'DESC']
        ]);

        return $this->jsonSuccess([
            'users' => $users,
            'pagination' => $this->request->getAttribute('paging')['Users'] ?? []
        ], 'Users list retrieved successfully');
    }

    /**
     * Verify email via token (Public)
     */
    public function verifyEmail($token = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'post']);

        if (!$token) {
            return $this->jsonError('Verification token is required.', 400);
        }

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->find()->where(['verification_token' => $token])->first();
        
        if (!$user) {
            return $this->jsonError('Invalid or expired verification token.', 404);
        }

        $user->email_verified = true;
        $user->verification_token = null;
        if ($usersTable->save($user)) {
            $this->Activity->logActivity('API Verify Email', 'Email verified via API.');
            return $this->jsonSuccess([], 'Email verified successfully.');
        }

        return $this->jsonError('Unable to verify email.', 500);
    }

    /**
     * Forgot password request (Public)
     */
    public function forgotPassword()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post']);

        $email = $this->request->getData('email');
        if (empty($email)) {
            return $this->jsonError('Email is required.', 400);
        }

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->find()->where(['email' => $email])->first();

        if ($user) {
            $user->password_reset_token = Security::randomString(32);
            $user->password_reset_expiry = new \DateTime('+2 hours');
            $usersTable->save($user);

            $this->queueEmail(
                $user->email,
                'Reset your password',
                'Reset your password using this link: ' . 
                \Cake\Routing\Router::url(['prefix' => false, 'controller' => 'Users', 'action' => 'resetPassword', $user->password_reset_token], true)
            );

            $this->Activity->logActivity('API Forgot Password', 'Requested password reset via API.');
        }

        return $this->jsonSuccess([], 'If the email exists in our system, a password reset link has been sent.');
    }

    /**
     * Reset password via token (Public)
     */
    public function resetPassword($token = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post']);

        if (!$token) {
            return $this->jsonError('Password reset token is required.', 400);
        }

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->find()
            ->where([
                'password_reset_token' => $token,
                'password_reset_expiry >' => new \DateTime()
            ])
            ->first();

        if (!$user) {
            return $this->jsonError('Invalid or expired password reset token.', 400);
        }

        $password = $this->request->getData('password');
        if (empty($password)) {
            return $this->jsonError('New password is required.', 400);
        }

        $user = $usersTable->patchEntity($user, ['password' => $password]);
        $user->password_reset_token = null;
        $user->password_reset_expiry = null;

        if ($usersTable->save($user)) {
            $this->Activity->logActivity('API Reset Password', 'Password reset successfully via API.');
            return $this->jsonSuccess([], 'Password reset successfully.');
        }

        return $this->jsonError('Password reset failed validation.', 422, $user->getErrors());
    }

    /**
     * Change password for logged-in user (Authenticated)
     */
    public function changePassword()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->get($identity->get('id'));

        $oldPassword = $this->request->getData('old_password');
        $newPassword = $this->request->getData('new_password');

        if (empty($oldPassword) || empty($newPassword)) {
            return $this->jsonError('Old password and new password are required.', 400);
        }

        $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
        if (!$hasher->check($oldPassword, $user->password)) {
            return $this->jsonError('Incorrect old password.', 400);
        }

        $user = $usersTable->patchEntity($user, ['password' => $newPassword]);
        if ($usersTable->save($user)) {
            $this->Activity->logActivity('API Change Password', 'Password changed successfully.');
            return $this->jsonSuccess([], 'Password changed successfully.');
        }

        return $this->jsonError('Password change failed validation.', 422, $user->getErrors());
    }

    /**
     * Add single user (Admin/Staff only)
     */
    public function addSingleUser()
    {
        $this->request->allowMethod(['post']);

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->newEmptyEntity();
        
        $data = $this->request->getData();
        $user = $usersTable->patchEntity($user, $data);
        
        $user->email_verified = true;
        $user->is_active = true;

        if ($usersTable->save($user)) {
            $roleId = (int)$this->request->getData('role_id', 4);
            $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
            $userRole = $userRolesTable->newEmptyEntity();
            $userRole->user_id = $user->id;
            $userRole->role_id = $roleId;
            $userRolesTable->save($userRole);

            $groupId = (int)$this->request->getData('group_id', 1);
            $groupUsersTable = TableRegistry::getTableLocator()->get('GroupUsers');
            $groupUser = $groupUsersTable->newEmptyEntity();
            $groupUser->user_id = $user->id;
            $groupUser->group_id = $groupId;
            $groupUsersTable->save($groupUser);

            $this->Activity->logActivity('API Admin Add User', 'User added by administrator.');

            return $this->jsonSuccess([
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created' => $user->created
                ]
            ], 'User added successfully.', 201);
        }

        return $this->jsonError('User addition failed validation.', 422, $user->getErrors());
    }

    /**
     * Add multiple users via CSV (Admin/Staff only)
     */
    public function addMultipleCsv()
    {
        $this->request->allowMethod(['post']);

        $file = $this->request->getData('csv_file');
        if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
            return $this->jsonError('Please upload a valid CSV file.', 400);
        }

        $filePath = $file->getStream()->getMetadata('uri');
        $handle = fopen($filePath, 'r');
        
        // Read header
        $header = fgetcsv($handle);
        $successCount = 0;
        $failCount = 0;
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) continue;

            $userData = [
                'username' => $row[0],
                'email' => $row[1],
                'password' => $row[2],
                'is_active' => true,
                'email_verified' => true
            ];

            $userEntity = $usersTable->newEntity($userData);
            if ($usersTable->save($userEntity)) {
                $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
                $userRole = $userRolesTable->newEmptyEntity();
                $userRole->user_id = $userEntity->id;
                $userRole->role_id = 4;
                $userRolesTable->save($userRole);

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

        return $this->jsonSuccess([
            'success_count' => $successCount,
            'fail_count' => $failCount
        ], 'CSV import finished.');
    }

    /**
     * Activate/Inactivate user status (Admin only)
     */
    public function status($id = null)
    {
        $this->request->allowMethod(['post', 'put']);

        if (!$id) {
            return $this->jsonError('User ID is required.', 400);
        }

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->get($id);

        $isActive = (bool)$this->request->getData('is_active');
        $user->is_active = $isActive;

        if ($usersTable->save($user)) {
            $actionWord = $isActive ? 'Activated' : 'Inactivated';
            $this->Activity->logActivity('API User Status Change', 'User account ' . strtolower($actionWord) . ' by administrator.');
            return $this->jsonSuccess([
                'user' => [
                    'id' => $user->id,
                    'is_active' => $user->is_active
                ]
            ], 'User account status updated to ' . ($isActive ? 'Active' : 'Inactive'));
        }

        return $this->jsonError('Unable to update user status.', 500);
    }

    /**
     * Delete user account (Admin or Self)
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);

        if (!$id) {
            return $this->jsonError('User ID is required.', 400);
        }

        $identity = $this->Authentication->getIdentity();
        $currentUserId = $identity->get('id');

        if ((int)$id === (int)$currentUserId) {
            $this->Authorization->skipAuthorization();
        }

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->get($id);

        if ($usersTable->delete($user)) {
            $this->logActivity($currentUserId, 'API Delete Account', 'Deleted user ID ' . $id);
            return $this->jsonSuccess([], 'Account deleted successfully.');
        }

        return $this->jsonError('Unable to delete account.', 500);
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
            $email->scheduled_time = new \Cake\I18n\DateTime();
            $scheduledEmailsTable->save($email);
        } catch (\Exception $e) {
            // fail silently
        }
    }
}
