<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Authorization\Policy\RequestPolicyInterface;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;

class RequestPolicy implements RequestPolicyInterface
{
    /**
     * Magic __call method to catch any authorization action.
     */
    public function __call(string $name, array $arguments): bool
    {
        if (str_starts_with($name, 'can')) {
            $identity = $arguments[0] ?? null;
            $request = $arguments[1] ?? null;
            if ($request instanceof ServerRequest) {
                return $this->canAccess($identity, $request);
            }
        }
        return false;
    }

    /**
     * Check if the request is authorized.
     */
    public function canAccess(?IdentityInterface $identity, ServerRequest $request): bool
    {
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');
        $prefix = $request->getParam('prefix');
        $plugin = $request->getParam('plugin');

        // Normalize names
        $controller = $controller ? strtolower($controller) : '';
        $action = $action ? strtolower($action) : '';
        $prefix = $prefix ? strtolower($prefix) : '';
        $plugin = $plugin ? strtolower($plugin) : '';

        if ($plugin === 'debugkit') {
            return true;
        }

        // List of public actions (accessible by anyone)
        $publicActions = [
            'users' => ['login', 'register', 'forgotpassword', 'resetpassword', 'verifyemail', 'logout', 'sociallogin'],
            'cmspages' => ['view'],
            'contactenquiries' => ['add'],
        ];

        if (isset($publicActions[$controller]) && in_array($action, $publicActions[$controller]) && !$prefix) {
            return true;
        }

        // If no user is logged in, redirect to login
        if ($identity === null) {
            return false;
        }

        // Self-service actions allowed for any authenticated user (web and API)
        $selfServiceActions = [
            'users' => ['profile', 'edit', 'logout', 'changepassword', 'deleteaccount']
        ];
        if (isset($selfServiceActions[$controller]) && in_array($action, $selfServiceActions[$controller])) {
            return true;
        }

        // Get original user data
        $user = $identity->getOriginalData();

        // 1. Fetch user's roles from DB
        $userRolesTable = TableRegistry::getTableLocator()->get('UserRoles');
        $userRoles = $userRolesTable->find()
            ->where(['user_id' => $user->id])
            ->select(['role_id'])
            ->all()
            ->extract('role_id')
            ->toArray();

        // If the user has the Administrator role (ID = 1), grant full access
        if (in_array(1, $userRoles)) {
            return true;
        }

        // 2. Fetch user's groups from DB
        $groupUsersTable = TableRegistry::getTableLocator()->get('GroupUsers');
        $userGroups = $groupUsersTable->find()
            ->where(['user_id' => $user->id])
            ->select(['group_id'])
            ->all()
            ->extract('group_id')
            ->toArray();

        // 3. Query permissions table
        // We match controller & action, role_id or group_id
        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        
        $query = $permissionsTable->find()
            ->where([
                'allowed' => true,
                'OR' => [
                    ['role_id IN' => $userRoles ?: [0]],
                    ['group_id IN' => $userGroups ?: [0]],
                ]
            ]);

        // Filter by controller, action. If we want general permissions (e.g. all actions of a controller), we could support wildcards, but let's check direct match
        // Or check prefix/plugin if specified. Let's make it match controller and action:
        $query->where([
            'LOWER(controller)' => $controller,
            'LOWER(action)' => $action
        ]);

        $permission = $query->first();

        return $permission !== null;
    }
}
