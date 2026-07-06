<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Phase 18 - Database Seed
 * Populates initial core roles.
 * Run via: bin/cake migrations seed
 */
class RolesSeed extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'description' => 'Full system access',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'Manager',
                'description' => 'Department manager',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'name' => 'Staff',
                'description' => 'Regular employee',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'name' => 'Customer',
                'description' => 'End user',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]
        ];

        $table = $this->table('roles');
        
        // Clear existing to avoid duplicate key errors on re-run
        $table->truncate();
        
        $table->insert($data)->save();
    }
}
