<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/** Phase 17 - CitiesTable */
class CitiesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('cities');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->belongsTo('States', ['foreignKey' => 'state_id']);
        $this->belongsTo('Countries', ['foreignKey' => 'country_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('name', 'City name is required.');
        $validator->notEmptyString('state_id', 'State is required.');
        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['state_id'], 'States'));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        return $rules;
    }
}
