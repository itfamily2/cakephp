<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupUsersFixture
 */
class GroupUsersFixture extends TestFixture
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
                'group_id' => 1,
                'user_id' => 1,
                'created' => '2026-07-06 12:22:01',
                'modified' => '2026-07-06 12:22:01',
            ],
        ];
        parent::init();
    }
}
