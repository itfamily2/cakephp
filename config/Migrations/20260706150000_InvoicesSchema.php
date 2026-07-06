<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class InvoicesSchema extends BaseMigration
{
    public function change(): void
    {
        // invoices table — linked to orders
        $table = $this->table('invoices');
        $table->addColumn('order_id', 'integer')
              ->addColumn('invoice_number', 'string', ['limit' => 50])
              ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('tax', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => 0])
              ->addColumn('discount', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => 0])
              ->addColumn('status', 'string', ['limit' => 30, 'default' => 'Draft'])
              ->addColumn('due_date', 'date', ['null' => true])
              ->addColumn('notes', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['null' => true])
              ->addForeignKey('order_id', 'orders', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
              ->addIndex(['invoice_number'], ['unique' => true])
              ->create();
    }
}
