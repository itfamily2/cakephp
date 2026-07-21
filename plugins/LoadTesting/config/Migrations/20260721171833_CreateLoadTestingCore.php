<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateLoadTestingCore extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/5/guides/writing-migrations/migration-methods.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        // Test Scenarios Table
        $scenarios = $this->table('load_testing_scenarios');
        $scenarios->addColumn('name', 'string', ['limit' => 255])
                  ->addColumn('description', 'text', ['null' => true])
                  ->addColumn('endpoint', 'string', ['limit' => 255])
                  ->addColumn('method', 'string', ['limit' => 10, 'default' => 'GET'])
                  ->addColumn('headers', 'text', ['null' => true]) // JSON
                  ->addColumn('body_payload', 'text', ['null' => true]) // JSON
                  ->addColumn('assertions', 'text', ['null' => true]) // JSON
                  ->addColumn('created', 'datetime')
                  ->addColumn('modified', 'datetime')
                  ->create();

        // Load Tests Table
        $tests = $this->table('load_testing_tests');
        $tests->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('scenario_id', 'integer')
              ->addColumn('virtual_users', 'integer', ['default' => 10])
              ->addColumn('ramp_up_seconds', 'integer', ['default' => 0])
              ->addColumn('duration_seconds', 'integer', ['default' => 60])
              ->addColumn('status', 'string', ['limit' => 50, 'default' => 'Pending'])
              ->addColumn('result_json', 'text', ['null' => true]) // Store results like avg latency, etc
              ->addColumn('started_at', 'datetime', ['null' => true])
              ->addColumn('completed_at', 'datetime', ['null' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addForeignKey('scenario_id', 'load_testing_scenarios', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
              ->create();
    }
}
