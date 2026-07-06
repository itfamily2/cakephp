<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Phase 17 - CountriesTable
 */
class CountriesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('countries');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->hasMany('States', ['foreignKey' => 'country_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('name', 'Country name is required.');
        $validator->notEmptyString('code', 'Country code is required.')->maxLength('code', 5);
        return $validator;
    }
}
