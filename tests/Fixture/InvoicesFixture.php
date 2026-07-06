<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InvoicesFixture
 */
class InvoicesFixture extends TestFixture
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
                'invoice_number' => 'Lorem ipsum dolor sit amet',
                'amount' => 1.5,
                'tax' => 1.5,
                'discount' => 1.5,
                'status' => 'Lorem ipsum dolor sit amet',
                'due_date' => '2026-07-06',
                'notes' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2026-07-06 14:34:07',
                'modified' => '2026-07-06 14:34:07',
            ],
        ];
        parent::init();
    }
}
