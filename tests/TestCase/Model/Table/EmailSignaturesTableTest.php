<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmailSignaturesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmailSignaturesTable Test Case
 */
class EmailSignaturesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EmailSignaturesTable
     */
    protected $EmailSignatures;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.EmailSignatures',
        'app.Users',
        'app.ScheduledEmails',
        'app.SentEmails',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('EmailSignatures') ? [] : ['className' => EmailSignaturesTable::class];
        $this->EmailSignatures = $this->getTableLocator()->get('EmailSignatures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->EmailSignatures);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\EmailSignaturesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\EmailSignaturesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
