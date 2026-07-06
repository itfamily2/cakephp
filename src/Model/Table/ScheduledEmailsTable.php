<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ScheduledEmails Model
 *
 * @property \App\Model\Table\EmailTemplatesTable&\Cake\ORM\Association\BelongsTo $EmailTemplates
 * @property \App\Model\Table\EmailSignaturesTable&\Cake\ORM\Association\BelongsTo $EmailSignatures
 *
 * @method \App\Model\Entity\ScheduledEmail newEmptyEntity()
 * @method \App\Model\Entity\ScheduledEmail newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ScheduledEmail> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ScheduledEmail findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ScheduledEmail> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ScheduledEmail saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduledEmail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduledEmail>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduledEmail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduledEmail> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduledEmail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduledEmail>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduledEmail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduledEmail> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScheduledEmailsTable extends Table
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

        $this->setTable('scheduled_emails');
        $this->setDisplayField('recipient_email');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('EmailTemplates', [
            'foreignKey' => 'email_template_id',
        ]);
        $this->belongsTo('EmailSignatures', [
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
            ->integer('email_template_id')
            ->allowEmptyString('email_template_id');

        $validator
            ->integer('email_signature_id')
            ->allowEmptyString('email_signature_id');

        $validator
            ->scalar('recipient_email')
            ->maxLength('recipient_email', 255)
            ->requirePresence('recipient_email', 'create')
            ->notEmptyString('recipient_email');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject');

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->notEmptyString('status');

        $validator
            ->dateTime('scheduled_time')
            ->allowEmptyDateTime('scheduled_time');

        $validator
            ->dateTime('sent_time')
            ->allowEmptyDateTime('sent_time');

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
        $rules->add($rules->existsIn(['email_template_id'], 'EmailTemplates'), ['errorField' => 'email_template_id']);
        $rules->add($rules->existsIn(['email_signature_id'], 'EmailSignatures'), ['errorField' => 'email_signature_id']);

        return $rules;
    }
}
