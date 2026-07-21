<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\ORM\TableRegistry;

$tables = ['Users', 'Roles', 'Products', 'Orders', 'OrderItems', 'Settings'];
$dump = [];

foreach ($tables as $alias) {
    try {
        $table = TableRegistry::getTableLocator()->get($alias);
        $dump[$alias] = $table->find()->limit(3)->enableHydration(false)->toArray();
    } catch (\Exception $e) {
        $dump[$alias] = ['error' => $e->getMessage()];
    }
}

file_put_contents(__DIR__ . '/db_dump.json', json_encode($dump, JSON_PRETTY_PRINT));
echo "Data dumped successfully.";
