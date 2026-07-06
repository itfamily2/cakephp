<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Datasource\EntityInterface;
use Cake\Log\Log;
use App\Event\DomainEvents;
use ArrayObject;

/**
 * Orders Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\OrderItemsTable&\Cake\ORM\Association\HasMany $OrderItems
 * @property \App\Model\Table\PaymentsTable&\Cake\ORM\Association\HasMany $Payments
 *
 * @method \App\Model\Entity\Order newEmptyEntity()
 * @method \App\Model\Entity\Order newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Order> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Order get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Order findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Order> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Order|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Order saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrdersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('orders');
        $this->setDisplayField('order_number');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('OrderItems', [
            'foreignKey' => 'order_id',
        ]);
        $this->hasMany('Payments', [
            'foreignKey' => 'order_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->scalar('order_number')
            ->maxLength('order_number', 50)
            ->requirePresence('order_number', 'create')
            ->notEmptyString('order_number');

        $validator
            ->decimal('total')
            ->requirePresence('total', 'create')
            ->notEmptyString('total');

        $validator
            ->scalar('status')
            ->maxLength('status', 30)
            ->notEmptyString('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    // =========================================================================
    // PHASE 10: ALL 7 MODEL EVENTS — Complete Lifecycle
    // =========================================================================
    //
    // EXECUTION ORDER for save($entity):
    //   1. beforeMarshal  → data array → entity conversion
    //   2. beforeRules    → before buildRules validation
    //   3. afterRules     → after buildRules validation
    //   4. beforeSave     → inside transaction, before SQL INSERT/UPDATE
    //   5. [SQL EXECUTES]
    //   6. afterSave      → inside transaction, entity has ID now
    //   7. afterSaveCommit → after transaction committed
    //
    // For find():
    //   1. beforeFind     → before SQL SELECT executes
    //
    // For delete():
    //   1. beforeDelete   → before SQL DELETE
    //   2. [SQL EXECUTES]
    //   3. afterDelete    → after SQL DELETE
    //   4. afterDeleteCommit → after transaction committed
    // =========================================================================

    /**
     * Model.beforeFind — Phase 10
     *
     * Fires before every SELECT query on this table.
     * Use for: adding global conditions, soft-delete filters, tenant scoping.
     *
     * INTERVIEW: "beforeFind() is my go-to for global query modifications.
     *   For multi-tenant apps, I add ->where(['tenant_id' => ...]) here
     *   so every query is automatically scoped."
     */
    public function beforeFind(EventInterface $event, SelectQuery $query, ArrayObject $options, bool $primary): void
    {
        Log::debug('[PHASE 10] Model.beforeFind — Orders query intercepted');

        // Example: Auto-exclude cancelled orders unless explicitly requested
        if (empty($options['includeCancelled'])) {
            // Commented out to not break existing queries:
            // $query->where(['Orders.status !=' => 'Cancelled']);
        }
    }

    /**
     * Model.beforeMarshal — Phase 10
     *
     * Fires before request data is converted into an Entity.
     * The $data parameter is the raw request array — you can modify it.
     *
     * Use for: trimming whitespace, normalizing formats, setting defaults.
     *
     * INTERVIEW: "beforeMarshal() modifies raw input BEFORE the entity is built.
     *   I use it for data cleanup like trimming strings or converting date formats.
     *   This is different from beforeSave() which works on the entity."
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        Log::debug('[PHASE 10] Model.beforeMarshal — Orders data being marshalled');

        // Auto-generate order number if not provided
        if (empty($data['order_number'])) {
            $data['order_number'] = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        }

        // Normalize status to title case
        if (!empty($data['status'])) {
            $data['status'] = ucfirst(strtolower($data['status']));
        }
    }

    /**
     * Model.beforeRules — Phase 10
     *
     * Fires BEFORE buildRules() validation checks run.
     * The entity is already created but not yet validated against rules.
     *
     * Use for: last-minute adjustments before rule checking.
     */
    public function beforeRules(EventInterface $event, EntityInterface $entity, ArrayObject $options, string $operation): void
    {
        Log::debug(sprintf(
            '[PHASE 10] Model.beforeRules — Orders entity (operation: %s)',
            $operation // 'create' or 'update'
        ));
    }

    /**
     * Model.afterRules — Phase 10
     *
     * Fires AFTER buildRules() checks pass.
     * At this point the entity has passed all validation and rules.
     *
     * Use for: post-validation adjustments, conditional field setting.
     */
    public function afterRules(EventInterface $event, EntityInterface $entity, ArrayObject $options, bool $result, string $operation): void
    {
        Log::debug(sprintf(
            '[PHASE 10] Model.afterRules — Orders rules %s (operation: %s)',
            $result ? 'PASSED' : 'FAILED',
            $operation
        ));
    }

    /**
     * Model.beforeSave — Phase 10
     *
     * Fires INSIDE the transaction, BEFORE the SQL INSERT/UPDATE.
     * The entity is fully validated. Returning false cancels the save.
     *
     * Use for: computing derived fields, setting timestamps,
     * generating slugs, last-chance modifications.
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        Log::debug(sprintf(
            '[PHASE 10] Model.beforeSave — Order %s (isNew: %s)',
            $entity->order_number ?? 'N/A',
            $entity->isNew() ? 'yes' : 'no'
        ));

        // Set default status for new orders
        if ($entity->isNew() && empty($entity->status)) {
            $entity->status = 'Pending';
        }
    }

    /**
     * Model.afterSave — Phase 10
     *
     * Fires INSIDE the transaction, AFTER the SQL ran.
     * Entity now has an ID (for new records).
     *
     * Use for: creating related records, updating counters.
     * WARNING: If the transaction later rolls back, these effects also roll back.
     *
     * INTERVIEW: "afterSave() is inside the transaction. I dispatch my custom
     *   Order.created event here. The NotificationListener will auto-create
     *   an invoice and log the event."
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        Log::info(sprintf(
            '[PHASE 10] Model.afterSave — Order %s saved (ID: %d)',
            $entity->order_number ?? 'N/A',
            $entity->id ?? 0
        ));

        // Dispatch custom domain event for NEW orders only
        if ($entity->isNew()) {
            EventManager::instance()->dispatch(new Event(
                DomainEvents::ORDER_CREATED,
                $this,
                ['order' => $entity]
            ));
        }

        // Invalidate related caches
        \Cake\Cache\Cache::delete('dashboard_total_orders', 'redis');
    }

    /**
     * Model.afterDelete — Phase 10
     *
     * Fires AFTER the SQL DELETE has executed (inside transaction).
     *
     * Use for: cleanup, audit logging, removing related resources.
     */
    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        Log::info(sprintf(
            '[PHASE 10] Model.afterDelete — Order %s deleted (ID: %d)',
            $entity->order_number ?? 'N/A',
            $entity->id ?? 0
        ));

        // Invalidate caches
        \Cake\Cache\Cache::delete('dashboard_total_orders', 'redis');
    }
}
