<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ScheduledEmailsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ScheduledEmailsTable Test Case
 */
class ScheduledEmailsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ScheduledEmailsTable
     */
    protected $ScheduledEmails;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.ScheduledEmails',
        'app.EmailTemplates',
        'app.EmailSignatures',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ScheduledEmails') ? [] : ['className' => ScheduledEmailsTable::class];
        $this->ScheduledEmails = $this->getTableLocator()->get('ScheduledEmails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ScheduledEmails);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ScheduledEmailsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\ScheduledEmailsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
