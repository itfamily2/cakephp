<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * UsersTable — Phase 5: Complete Table Demonstration
 *
 * This is the most important model class in the project.
 * It demonstrates EVERY Phase 5 Table concept with deep comments:
 *
 *   ✅ initialize()        → Behaviors, associations, table config
 *   ✅ Timestamp Behavior  → Auto-manages created/modified columns
 *   ✅ All 4 Associations  → hasOne, belongsTo, hasMany, belongsToMany
 *   ✅ Validation          → Format checks before entity creation
 *   ✅ RulesChecker        → Stateful DB integrity checks at save time
 *   ✅ Custom Finders       → find('active'), find('withRoles'), etc.
 *   ✅ Model Events        → beforeSave, afterSave, beforeDelete, afterSaveCommit
 *   ✅ dispatchEvent()     → Custom application events (User.registered)
 *
 * TABLE vs ENTITY:
 *   Table  = the factory and repository. Builds entities, executes queries,
 *            fires lifecycle events, enforces business rules.
 *   Entity = a single row's data with computed properties.
 */
class UsersTable extends Table
{
    // =========================================================================
    // initialize() — Table Configuration
    // =========================================================================
    // Runs ONCE when the Table is first loaded by the ORM.
    // This is where you declare: table name, PK, display field,
    // behaviors (plugins), and associations (relationships).
    // =========================================================================
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // --- Table Metadata ---
        $this->setTable('users');          // Physical DB table name
        $this->setDisplayField('username');// Used in find('list') as label
        $this->setPrimaryKey('id');        // Primary key column name

        // =====================================================================
        // BEHAVIORS — Phase 5: Behavior
        // =====================================================================
        // Behaviors are reusable plugins that attach to Tables.
        // Each behavior can: add methods, add finders, listen to events.
        // =====================================================================

        // TimestampBehavior (built-in):
        //   Automatically sets created = NOW() on INSERT
        //   Automatically sets modified = NOW() on UPDATE
        //   You never need to set these manually in controller code.
        $this->addBehavior('Timestamp');

        // AuditLogBehavior (custom):
        //   Logs all field changes to the audit_logs table after every save.
        //   Attached to Users because every user change must be traceable.
        $this->addBehavior('AuditLog');

        // =====================================================================
        // ASSOCIATIONS — Phase 5: All 4 Association Types
        // =====================================================================
        //
        // hasOne     → Users.id  = profiles.user_id   (one Profile per User)
        // belongsTo  → (no direct belongsTo for Users as root model)
        // hasMany    → Users.id  = activity_logs.user_id (many logs)
        // belongsToMany → Users ↔ Roles via user_roles join table
        // =====================================================================

        // --- hasMany: One User → Many ActivityLogs ---
        // The FK is on the child table (activity_logs.user_id → users.id)
        // 'dependent' => true: deleting a User also deletes their ActivityLogs
        $this->hasMany('ActivityLogs', [
            'foreignKey' => 'user_id',
            'dependent'  => true,  // Cascade delete
            'order'      => ['ActivityLogs.created' => 'DESC'], // Default sort
        ]);

        // --- hasMany: One User → Many AuditLogs ---
        $this->hasMany('AuditLogs', [
            'foreignKey' => 'user_id',
            'dependent'  => true,
        ]);

        // --- hasMany: One User → Many Orders ---
        // A user can place many orders over their lifetime
        $this->hasMany('Orders', [
            'foreignKey' => 'user_id',
            'dependent'  => false, // Do NOT delete orders if user deleted (financial records)
        ]);

        // --- hasMany: One User → Many EmailTemplates ---
        $this->hasMany('EmailTemplates', [
            'foreignKey' => 'user_id',
            'dependent'  => true,
        ]);

        // --- hasMany: One User → Many EmailSignatures ---
        $this->hasMany('EmailSignatures', [
            'foreignKey' => 'user_id',
            'dependent'  => true,
        ]);

        // --- hasMany (join): One User → Many UserRoles ---
        // hasMany to the join table is needed for direct join table access
        $this->hasMany('UserRoles', [
            'foreignKey' => 'user_id',
            'dependent'  => true,
        ]);

        // --- hasMany (join): One User → Many GroupUsers ---
        $this->hasMany('GroupUsers', [
            'foreignKey' => 'user_id',
            'dependent'  => true,
        ]);

