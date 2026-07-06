<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SentEmailsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SentEmailsTable Test Case
 */
class SentEmailsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SentEmailsTable
     */
    protected $SentEmails;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.SentEmails',
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
        $config = $this->getTableLocator()->exists('SentEmails') ? [] : ['className' => SentEmailsTable::class];
        $this->SentEmails = $this->getTableLocator()->get('SentEmails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SentEmails);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\SentEmailsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\SentEmailsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
