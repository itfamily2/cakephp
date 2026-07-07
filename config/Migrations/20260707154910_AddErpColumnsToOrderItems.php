<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddErpColumnsToOrderItems extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('order_items');
        $table->addColumn('discount', 'decimal', [
            'default' => '0.00',
            'null' => false,
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->addColumn('tax', 'decimal', [
            'default' => '0.00',
            'null' => false,
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->addColumn('line_total', 'decimal', [
            'default' => '0.00',
            'null' => false,
            'precision' => 10,
            'scale' => 2,
        ]);
        $table->update();
    }
}
