<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Phase 17 - AttachmentsTable
 * Polymorphic file attachment system.
 */
class AttachmentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('attachments');
        $this->setDisplayField('original_name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp', ['events' => ['Model.beforeSave' => ['created' => 'new']]]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('model', 'Parent model is required.');
        $validator->notEmptyString('file_name', 'File name is required.');
        $validator->notEmptyString('file_path', 'File path is required.');
        $validator->notEmptyString('mime_type', 'Mime type is required.');
        $validator->integer('file_size')->greaterThan('file_size', 0, 'File must not be empty.');
        return $validator;
    }
}