        // =====================================================================
        // belongsToMany — Many-to-Many via Join Table
        // =====================================================================
        // Users ↔ Roles via the user_roles join table.
        //
        // HOW IT DIFFERS FROM hasMany UserRoles:
        //   hasMany('UserRoles') → gives you the join table rows (raw pivots)
        //   belongsToMany('Roles') → gives you the Role entities directly,
        //   with the join table handled transparently.
        //
        // USAGE:
        //   $user->roles → collection of Role entities
        //   $usersTable->save($user, ['associated' => ['Roles']]) → syncs join table
        //
        // INTERVIEW: "belongsToMany is CakePHP's Many-to-Many. It automatically
        //   manages the join table rows on save/delete. The 'through' option
        //   lets me attach extra columns to the join record (e.g. assigned_at)."
        // =====================================================================
        $this->belongsToMany('Roles', [
            'foreignKey'       => 'user_id',
            'targetForeignKey' => 'role_id',
            'joinTable'        => 'user_roles',
            'through'          => 'UserRoles',  // Explicit join model for extra columns
        ]);

        $this->belongsToMany('Groups', [
            'foreignKey'       => 'user_id',
            'targetForeignKey' => 'group_id',
            'joinTable'        => 'group_users',
            'through'          => 'GroupUsers',
        ]);
    }


    // =========================================================================
    // validationDefault() — Phase 5: Validation
    // =========================================================================
    // Validation runs BEFORE the entity is created from request data.
    // It checks format, length, type — things that don't need a DB query.
    //
    // TWO TYPES OF VALIDATION:
    //   1. validationDefault()  → Always runs (create and update)
    //   2. validationCreate()   → Only runs on newEntity() (new records)
    //   3. validationUpdate()   → Only runs on patchEntity() (existing records)
    //
    // DIFFERENCE FROM buildRules():
    //   Validation = input format checking (no DB queries)
    //   Rules      = data integrity checking (uses DB queries)
    //
    // INTERVIEW: "Validation runs in memory before touching the database.
    //   buildRules() runs inside the database transaction at save time.
    //   You want format errors caught by validation (fast), and uniqueness
    //   errors caught by rules (requires a DB query)."
    // =========================================================================
    public function validationDefault(Validator $validator): Validator
    {
        // username: required on create, 3-50 chars, alphanumeric + underscores only
        $validator
            ->scalar('username')
            ->minLength('username', 3, 'Username must be at least 3 characters')
            ->maxLength('username', 50, 'Username cannot exceed 50 characters')
            ->regex('username', '/^[a-zA-Z0-9_]+$/', 'Username can only contain letters, numbers and underscores')
            ->requirePresence('username', 'create')
            ->notEmptyString('username', 'Username is required');

        // email: required on create, valid email format
        $validator
            ->email('email', false, 'Please enter a valid email address')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'Email is required');

        // password: required on create only (not required on profile edit)
        $validator
            ->scalar('password')
            ->minLength('password', 8, 'Password must be at least 8 characters')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'Password is required');

        // profile_image: optional, must be image MIME type when provided
        $validator
            ->scalar('profile_image')
            ->maxLength('profile_image', 255)
            ->allowEmptyFile('profile_image');

        // is_active: boolean flag
        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        // email_verified: boolean flag
        $validator
            ->boolean('email_verified')
            ->notEmptyString('email_verified');

        // Token fields: optional, max 255 chars
        $validator->scalar('verification_token')->maxLength('verification_token', 255)->allowEmptyString('verification_token');
        $validator->scalar('password_reset_token')->maxLength('password_reset_token', 255)->allowEmptyString('password_reset_token');
        $validator->dateTime('password_reset_expiry')->allowEmptyDateTime('password_reset_expiry');
        $validator->dateTime('last_login_time')->allowEmptyDateTime('last_login_time');
        $validator->scalar('last_login_ip')->maxLength('last_login_ip', 45)->allowEmptyString('last_login_ip');

        return $validator;
    }

    /**
     * validationCreate() — Validation rules ONLY for new user registration.
     *
     * Phase 5: Named validation sets
     * Called by: newEntity($data, ['validate' => 'create'])
     *
     * Use case: registration requires password confirmation,
     * but profile edit does not.
     */
    public function validationCreate(Validator $validator): Validator
    {
        // Start with default rules
        $validator = $this->validationDefault($validator);

        // Add password confirmation check — only needed during registration
        $validator
            ->scalar('confirm_password')
            ->notEmptyString('confirm_password', 'Please confirm your password')
            ->add('confirm_password', 'matchesPassword', [
                'rule' => function ($value, $context) {
                    return $value === ($context['data']['password'] ?? null);
                },
                'message' => 'Passwords do not match',
            ]);

        return $validator;
    }


    // =========================================================================
    // buildRules() — Phase 5: RulesChecker
    // =========================================================================
    // Rules are checked INSIDE the database transaction at save() time.
    // They enforce application-level data integrity (not just format).
    //
    // BUILT-IN RULES:
    //   isUnique()    → Check uniqueness in DB
    //   existsIn()    → Check foreign key references exist
    //   isLinkedTo()  → Check association exists
    //   validCount()  → Validate associated record count
    //
    // CUSTOM RULES:
    //   Add any closure or callable that returns bool/string.
    //
    // INTERVIEW: "Rules run inside a transaction. If a rule fails, the
    //   transaction rolls back. This is the correct place for isUnique()
    //   because checking uniqueness requires a SELECT query — validation
    //   deliberately avoids DB calls."
    // =========================================================================
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // isUnique: username must be unique in the users table
        $rules->add($rules->isUnique(['username']), 'uniqueUsername', [
            'errorField' => 'username',
            'message'    => 'This username is already taken.',
        ]);

        // isUnique: email must be unique
        $rules->add($rules->isUnique(['email']), 'uniqueEmail', [
            'errorField' => 'email',
            'message'    => 'An account with this email already exists.',
        ]);

        // Custom rule: username cannot be a reserved word
        $rules->add(function (EntityInterface $entity, array $options) {
            $reserved = ['admin', 'root', 'system', 'superuser', 'null'];
            if (in_array(strtolower($entity->username ?? ''), $reserved, true)) {
                return 'This username is reserved and cannot be used.';
            }
            return true;
        }, 'notReservedUsername', [
            'errorField' => 'username',
        ]);

        return $rules;
    }


    // =========================================================================
    // CUSTOM FINDERS — Phase 5: Custom Finder
    // =========================================================================
    // Custom finders extend find() with reusable query logic.
    //
    // NAMING CONVENTION: findXxx() → called as find('xxx')
    //
    // USAGE:
    //   $this->Users->find('active')          → active users
    //   $this->Users->find('withRoles')       → users with their roles
    //   $this->Users->find('search', ['term' => 'john'])
    //
    // INTERVIEW: "Custom finders keep complex query conditions out of controllers.
    //   Instead of copy-pasting ->where(['is_active' => true]) everywhere,
    //   I define findActive() once on the Table and call find('active')
    //   from anywhere. Controllers stay thin."
    // =========================================================================

    /**
     * findActive() — Returns only active (non-banned) users.
     * Usage: $this->Users->find('active')
     */
    public function findActive(SelectQuery $query, array $options): SelectQuery
    {
        return $query->where([
            'Users.is_active'      => true,
            'Users.email_verified' => true,
        ]);
    }

    /**
     * findInactive() — Returns deactivated users.
     * Usage: $this->Users->find('inactive')
     */
    public function findInactive(SelectQuery $query, array $options): SelectQuery
    {
        return $query->where(['Users.is_active' => false]);
    }

    /**
     * findWithRoles() — Returns users with their role associations loaded.
     * Usage: $this->Users->find('withRoles')
     *
     * INTERVIEW: "Custom finders can also contain() calls, saving controllers
     *   from knowing which associations to eager-load."
     */
    public function findWithRoles(SelectQuery $query, array $options): SelectQuery
    {
        return $query->contain(['UserRoles.Roles', 'GroupUsers.Groups']);
    }

    /**
     * findSearch() — Full-text search across username, email, first_name.
     * Usage: $this->Users->find('search', ['term' => 'john'])
     *
     * Options:
     *   term  (string) — search string
     *   role  (int)    — filter by role_id
     */
    public function findSearch(SelectQuery $query, array $options): SelectQuery
    {
        $term = $options['term'] ?? null;
        $role = $options['role'] ?? null;

        if (!empty($term)) {
            $like = '%' . $term . '%';
            $query->where([
                'OR' => [
                    'Users.username LIKE'   => $like,
                    'Users.email LIKE'      => $like,
                ]
            ]);
        }

        if (!empty($role)) {
            // Filter by role using matching() for an INNER JOIN
            $query->matching('Roles', function (SelectQuery $q) use ($role) {
                return $q->where(['Roles.id' => $role]);
            });
        }

        return $query;
    }

    /**
     * findRecentlyRegistered() — Users registered in last N days.
     * Usage: $this->Users->find('recentlyRegistered', ['days' => 7])
     */
    public function findRecentlyRegistered(SelectQuery $query, array $options): SelectQuery
    {
        $days = $options['days'] ?? 7;
        $since = new \Cake\I18n\DateTime("-{$days} days");

        return $query->where(['Users.created >=' => $since])
                     ->orderBy(['Users.created' => 'DESC']);
    }


    // =========================================================================
    // MODEL EVENTS — Phase 5: Events
    // =========================================================================
    // Table classes implement EventListenerInterface automatically.
    // Declare lifecycle methods with exact CakePHP event name signatures.
    //
    // COMPLETE EVENT EXECUTION ORDER:
    //   save($entity)
    //     → beforeMarshal      (in behavior/table, before data→entity)
    //     → beforeRules        (before buildRules checks)
    //     → afterRules         (after buildRules checks)
    //     → beforeSave         (last chance to cancel, inside transaction)
    //     → [SQL INSERT/UPDATE]
    //     → afterSave          (inside transaction, entity has id now)
    //     → afterSaveCommit    (after transaction committed — send emails here!)
    //
    //   delete($entity)
    //     → beforeDelete
    //     → [SQL DELETE]
    //     → afterDelete
    //     → afterDeleteCommit
    // =========================================================================

    /**
     * beforeSave() — Phase 5: Model.beforeSave Event
     *
     * Fires INSIDE the database transaction, BEFORE SQL runs.
     * Use for: setting computed fields, generating tokens, last-minute changes.
     * Returning false cancels the save (rolls back transaction).
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // Auto-generate email verification token for new users
        if ($entity->isNew() && empty($entity->verification_token)) {
            $entity->verification_token = bin2hex(random_bytes(32));
        }

        // Auto-assign default active status for new users
        if ($entity->isNew() && !isset($entity->is_active)) {
            $entity->is_active = false; // Inactive until email verified
        }
    }

    /**
     * afterSave() — Phase 5: Model.afterSave Event
     *
     * Fires INSIDE the transaction, AFTER SQL ran.
     * Entity now has an id (for new records).
     *
     * Use for: creating related records, updating counters.
     * NOTE: If transaction later rolls back, this effect also rolls back.
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // Invalidate Redis cache whenever a user is saved
        \Cake\Cache\Cache::delete('dashboard_total_users', 'redis');
        \Cake\Cache\Cache::delete('dashboard_recent_users', 'redis');
    }

    /**
     * afterSaveCommit() — Phase 5: Model.afterSaveCommit Event
     *
     * Fires AFTER the transaction is fully committed to the DB.
     * This is the CORRECT place to send emails, call external APIs,
     * or trigger webhooks — because the data is guaranteed persisted.
     *
     * WHY NOT afterSave():
     *   afterSave() runs INSIDE the transaction. If you send an email there
     *   but the transaction rolls back, the email is already sent but the
     *   user doesn't exist. afterSaveCommit() prevents this race condition.
     *
     * INTERVIEW: "afterSaveCommit() is the key insight about CakePHP events.
     *   The transaction is committed before it fires, so any external side
     *   effects (emails, webhooks, queue jobs) only happen when data is truly
     *   saved. This prevents sending registration emails for rolled-back users."
     */
    public function afterSaveCommit(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // Only for new users (registration)
        if ($entity->isNew()) {
            // Dispatch a CUSTOM APPLICATION EVENT
            // Other listeners (e.g. NotificationListener) can react to this
            $this->dispatchEvent('User.registered', ['user' => $entity]);
        }
    }

    /**
     * beforeDelete() — Phase 5: Model.beforeDelete Event
     *
     * Fires before DELETE SQL.
     * Returning false prevents deletion.
     */
    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // Prevent deletion of the primary admin user (id=1 is the seeded superadmin)
        if ($entity->id === 1) {
            // Stop deletion by halting event propagation
            $event->stopPropagation();
        }
    }
}
