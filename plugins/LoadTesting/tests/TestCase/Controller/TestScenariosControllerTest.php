<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\TestScenariosController;

/**
 * LoadTesting\Controller\TestScenariosController Test Case
 *
 * @link \LoadTesting\Controller\TestScenariosController
 */
class TestScenariosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.TestScenarios',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\TestScenariosController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
