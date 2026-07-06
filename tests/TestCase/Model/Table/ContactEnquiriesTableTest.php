<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContactEnquiriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContactEnquiriesTable Test Case
 */
class ContactEnquiriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContactEnquiriesTable
     */
    protected $ContactEnquiries;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.ContactEnquiries',
        'app.AssignedStaffs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ContactEnquiries') ? [] : ['className' => ContactEnquiriesTable::class];
        $this->ContactEnquiries = $this->getTableLocator()->get('ContactEnquiries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ContactEnquiries);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ContactEnquiriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\ContactEnquiriesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
