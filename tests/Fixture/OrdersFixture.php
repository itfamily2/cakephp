<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrdersFixture
 */
class OrdersFixture extends TestFixture
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
                'order_number' => 'Lorem ipsum dolor sit amet',
                'total' => 1.5,
                'status' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-07-06 14:15:31',
                'modified' => '2026-07-06 14:15:31',
            ],
        ];
        parent::init();
    }
}
