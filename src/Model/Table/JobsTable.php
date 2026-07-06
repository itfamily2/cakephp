<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/** Phase 17 - JobsTable - Background job queue table */
class JobsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('jobs');
        $this->setDisplayField('queue');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp', ['events' => ['Model.beforeSave' => ['created' => 'new']]]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmptyString('payload', 'Job payload is required.');
        $validator->notEmptyString('queue', 'Queue name is required.');
        return $validator;
    }
}
