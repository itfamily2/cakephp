<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddErpColumnsToOrders extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('orders');
        $table->addColumn('notes', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('tax', 'decimal', [
            'default' => '0.00',
            'null' => false,
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->addColumn('discount', 'decimal', [
            'default' => '0.00',
            'null' => false,
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->addColumn('grand_total', 'decimal', [
            'default' => '0.00',
            'null' => false,
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->addColumn('accepted_by', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('accepted_at', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('cancelled_by', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('cancelled_at', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
