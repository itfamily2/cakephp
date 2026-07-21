<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\LogsController;

/**
 * LoadTesting\Controller\LogsController Test Case
 *
 * @link \LoadTesting\Controller\LogsController
 */
class LogsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.Logs',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\LogsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
