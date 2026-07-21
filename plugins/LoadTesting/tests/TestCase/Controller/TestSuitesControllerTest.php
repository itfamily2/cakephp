<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\TestSuitesController;

/**
 * LoadTesting\Controller\TestSuitesController Test Case
 *
 * @link \LoadTesting\Controller\TestSuitesController
 */
class TestSuitesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.TestSuites',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\TestSuitesController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
