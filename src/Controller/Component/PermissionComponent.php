<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Http\Exception\ForbiddenException;

/**
 * Phase 11 - PermissionComponent
 * 
 * Handles custom permission checking logic. Can work alongside or on top
 * of the standard Authorization plugin to provide fine-grained checks.
 */
class PermissionComponent extends Component
{
    protected array $components = ['Authentication.Authentication'];

    /**
     * Check if current user has a specific role.
     * Note: This assumes roles are loaded into the identity or we query them.
     */
    public function hasRole(string $roleName): bool
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return false;
        }

        // Simplistic check for demo purposes. In reality, you'd check UserRoles.
        // If identity has 'user_roles' loaded:
        $user = $identity->getOriginalData();
        if (!empty($user->user_roles)) {
            foreach ($user->user_roles as $userRole) {
                if (isset($userRole->role) && strtolower($userRole->role->name) === strtolower($roleName)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Require a specific role to proceed.
     * 
     * @throws \Cake\Http\Exception\ForbiddenException
     */
    public function requireRole(string $roleName): void
    {
        if (!$this->hasRole($roleName)) {
            throw new ForbiddenException("Access denied. Requires role: {$roleName}");
        }
    }
    
    /**
     * Example method for fine-grained permission string checking.
     */
    public function can(string $action, string $resource): bool
    {
        // Implementation would query Permissions table based on user's roles
        // e.g. check if user's roles have permission 'edit_orders'
        \Cake\Log\Log::debug("Checking if user can {$action} {$resource}");
        return true; // Stub
    }
}
