<?php
declare(strict_types=1);

use Migrations\BaseMigration;

/**
 * Phase 5: Add soft delete columns to products, categories, cms_pages
 */
class AddSoftDeleteColumns extends BaseMigration
{
    public function change(): void
    {
        // Add deleted_at to products (for SoftDeleteBehavior)
        $this->table('products')
             ->addColumn('deleted_at', 'datetime', ['null' => true, 'default' => null, 'after' => 'description'])
             ->update();

        // Add soft delete to categories too
        $this->table('categories')
             ->addColumn('deleted_at', 'datetime', ['null' => true, 'default' => null, 'after' => 'description'])
             ->update();

        // Add soft delete to cms_pages
        $this->table('cms_pages')
             ->addColumn('deleted_at', 'datetime', ['null' => true, 'default' => null])
             ->update();
    }
}
