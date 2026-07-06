<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CmsPagesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CmsPagesTable Test Case
 */
class CmsPagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CmsPagesTable
     */
    protected $CmsPages;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.CmsPages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CmsPages') ? [] : ['className' => CmsPagesTable::class];
        $this->CmsPages = $this->getTableLocator()->get('CmsPages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CmsPages);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\CmsPagesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\CmsPagesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
