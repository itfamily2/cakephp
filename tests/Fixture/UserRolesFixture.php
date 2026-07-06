<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserRolesFixture
 */
class UserRolesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'role_id' => 1,
                'created' => '2026-07-06 12:22:02',
                'modified' => '2026-07-06 12:22:02',
            ],
        ];
        parent::init();
    }
}
