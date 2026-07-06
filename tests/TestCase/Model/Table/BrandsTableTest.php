<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BrandsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BrandsTable Test Case
 */
class BrandsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BrandsTable
     */
    protected $Brands;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Brands',
        'app.Products',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Brands') ? [] : ['className' => BrandsTable::class];
        $this->Brands = $this->getTableLocator()->get('Brands', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Brands);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BrandsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
