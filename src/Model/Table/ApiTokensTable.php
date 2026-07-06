<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Query\SelectQuery;
use Cake\Validation\Validator;

/**
 * Phase 17 - ApiTokensTable
 * Personal access tokens for the API (alternative to stateless JWT).
 */
class ApiTokensTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('api_tokens');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('token', 'Token is required.');
        $validator->notEmptyString('user_id', 'User is required.');
        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(['token']), 'uniqueToken', ['message' => 'This token already exists.']);
        return $rules;
    }

    /** find('active') - Only non-revoked, non-expired tokens */
    public function findActive(SelectQuery $query, array $options): SelectQuery
    {
        return $query->where([
            'ApiTokens.is_revoked' => false,
            'OR' => [
                'ApiTokens.expires_at IS NULL',
                'ApiTokens.expires_at >' => new \DateTime()
            ]
        ]);
    }
}
