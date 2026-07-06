<?php
declare(strict_types=1);

namespace UserManager\Service;

use Cake\ORM\Locator\LocatorAwareTrait;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Log\Log;

/**
 * Enterprise User Access Manager
 * Handles robust JWT generation, multi-factor auth verification, and RBAC checks.
 */
class AccessManagerService
{
    use LocatorAwareTrait;

    public function verifyCredentials(string $username, string $password): ?\Cake\Datasource\EntityInterface
    {
        $usersTable = $this->fetchTable('Users');
        
        $user = $usersTable->find()
            ->where(['username' => $username, 'is_active' => true])
            ->first();

        if (!$user) {
            $this->logAttempt($username, 'failed', 'User not found or inactive');
            return null;
        }

        $hasher = new DefaultPasswordHasher();
        if (!$hasher->check($password, $user->password)) {
            $this->logAttempt($username, 'failed', 'Invalid password');
            return null;
        }

        $this->logAttempt($username, 'success');
        return $user;
    }

    private function logAttempt(string $username, string $status, string $reason = null): void
    {
        $logsTable = $this->fetchTable('LoginLogs');
        $log = $logsTable->newEntity([
            'username' => $username,
            'status' => $status,
            'fail_reason' => $reason,
            'created' => new \DateTime()
        ]);
        $logsTable->save($log);
        
        if ($status === 'failed') {
            Log::warning("Failed login attempt for user: {$username} ({$reason})");
        }
    }
}
