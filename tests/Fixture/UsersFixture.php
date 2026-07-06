<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'username' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'profile_image' => 'Lorem ipsum dolor sit amet',
                'last_login_time' => '2026-07-06 12:22:02',
                'last_login_ip' => 'Lorem ipsum dolor sit amet',
                'remember_token' => 'Lorem ipsum dolor sit amet',
                'is_active' => 1,
                'email_verified' => 1,
                'verification_token' => 'Lorem ipsum dolor sit amet',
                'password_reset_token' => 'Lorem ipsum dolor sit amet',
                'password_reset_expiry' => '2026-07-06 12:22:02',
                'created' => '2026-07-06 12:22:02',
                'modified' => '2026-07-06 12:22:02',
            ],
        ];
        parent::init();
    }
}
