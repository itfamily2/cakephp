<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class InitialSchema extends BaseMigration
{
    public function change(): void
    {
        // roles
        $table = $this->table('roles');
        $table->addColumn('name', 'string', ['limit' => 50])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->create();

        // users
        $table = $this->table('users');
        $table->addColumn('username', 'string', ['limit' => 50])
              ->addColumn('email', 'string', ['limit' => 100])
              ->addColumn('password', 'string', ['limit' => 255])
              ->addColumn('profile_image', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('last_login_time', 'datetime', ['null' => true])
              ->addColumn('last_login_ip', 'string', ['limit' => 45, 'null' => true])
              ->addColumn('remember_token', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('email_verified', 'boolean', ['default' => false])
              ->addColumn('verification_token', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('password_reset_token', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('password_reset_expiry', 'datetime', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addIndex(['username'], ['unique' => true])
              ->addIndex(['email'], ['unique' => true])
              ->create();

        // user_roles
        $table = $this->table('user_roles');
        $table->addColumn('user_id', 'integer')
              ->addColumn('role_id', 'integer')
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->addForeignKey('role_id', 'roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();

        // groups
        $table = $this->table('groups');
        $table->addColumn('parent_id', 'integer', ['null' => true])
              ->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('registration_allowed', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('parent_id', 'groups', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
              ->create();

        // group_users
        $table = $this->table('group_users');
        $table->addColumn('group_id', 'integer')
              ->addColumn('user_id', 'integer')
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();

        // permissions
        $table = $this->table('permissions');
        $table->addColumn('role_id', 'integer', ['null' => true])
              ->addColumn('group_id', 'integer', ['null' => true])
              ->addColumn('controller', 'string', ['limit' => 100])
              ->addColumn('action', 'string', ['limit' => 100])
              ->addColumn('allowed', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('role_id', 'roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();

        // email_templates
        $table = $this->table('email_templates');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('subject', 'string', ['limit' => 255])
              ->addColumn('body', 'text')
              ->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
              ->create();

        // email_signatures
        $table = $this->table('email_signatures');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('body', 'text')
              ->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
              ->create();

        // scheduled_emails
        $table = $this->table('scheduled_emails');
        $table->addColumn('email_template_id', 'integer', ['null' => true])
              ->addColumn('email_signature_id', 'integer', ['null' => true])
              ->addColumn('recipient_email', 'string', ['limit' => 255])
              ->addColumn('subject', 'string', ['limit' => 255])
              ->addColumn('body', 'text')
              ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
              ->addColumn('scheduled_time', 'datetime', ['null' => true])
              ->addColumn('sent_time', 'datetime', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->create();

        // sent_emails
        $table = $this->table('sent_emails');
        $table->addColumn('email_template_id', 'integer', ['null' => true])
              ->addColumn('email_signature_id', 'integer', ['null' => true])
              ->addColumn('recipient_email', 'string', ['limit' => 255])
              ->addColumn('subject', 'string', ['limit' => 255])
              ->addColumn('body', 'text')
              ->addColumn('sent_time', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->create();

        // cms_pages
        $table = $this->table('cms_pages');
        $table->addColumn('title', 'string', ['limit' => 255])
              ->addColumn('slug', 'string', ['limit' => 255])
              ->addColumn('content', 'text')
              ->addColumn('meta_title', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('meta_description', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addIndex(['slug'], ['unique' => true])
              ->create();

        // contact_enquiries
        $table = $this->table('contact_enquiries');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('email', 'string', ['limit' => 100])
              ->addColumn('subject', 'string', ['limit' => 255])
              ->addColumn('message', 'text')
              ->addColumn('reply_message', 'text', ['null' => true])
              ->addColumn('reply_status', 'string', ['limit' => 20, 'default' => 'pending'])
              ->addColumn('assigned_staff_id', 'integer', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('assigned_staff_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
              ->create();

        // settings
        $table = $this->table('settings');
        $table->addColumn('key', 'string', ['limit' => 100])
              ->addColumn('value', 'text', ['null' => true])
              ->addColumn('input_type', 'string', ['limit' => 50, 'default' => 'text'])
              ->addColumn('options', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addIndex(['key'], ['unique' => true])
              ->create();

        // activity_logs
        $table = $this->table('activity_logs');
        $table->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('action', 'string', ['limit' => 100])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('ip_address', 'string', ['limit' => 45, 'null' => true])
              ->addColumn('user_agent', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
              ->create();

        // audit_logs
        $table = $this->table('audit_logs');
        $table->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('table_name', 'string', ['limit' => 100])
              ->addColumn('row_id', 'integer')
              ->addColumn('action', 'string', ['limit' => 20])
              ->addColumn('old_values', 'text', ['null' => true])
              ->addColumn('new_values', 'text', ['null' => true])
              ->addColumn('ip_address', 'string', ['limit' => 45, 'null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
              ->create();
    }
}
