<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/** Phase 17 - LanguagesTable */
class LanguagesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('languages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('name', 'Language name is required.');
        $validator->notEmptyString('code', 'Language code is required.')->maxLength('code', 10);
        $validator->inList('direction', ['ltr', 'rtl'], 'Direction must be ltr or rtl.');
        return $validator;
    }
}
