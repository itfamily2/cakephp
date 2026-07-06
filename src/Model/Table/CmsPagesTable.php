<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CmsPages Model
 *
 * @method \App\Model\Entity\CmsPage newEmptyEntity()
 * @method \App\Model\Entity\CmsPage newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\CmsPage> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CmsPage get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\CmsPage findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\CmsPage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\CmsPage> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CmsPage|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\CmsPage saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\CmsPage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CmsPage>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CmsPage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CmsPage> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CmsPage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CmsPage>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CmsPage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CmsPage> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CmsPagesTable extends Table
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

        $this->setTable('cms_pages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 255)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        $validator
            ->scalar('meta_title')
            ->maxLength('meta_title', 255)
            ->allowEmptyString('meta_title');

        $validator
            ->scalar('meta_description')
            ->allowEmptyString('meta_description');

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
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);

        return $rules;
    }
}
