<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/** Phase 17 - CurrenciesTable */
class CurrenciesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('currencies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('name', 'Currency name is required.');
        $validator->notEmptyString('code', 'Currency code is required.')->maxLength('code', 5);
        $validator->notEmptyString('symbol', 'Currency symbol is required.');
        $validator->decimal('exchange_rate')->greaterThan('exchange_rate', 0, 'Exchange rate must be positive.');
        return $validator;
    }
}
