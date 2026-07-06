<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmailSignatures Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ScheduledEmailsTable&\Cake\ORM\Association\HasMany $ScheduledEmails
 * @property \App\Model\Table\SentEmailsTable&\Cake\ORM\Association\HasMany $SentEmails
 *
 * @method \App\Model\Entity\EmailSignature newEmptyEntity()
 * @method \App\Model\Entity\EmailSignature newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\EmailSignature> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmailSignature get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\EmailSignature findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\EmailSignature patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\EmailSignature> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmailSignature|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\EmailSignature saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\EmailSignature>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailSignature>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EmailSignature>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailSignature> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EmailSignature>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailSignature>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EmailSignature>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailSignature> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmailSignaturesTable extends Table
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

        $this->setTable('email_signatures');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ScheduledEmails', [
            'foreignKey' => 'email_signature_id',
        ]);
        $this->hasMany('SentEmails', [
            'foreignKey' => 'email_signature_id',
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
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

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
}
