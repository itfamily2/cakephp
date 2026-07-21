<?php
/**
 * Load Test Seeder Script for CakePHP Enterprise
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use Cake\Console\CommandRunner;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

// Bootstrap CakePHP
require dirname(__DIR__) . '/config/bootstrap.php';

echo "Starting Load Test Data Seeding...\n";

$brandsTable = TableRegistry::getTableLocator()->get('Brands');
$categoriesTable = TableRegistry::getTableLocator()->get('Categories');
$productsTable = TableRegistry::getTableLocator()->get('Products');
$usersTable = TableRegistry::getTableLocator()->get('Users');
$rolesTable = TableRegistry::getTableLocator()->get('Roles');
$permissionsTable = TableRegistry::getTableLocator()->get('Permissions');

// 1. Seed Roles
$roles = ['Admin', 'Manager', 'Doctor', 'Pharmacist', 'Customer Support', 'Customer'];
$roleIds = [];
foreach ($roles as $r) {
    $role = $rolesTable->newEmptyEntity();
    $role->name = $r;
    $role->description = "$r Role for load testing";
    if ($saved = $rolesTable->save($role)) {
        $roleIds[] = $saved->id;
    }
}
echo "Seeded " . count($roleIds) . " roles.\n";

// 2. Seed Permissions
$modules = ['Users', 'Products', 'Orders', 'Settings', 'Reports', 'Dashboard', 'Inventory', 'Content'];
$actions = ['view', 'add', 'edit', 'delete', 'export'];
$permIds = [];
foreach ($modules as $mod) {
    foreach ($actions as $act) {
        $perm = $permissionsTable->newEmptyEntity();
        $perm->name = strtolower($mod . '_' . $act);
        $perm->description = "Can $act $mod";
        if ($saved = $permissionsTable->save($perm)) {
            $permIds[] = $saved->id;
        }
    }
}
echo "Seeded " . count($permIds) . " permissions.\n";

// 3. Seed Users (100 users)
$userIds = [];
$hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
$defaultPassword = $hasher->hash('password123');

for ($i = 1; $i <= 100; $i++) {
    $user = $usersTable->newEmptyEntity();
    $user->username = "loaduser_" . $i;
    $user->email = "loaduser_{$i}@example.com";
    $user->password = $defaultPassword;
    $user->is_active = true;
    $user->email_verified = true;
    if ($saved = $usersTable->save($user)) {
        $userIds[] = $saved->id;
    }
}
echo "Seeded 100 Users.\n";

// 4. Seed Brands (Homeopathy)
$brandNames = ["Dr. Reckeweg", "SBL", "Willmar Schwabe", "Allen", "Bakson", "BHP", "Haslab", "Medisynth", "Adel", "Boiron"];
$brandIds = [];
foreach ($brandNames as $b) {
    $brand = $brandsTable->newEmptyEntity();
    $brand->name = $b;
    $brand->slug = strtolower(str_replace([' ', '.'], ['-', ''], $b));
    $brand->is_active = true;
    if ($saved = $brandsTable->save($brand)) {
        $brandIds[] = $saved->id;
    }
}
echo "Seeded 10 Brands.\n";

// 5. Seed Categories
$catNames = ["Dilutions", "Mother Tinctures", "Triturations", "Biochemic Tablets", "Biocombinations", "Drops", "Ointments", "Syrups", "Speciality", "Cosmetics"];
$catIds = [];
foreach ($catNames as $c) {
    $cat = $categoriesTable->newEmptyEntity();
    $cat->name = $c;
    $cat->slug = strtolower(str_replace([' '], ['-'], $c));
    $cat->is_active = true;
    if ($saved = $categoriesTable->save($cat)) {
        $catIds[] = $saved->id;
    }
}
echo "Seeded 10 Categories.\n";

// 6. Seed Products (Medicines) - 500 items
$medicines = ['Nux Vomica', 'Arnica Montana', 'Belladonna', 'Aconite Napellus', 'Pulsatilla', 'Rhus Tox', 'Bryonia Alba', 'Sulphur', 'Calcarea Carb', 'Lycopodium'];
$potencies = ['30 CH', '200 CH', '1M', 'Q', '3X', '6X', '12X'];

$productCount = 0;
for ($i = 1; $i <= 500; $i++) {
    $medName = $medicines[array_rand($medicines)];
    $potency = $potencies[array_rand($potencies)];
    
    $prod = $productsTable->newEmptyEntity();
    $prod->name = "$medName $potency";
    $prod->slug = strtolower(str_replace([' ', '.'], ['-', ''], $prod->name)) . '-' . rand(1000, 9999);
    $prod->sku = "MED-" . str_pad($i, 5, "0", STR_PAD_LEFT);
    $prod->price = rand(50, 800) + 0.50;
    $prod->stock = rand(0, 500);
    $prod->is_active = true;
    
    if (!empty($brandIds)) {
        $prod->brand_id = $brandIds[array_rand($brandIds)];
    }
    if (!empty($catIds)) {
        $prod->category_id = $catIds[array_rand($catIds)];
    }
    
    if ($productsTable->save($prod)) {
        $productCount++;
    }
}
echo "Seeded $productCount Products (Homeopathic Medicines).\n";

echo "\n--- Load Test Seeding Complete! ---\n";
