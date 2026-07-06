<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ContactEnquiries Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $AssignedStaffs
 *
 * @method \App\Model\Entity\ContactEnquiry newEmptyEntity()
 * @method \App\Model\Entity\ContactEnquiry newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ContactEnquiry> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContactEnquiry get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ContactEnquiry findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ContactEnquiry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ContactEnquiry> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContactEnquiry|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ContactEnquiry saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ContactEnquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactEnquiry>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactEnquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactEnquiry> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactEnquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactEnquiry>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactEnquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactEnquiry> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContactEnquiriesTable extends Table
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

        $this->setTable('contact_enquiries');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AssignedStaffs', [
            'foreignKey' => 'assigned_staff_id',
            'className' => 'Users',
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject');

        $validator
            ->scalar('message')
            ->requirePresence('message', 'create')
            ->notEmptyString('message');

        $validator
            ->scalar('reply_message')
            ->allowEmptyString('reply_message');

        $validator
            ->scalar('reply_status')
            ->maxLength('reply_status', 20)
            ->notEmptyString('reply_status');

        $validator
            ->integer('assigned_staff_id')
            ->allowEmptyString('assigned_staff_id');

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
        $rules->add($rules->existsIn(['assigned_staff_id'], 'AssignedStaffs'), ['errorField' => 'assigned_staff_id']);

        return $rules;
    }
}
