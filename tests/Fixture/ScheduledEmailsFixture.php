<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ScheduledEmailsFixture
 */
class ScheduledEmailsFixture extends TestFixture
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
                'email_template_id' => 1,
                'email_signature_id' => 1,
                'recipient_email' => 'Lorem ipsum dolor sit amet',
                'subject' => 'Lorem ipsum dolor sit amet',
                'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'status' => 'Lorem ipsum dolor ',
                'scheduled_time' => '2026-07-06 12:22:01',
                'sent_time' => '2026-07-06 12:22:01',
                'created' => '2026-07-06 12:22:01',
                'modified' => '2026-07-06 12:22:01',
            ],
        ];
        parent::init();
    }
}
