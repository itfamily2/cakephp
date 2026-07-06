<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\DateTime;

/**
 * User Entity — Phase 5: Complete Entity Demonstration
 *
 * This entity demonstrates every Phase 5 Entity concept:
 *   ✅ _accessible    → Mass assignment protection (security)
 *   ✅ _hidden        → Serialization filtering
 *   ✅ Mutator        → _setPassword() auto-hashes on assignment
 *   ✅ Accessor       → _getFullName() computed read property
 *   ✅ Virtual Fields → Fields computed at runtime, not in DB
 *
 * ENTITY vs TABLE — KEY DISTINCTION:
 *   - Entity   = single ROW in memory (domain object, the "what")
 *   - Table    = the repository for many rows (queries, the "how to get")
 *
 * DATA MAPPER PATTERN:
 *   Entity does NOT know how to save itself (unlike Active Record).
 *   To save: $usersTable->save($user) — Table handles persistence.
 *   Entity only holds state and business computation logic.
 *
 * INTERVIEW TALKING POINT:
 *   "CakePHP uses the Data Mapper pattern. The Entity is a pure data holder
 *    with computed properties (virtual fields) and lifecycle hooks (mutators).
 *    It has zero database awareness — that's deliberately the Table class's job."
 */
class User extends Entity
{
    // =========================================================================
    // _accessible — Mass Assignment Guard (Security)
    // =========================================================================
    // Controls which fields can be set via newEntity() and patchEntity().
    //
    // WHY THIS MATTERS:
    //   Without _accessible, a malicious POST body could set any column:
    //   POST /users/edit with {'role_id': 1, 'is_admin': true}
    //
    //   With _accessible, only listed fields pass through patchEntity().
    //   Setting 'is_admin' would silently be ignored.
    //
    // RULE: Never use '*' => true in production. Always whitelist explicitly.
    //
    // INTERVIEW: "Mass assignment protection prevents attackers from submitting
    //   extra form fields to set columns they shouldn't control. It's the PHP
    //   equivalent of Laravel's $fillable array."
    // =========================================================================
    protected array $_accessible = [
        // Authentication fields
        'username'             => true,
        'email'                => true,
        'password'             => true,

        // Profile fields
        'profile_image'        => true,
        'first_name'           => true,
        'last_name'            => true,

        // Account state — allowed for admin updates
        'is_active'            => true,
        'email_verified'       => true,

        // Token fields — only set programmatically, but need to be accessible
        'verification_token'   => true,
        'password_reset_token' => true,
        'password_reset_expiry'=> true,
        'remember_token'       => true,

        // Tracking fields — set by login logic, not forms
        'last_login_time'      => true,
        'last_login_ip'        => true,

        // Timestamps — managed by TimestampBehavior
        'created'              => true,
        'modified'             => true,

        // Associations — allow nested creates
        'activity_logs'        => true,
        'audit_logs'           => true,
        'user_roles'           => true,
        'group_users'          => true,
    ];


    // =========================================================================
    // _hidden — Serialization Filter
    // =========================================================================
    // Fields listed here are EXCLUDED when the entity is converted to:
    //   - JSON: $user->toJson() / json_encode($user)
    //   - Array: $user->toArray()
    //
    // This prevents sensitive fields from leaking in API responses.
    // The field is still readable on the entity object itself in PHP code.
    //
    // INTERVIEW: "I hide password, tokens, and IPs from JSON serialization
    //   so they never accidentally appear in API responses, even if a developer
    //   forgets to filter them in the controller."
    // =========================================================================
    protected array $_hidden = [
        'password',
        'remember_token',
        'verification_token',
        'password_reset_token',
        'last_login_ip',        // Don't expose IP in API responses
    ];


