<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\ReportsController;

/**
 * LoadTesting\Controller\ReportsController Test Case
 *
 * @link \LoadTesting\Controller\ReportsController
 */
class ReportsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.Reports',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\ReportsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
