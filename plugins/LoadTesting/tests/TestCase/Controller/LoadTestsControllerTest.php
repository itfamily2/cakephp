<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\LoadTestsController;

/**
 * LoadTesting\Controller\LoadTestsController Test Case
 *
 * @link \LoadTesting\Controller\LoadTestsController
 */
class LoadTestsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.LoadTests',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\LoadTestsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
