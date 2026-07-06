<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\UnauthorizedException;

/**
 * Phase 11 - AuthComponent
 * 
 * A custom component to wrap authentication and authorization logic,
 * making it easier to check roles and identities across controllers.
 */
class AuthComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    /**
     * Required components.
     * We depend on the standard Authentication component to get the identity.
     */
    protected array $components = ['Authentication.Authentication'];

    /**
     * Check if the current user is logged in.
     */
    public function isLoggedIn(): bool
    {
        return $this->Authentication->getIdentity() !== null;
    }

    /**
     * Get the current user data.
     */
    public function user()
    {
        $identity = $this->Authentication->getIdentity();
        return $identity ? $identity->getOriginalData() : null;
    }

    /**
     * Get the current user's ID.
     */
    public function getUserId(): ?int
    {
        $user = $this->user();
        return $user ? $user->id : null;
    }

    /**
     * Require a user to be logged in, otherwise throw an exception.
     * 
     * @throws \Cake\Http\Exception\UnauthorizedException
     */
    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            throw new UnauthorizedException('You must be logged in to access this action.');
        }
    }
}
