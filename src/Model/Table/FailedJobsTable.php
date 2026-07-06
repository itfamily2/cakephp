<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

/** Phase 17 - FailedJobsTable - Stores jobs that failed execution */
class FailedJobsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('failed_jobs');
        $this->setDisplayField('queue');
        $this->setPrimaryKey('id');
    }
}
