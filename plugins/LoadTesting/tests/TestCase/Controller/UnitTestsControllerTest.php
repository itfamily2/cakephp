<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\UnitTestsController;

/**
 * LoadTesting\Controller\UnitTestsController Test Case
 *
 * @link \LoadTesting\Controller\UnitTestsController
 */
class UnitTestsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.UnitTests',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\UnitTestsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
