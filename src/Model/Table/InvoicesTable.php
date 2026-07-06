<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Invoices Model
 *
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\BelongsTo $Orders
 *
 * @method \App\Model\Entity\Invoice newEmptyEntity()
 * @method \App\Model\Entity\Invoice newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Invoice> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Invoice get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Invoice findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Invoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Invoice> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Invoice saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Invoice>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Invoice>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Invoice>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Invoice> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Invoice>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Invoice>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Invoice>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Invoice> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InvoicesTable extends Table
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

        $this->setTable('invoices');
        $this->setDisplayField('invoice_number');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orders', [
            'foreignKey' => 'order_id',
            'joinType' => 'INNER',
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
            ->integer('order_id')
            ->notEmptyString('order_id');

        $validator
            ->scalar('invoice_number')
            ->maxLength('invoice_number', 50)
            ->requirePresence('invoice_number', 'create')
            ->notEmptyString('invoice_number')
            ->add('invoice_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->decimal('tax')
            ->notEmptyString('tax');

        $validator
            ->decimal('discount')
            ->notEmptyString('discount');

        $validator
            ->scalar('status')
            ->maxLength('status', 30)
            ->notEmptyString('status');

        $validator
            ->date('due_date')
            ->allowEmptyDate('due_date');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

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
        $rules->add($rules->isUnique(['invoice_number']), ['errorField' => 'invoice_number']);
        $rules->add($rules->existsIn(['order_id'], 'Orders'), ['errorField' => 'order_id']);

        return $rules;
    }
}
