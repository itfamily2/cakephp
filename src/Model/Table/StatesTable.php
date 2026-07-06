<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/** Phase 17 - StatesTable */
class StatesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('states');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Countries', ['foreignKey' => 'country_id']);
        $this->hasMany('Cities', ['foreignKey' => 'state_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('name', 'State name is required.');
        $validator->notEmptyString('country_id', 'Country is required.');
        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        return $rules;
    }
}
