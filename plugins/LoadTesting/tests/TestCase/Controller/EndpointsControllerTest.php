<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\EndpointsController;

/**
 * LoadTesting\Controller\EndpointsController Test Case
 *
 * @link \LoadTesting\Controller\EndpointsController
 */
class EndpointsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.Endpoints',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\EndpointsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
