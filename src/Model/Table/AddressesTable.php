<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Phase 17 - AddressesTable
 * Manages billing/shipping addresses linked to users.
 */
class AddressesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('addresses');
        $this->setDisplayField('full_name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        
        // Association
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('full_name', 'Full name is required.');
        $validator->notEmptyString('address_line1', 'Street address is required.');
        $validator->notEmptyString('city', 'City is required.');
        $validator->inList('address_type', ['billing', 'shipping', 'office'], 'Invalid address type.');
        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}
