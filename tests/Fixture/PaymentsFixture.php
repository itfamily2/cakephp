<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PaymentsFixture
 */
class PaymentsFixture extends TestFixture
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
                'amount' => 1.5,
                'payment_method' => 'Lorem ipsum dolor sit amet',
                'status' => 'Lorem ipsum dolor sit amet',
                'transaction_reference' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-07-06 14:15:38',
                'modified' => '2026-07-06 14:15:38',
            ],
        ];
        parent::init();
    }
}
