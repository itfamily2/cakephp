<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrderItemsFixture
 */
class OrderItemsFixture extends TestFixture
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
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 1,
                'price' => 1.5,
                'created' => '2026-07-06 14:15:34',
                'modified' => '2026-07-06 14:15:34',
            ],
        ];
        parent::init();
    }
}
