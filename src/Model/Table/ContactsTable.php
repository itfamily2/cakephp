<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Phase 17 - ContactsTable
 * Stores contact/enquiry form submissions.
 */
class ContactsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('contacts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('name', 'Name is required.');
        $validator->email('email', false, 'Please provide a valid email.')->allowEmptyString('email');
        $validator->notEmptyString('message', 'Message is required.')->minLength('message', 10, 'Message must be at least 10 characters.');
        $validator->inList('status', ['new', 'read', 'replied', 'closed'], 'Invalid status.');
        return $validator;
    }
}
