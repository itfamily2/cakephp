<?php
declare(strict_types=1);

namespace LoadTesting\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use LoadTesting\Controller\AssertionsController;

/**
 * LoadTesting\Controller\AssertionsController Test Case
 *
 * @link \LoadTesting\Controller\AssertionsController
 */
class AssertionsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.LoadTesting.Assertions',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \LoadTesting\Controller\AssertionsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
