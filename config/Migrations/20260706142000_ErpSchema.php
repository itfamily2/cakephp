<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class ErpSchema extends BaseMigration
{
    public function change(): void
    {
        // categories
        $table = $this->table('categories');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('slug', 'string', ['limit' => 100])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->create();

        // brands
        $table = $this->table('brands');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('slug', 'string', ['limit' => 100])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->create();

        // products
        $table = $this->table('products');
        $table->addColumn('category_id', 'integer', ['null' => true])
              ->addColumn('brand_id', 'integer', ['null' => true])
              ->addColumn('name', 'string', ['limit' => 150])
              ->addColumn('slug', 'string', ['limit' => 150])
              ->addColumn('sku', 'string', ['limit' => 50])
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('stock', 'integer', ['default' => 0])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
              ->addForeignKey('brand_id', 'brands', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
              ->create();

        // orders
        $table = $this->table('orders');
        $table->addColumn('user_id', 'integer', ['null' => true])
              ->addColumn('order_number', 'string', ['limit' => 50])
              ->addColumn('total', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('status', 'string', ['limit' => 30, 'default' => 'Pending'])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
              ->create();

        // order_items
        $table = $this->table('order_items');
        $table->addColumn('order_id', 'integer')
              ->addColumn('product_id', 'integer', ['null' => true])
              ->addColumn('quantity', 'integer', ['default' => 1])
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('order_id', 'orders', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
              ->addForeignKey('product_id', 'products', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
              ->create();

        // payments
        $table = $this->table('payments');
        $table->addColumn('order_id', 'integer')
              ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('payment_method', 'string', ['limit' => 50])
              ->addColumn('status', 'string', ['limit' => 30, 'default' => 'Pending'])
              ->addColumn('transaction_reference', 'string', ['limit' => 100, 'null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('order_id', 'orders', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
              ->create();
    }
}
