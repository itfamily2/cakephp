<?php
declare(strict_types=1);

use Migrations\BaseSeed;

class InitialSeedSeed extends BaseSeed
{
    public function run(): void
    {
        // 1. Seed Roles
        $rolesData = [
            ['id' => 1, 'name' => 'Administrator', 'description' => 'Full access to all modules', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Manager', 'description' => 'Manage users, CMS and view reports', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Staff', 'description' => 'Manage CMS and contact enquiries', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Customer', 'description' => 'General customer client', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['id' => 5, 'name' => 'Vendor', 'description' => 'External merchant/vendor', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['id' => 6, 'name' => 'Guest', 'description' => 'Read-only access to public sections', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
        ];
        $this->table('roles')->insert($rolesData)->save();

        // 2. Seed Users
        $usersData = [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'is_active' => 1,
                'email_verified' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'username' => 'manager',
                'email' => 'manager@example.com',
                'password' => password_hash('manager123', PASSWORD_BCRYPT),
                'is_active' => 1,
                'email_verified' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'username' => 'staff',
                'email' => 'staff@example.com',
                'password' => password_hash('staff123', PASSWORD_BCRYPT),
                'is_active' => 1,
                'email_verified' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->table('users')->insert($usersData)->save();

        // 3. Seed User Roles
        $userRolesData = [
            ['user_id' => 1, 'role_id' => 1, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['user_id' => 2, 'role_id' => 2, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['user_id' => 3, 'role_id' => 3, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
        ];
        $this->table('user_roles')->insert($userRolesData)->save();

        // 4. Seed Groups
        $groupsData = [
            ['id' => 1, 'parent_id' => null, 'name' => 'General Users', 'registration_allowed' => 1, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['id' => 2, 'parent_id' => 1, 'name' => 'Internal Staff', 'registration_allowed' => 0, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['id' => 3, 'parent_id' => 1, 'name' => 'External Clients', 'registration_allowed' => 1, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
        ];
        $this->table('groups')->insert($groupsData)->save();

        // 5. Seed Settings
        $settingsData = [
            ['key' => 'site_name', 'value' => 'Enterprise User Management', 'input_type' => 'text', 'options' => null, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['key' => 'timezone', 'value' => 'UTC', 'input_type' => 'text', 'options' => null, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['key' => 'default_html_editor', 'value' => 'tinymce', 'input_type' => 'select', 'options' => 'tinymce,ckeditor,simple', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['key' => 'theme', 'value' => 'dark', 'input_type' => 'radio', 'options' => 'dark,light', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['key' => 'language', 'value' => 'en', 'input_type' => 'select', 'options' => 'en,es,fr,de', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['key' => 'file_upload_size', 'value' => '10', 'input_type' => 'text', 'options' => null, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
            ['key' => 'maintenance_mode', 'value' => '0', 'input_type' => 'radio', 'options' => '0,1', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')],
        ];
        $this->table('settings')->insert($settingsData)->save();

        // 6. Seed CMS Pages
        $cmsData = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h3>About Our Enterprise System</h3><p>This is a complete enterprise system built with CakePHP 5 and Bootstrap 5.</p>',
                'meta_title' => 'About Us - Enterprise User Management',
                'meta_description' => 'Learn more about our enterprise user management system.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h3>Privacy Policy</h3><p>We value your privacy. Your data is encrypted and kept secure.</p>',
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'Privacy policy of the enterprise application.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h3>Terms of Service</h3><p>Please read these terms carefully before using our application.</p>',
                'meta_title' => 'Terms of Service',
                'meta_description' => 'Terms of service of the enterprise application.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->table('cms_pages')->insert($cmsData)->save();
    }
}
