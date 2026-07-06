<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupUsersTable Test Case
 */
class GroupUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupUsersTable
     */
    protected $GroupUsers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.GroupUsers',
        'app.Groups',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('GroupUsers') ? [] : ['className' => GroupUsersTable::class];
        $this->GroupUsers = $this->getTableLocator()->get('GroupUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->GroupUsers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\GroupUsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\GroupUsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
