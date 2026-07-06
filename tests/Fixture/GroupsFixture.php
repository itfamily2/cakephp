<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupsFixture
 */
class GroupsFixture extends TestFixture
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
                'parent_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'registration_allowed' => 1,
                'created' => '2026-07-06 12:22:01',
                'modified' => '2026-07-06 12:22:01',
            ],
        ];
        parent::init();
    }
}
