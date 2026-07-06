<?php
declare(strict_types=1);

use Migrations\BaseMigration;

/**
 * Phase 17 - Complete Database Schema
 * Creates all remaining tables needed for the 50+ table ERP system.
 */
class Phase17CompleteSchema extends BaseMigration
{
    public function change(): void
    {
        // =====================================================================
        // 1. ADDRESSES
        // =====================================================================
        if (!$this->hasTable('addresses')) {
            $this->table('addresses', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('user_id',      'integer',  ['null' => true, 'default' => null])
                ->addColumn('address_type', 'string',   ['limit' => 20, 'default' => 'billing', 'comment' => 'billing|shipping|office'])
                ->addColumn('label',        'string',   ['limit' => 100, 'null' => true])
                ->addColumn('full_name',    'string',   ['limit' => 150])
                ->addColumn('phone',        'string',   ['limit' => 20, 'null' => true])
                ->addColumn('address_line1','string',   ['limit' => 255])
                ->addColumn('address_line2','string',   ['limit' => 255, 'null' => true])
                ->addColumn('city',         'string',   ['limit' => 100])
                ->addColumn('state',        'string',   ['limit' => 100, 'null' => true])
                ->addColumn('postal_code',  'string',   ['limit' => 20, 'null' => true])
                ->addColumn('country_code', 'string',   ['limit' => 5, 'default' => 'IN'])
                ->addColumn('is_default',   'boolean',  ['default' => false])
                ->addColumn('created',      'datetime', ['null' => true])
                ->addColumn('modified',     'datetime', ['null' => true])
                ->addIndex(['user_id'])
                ->create();
        }

        // =====================================================================
        // 2. ATTACHMENTS
        // =====================================================================
        if (!$this->hasTable('attachments')) {
            $this->table('attachments', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('model',        'string',   ['limit' => 100])
                ->addColumn('foreign_key',  'integer',  ['null' => true])
                ->addColumn('field',        'string',   ['limit' => 100, 'default' => 'file'])
                ->addColumn('original_name','string',   ['limit' => 255])
                ->addColumn('file_name',    'string',   ['limit' => 255])
                ->addColumn('file_path',    'string',   ['limit' => 500])
                ->addColumn('mime_type',    'string',   ['limit' => 100])
                ->addColumn('file_size',    'integer',  ['default' => 0])
                ->addColumn('uploaded_by',  'integer',  ['null' => true])
                ->addColumn('created',      'datetime', ['null' => true])
                ->addIndex(['model', 'foreign_key'])
                ->create();
        }

        // =====================================================================
        // 3. NOTIFICATIONS
        // =====================================================================
        if (!$this->hasTable('notifications')) {
            $this->table('notifications', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('user_id',    'integer',  ['null' => true])
                ->addColumn('type',       'string',   ['limit' => 50, 'default' => 'info', 'comment' => 'info|success|warning|error'])
                ->addColumn('title',      'string',   ['limit' => 255])
                ->addColumn('message',    'text',     [])
                ->addColumn('url',        'string',   ['limit' => 500, 'null' => true])
                ->addColumn('is_read',    'boolean',  ['default' => false])
                ->addColumn('read_at',    'datetime', ['null' => true])
                ->addColumn('created',    'datetime', ['null' => true])
                ->addIndex(['user_id', 'is_read'])
                ->create();
        }

        // =====================================================================
        // 4. SESSIONS
        // =====================================================================
        if (!$this->hasTable('sessions')) {
            $this->table('sessions', ['id' => false, 'primary_key' => ['id']])
                ->addColumn('id',         'string',   ['limit' => 128])
                ->addColumn('data',       'text',     ['null' => true])
                ->addColumn('expires',    'integer',  ['null' => true])
                ->create();
        }

        // =====================================================================
        // 5. LOGIN LOGS
        // =====================================================================
        if (!$this->hasTable('login_logs')) {
            $this->table('login_logs', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('user_id',    'integer',  ['null' => true])
                ->addColumn('username',   'string',   ['limit' => 100, 'null' => true])
                ->addColumn('ip_address', 'string',   ['limit' => 45, 'null' => true])
                ->addColumn('user_agent', 'string',   ['limit' => 500, 'null' => true])
                ->addColumn('status',     'string',   ['limit' => 20, 'default' => 'success', 'comment' => 'success|failed|locked'])
                ->addColumn('fail_reason','string',   ['limit' => 255, 'null' => true])
                ->addColumn('created',    'datetime', ['null' => true])
                ->addIndex(['user_id'])
                ->addIndex(['ip_address'])
                ->create();
        }

        // =====================================================================
        // 6. COUNTRIES
        // =====================================================================
        if (!$this->hasTable('countries')) {
            $this->table('countries', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('name',        'string',  ['limit' => 100])
                ->addColumn('code',        'string',  ['limit' => 5])
                ->addColumn('phone_code',  'string',  ['limit' => 10, 'null' => true])
                ->addColumn('currency',    'string',  ['limit' => 5, 'null' => true])
                ->addColumn('flag',        'string',  ['limit' => 10, 'null' => true])
                ->addColumn('is_active',   'boolean', ['default' => true])
                ->addIndex(['code'], ['unique' => true])
                ->create();
        }

        // =====================================================================
        // 7. STATES
        // =====================================================================
        if (!$this->hasTable('states')) {
            $this->table('states', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('country_id', 'integer', [])
                ->addColumn('name',       'string',  ['limit' => 150])
                ->addColumn('code',       'string',  ['limit' => 20, 'null' => true])
                ->addColumn('is_active',  'boolean', ['default' => true])
                ->addIndex(['country_id'])
                ->create();
        }

        // =====================================================================
        // 8. CITIES
        // =====================================================================
        if (!$this->hasTable('cities')) {
            $this->table('cities', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('state_id',   'integer', [])
                ->addColumn('country_id', 'integer', [])
                ->addColumn('name',       'string',  ['limit' => 150])
                ->addColumn('is_active',  'boolean', ['default' => true])
                ->addIndex(['state_id'])
                ->create();
        }

        // =====================================================================
        // 9. CURRENCIES
        // =====================================================================
        if (!$this->hasTable('currencies')) {
            $this->table('currencies', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('name',           'string',  ['limit' => 100])
                ->addColumn('code',           'string',  ['limit' => 5])
                ->addColumn('symbol',         'string',  ['limit' => 10])
                ->addColumn('exchange_rate',  'decimal', ['precision' => 15, 'scale' => 6, 'default' => '1.000000'])
                ->addColumn('is_default',     'boolean', ['default' => false])
                ->addColumn('is_active',      'boolean', ['default' => true])
                ->addIndex(['code'], ['unique' => true])
                ->create();
        }

        // =====================================================================
        // 10. LANGUAGES
        // =====================================================================
        if (!$this->hasTable('languages')) {
            $this->table('languages', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('name',        'string',  ['limit' => 100])
                ->addColumn('code',        'string',  ['limit' => 10])
                ->addColumn('locale',      'string',  ['limit' => 20, 'null' => true])
                ->addColumn('direction',   'string',  ['limit' => 5, 'default' => 'ltr'])
                ->addColumn('is_default',  'boolean', ['default' => false])
                ->addColumn('is_active',   'boolean', ['default' => true])
                ->addIndex(['code'], ['unique' => true])
                ->create();
        }

        // =====================================================================
        // 11. JOBS (Async Job Queue)
        // =====================================================================
        if (!$this->hasTable('jobs')) {
            $this->table('jobs', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('queue',        'string',   ['limit' => 100, 'default' => 'default'])
                ->addColumn('payload',      'text',  [])
                ->addColumn('attempts',     'smallinteger', ['default' => 0])
                ->addColumn('reserved_at',  'datetime', ['null' => true])
                ->addColumn('available_at', 'datetime', [])
                ->addColumn('created',      'datetime', ['null' => true])
                ->addIndex(['queue'])
                ->create();
        }

        // =====================================================================
        // 12. FAILED JOBS
        // =====================================================================
        if (!$this->hasTable('failed_jobs')) {
            $this->table('failed_jobs', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('uuid',         'string',   ['limit' => 100, 'null' => true])
                ->addColumn('connection',   'string',   ['limit' => 100])
                ->addColumn('queue',        'string',   ['limit' => 100])
                ->addColumn('payload',      'text',  [])
                ->addColumn('exception',    'text',  [])
                ->addColumn('failed_at',    'datetime', ['null' => true])
                ->create();
        }

        // =====================================================================
        // 13. API TOKENS
        // =====================================================================
        if (!$this->hasTable('api_tokens')) {
            $this->table('api_tokens', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('user_id',      'integer',  [])
                ->addColumn('token',        'string',   ['limit' => 500])
                ->addColumn('name',         'string',   ['limit' => 100, 'null' => true, 'comment' => 'Token label e.g. iOS App'])
                ->addColumn('abilities',    'text',     ['null' => true, 'comment' => 'JSON array of allowed actions'])
                ->addColumn('last_used_at', 'datetime', ['null' => true])
                ->addColumn('expires_at',   'datetime', ['null' => true])
                ->addColumn('is_revoked',   'boolean',  ['default' => false])
                ->addColumn('created',      'datetime', ['null' => true])
                ->addColumn('modified',     'datetime', ['null' => true])
                ->addIndex(['user_id'])
                ->addIndex(['token'], ['unique' => true])
                ->create();
        }

        // =====================================================================
        // 14. CONTACTS  
        // =====================================================================
        if (!$this->hasTable('contacts')) {
            $this->table('contacts', ['id' => true, 'primary_key' => ['id']])
                ->addColumn('name',       'string',   ['limit' => 200])
                ->addColumn('email',      'string',   ['limit' => 255, 'null' => true])
                ->addColumn('phone',      'string',   ['limit' => 30, 'null' => true])
                ->addColumn('company',    'string',   ['limit' => 200, 'null' => true])
                ->addColumn('message',    'text',     [])
                ->addColumn('status',     'string',   ['limit' => 30, 'default' => 'new', 'comment' => 'new|read|replied|closed'])
                ->addColumn('ip_address', 'string',   ['limit' => 45, 'null' => true])
                ->addColumn('created',    'datetime', ['null' => true])
                ->addColumn('modified',   'datetime', ['null' => true])
                ->create();
        }
    }
}