    // =========================================================================
    // MUTATOR — _setPassword() (Setter Method)
    // =========================================================================
    // CakePHP automatically calls _setXxx() whenever you assign to $entity->xxx
    //
    // HOW IT WORKS:
    //   $user->password = 'secret123'
    //   → PHP magic setter triggers _setPassword('secret123')
    //   → BCRYPT hash is computed and stored instead
    //
    // WHY: The controller never needs to remember to hash passwords.
    //   Hashing happens at the Entity layer automatically, every time.
    //
    // INTERVIEW: "Mutators in CakePHP entities use the naming convention
    //   _setFieldName(). They intercept property assignment, allowing me to
    //   transform data (hash password, format phone number) before it's stored."
    // =========================================================================
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            // DefaultPasswordHasher uses BCRYPT by default (cost factor 10)
            // In PHP 8+, can be configured to use Argon2id
            return (new DefaultPasswordHasher())->hash($password);
        }
        // Return as-is if empty (e.g. edit form with empty password field)
        return $password;
    }

    /**
     * _setUsername() — Mutator: auto-lowercase and trim usernames on save.
     */
    protected function _setUsername(string $username): string
    {
        // Normalize username: trim whitespace, lowercase
        return strtolower(trim($username));
    }

    /**
     * _setEmail() — Mutator: normalize email addresses.
     */
    protected function _setEmail(string $email): string
    {
        // Always store email as lowercase to prevent duplicate registrations
        return strtolower(trim($email));
    }


    // =========================================================================
    // ACCESSOR — _getXxx() (Getter / Computed Property)
    // =========================================================================
    // CakePHP calls _getXxx() whenever you READ $entity->xxx.
    // Useful for returning transformed or combined data.
    //
    // INTERVIEW: "Accessors let me expose computed properties on the entity
    //   without storing them in the database. $user->full_name reads two
    //   columns and returns a formatted string — zero extra DB query needed."
    // =========================================================================

    /**
     * _getFullName() — Accessor: computed from first_name + last_name.
     * Access: $user->full_name
     */
    protected function _getFullName(): string
    {
        $first = $this->_fields['first_name'] ?? '';
        $last  = $this->_fields['last_name'] ?? '';

        if ($first && $last) {
            return trim("{$first} {$last}");
        }

        // Fall back to username if no name is set
        return $this->_fields['username'] ?? '';
    }

    /**
     * _getDisplayName() — Accessor: shows full name or username.
     * Access: $user->display_name
     */
    protected function _getDisplayName(): string
    {
        return $this->full_name ?: ($this->_fields['username'] ?? 'Unknown User');
    }

    /**
     * _getAvatarUrl() — Accessor: returns profile image URL or Gravatar fallback.
     * Access: $user->avatar_url
     */
    protected function _getAvatarUrl(): string
    {
        // If user has uploaded a profile image, return it
        if (!empty($this->_fields['profile_image'])) {
            return '/uploads/avatars/' . $this->_fields['profile_image'];
        }

        // Fall back to Gravatar (MD5 of email for privacy)
        $email = $this->_fields['email'] ?? '';
        $hash  = md5(strtolower(trim($email)));

        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=80";
    }

    /**
     * _getStatusLabel() — Accessor: human-readable status string.
     * Access: $user->status_label
     */
    protected function _getStatusLabel(): string
    {
        return ($this->_fields['is_active'] ?? false)
            ? 'Active'
            : 'Inactive';
    }

    /**
     * _getIsPasswordExpired() — Accessor: checks if reset token has expired.
     * Access: $user->is_password_expired
     *
     * VIRTUAL FIELD NOTE:
     *   This is computed at runtime from the password_reset_expiry column.
     *   It never exists as a database column — it's always derived.
     */
    protected function _getIsPasswordExpired(): bool
    {
        $expiry = $this->_fields['password_reset_expiry'] ?? null;

        if (!$expiry) {
            return false;
        }

        return $expiry < new DateTime();
    }

    /**
     * _getMemberSince() — Accessor: human-friendly registration time.
     * Access: $user->member_since
     */
    protected function _getMemberSince(): string
    {
        $created = $this->_fields['created'] ?? null;

        if (!$created) {
            return 'Unknown';
        }

        return $created->format('F Y'); // e.g. "January 2026"
    }
}
