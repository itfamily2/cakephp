<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\ORM\TableRegistry;

$products = TableRegistry::getTableLocator()->get('Products')->find()->contain(['Categories', 'Brands'])->limit(3)->toArray();

foreach ($products as $p) {
    echo "Product: {$p->name} | category_id: {$p->category_id} | brand_id: {$p->brand_id}\n";
    echo "Has Category Relation: " . ($p->hasValue('category') ? "Yes ({$p->category->name})" : "No") . "\n";
    echo "Has Brand Relation: " . ($p->hasValue('brand') ? "Yes ({$p->brand->name})" : "No") . "\n";
    echo "--------------------------\n";
}
