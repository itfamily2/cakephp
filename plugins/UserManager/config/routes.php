<?php
declare(strict_types=1);

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin(
    'UserManager',
    ['path' => '/'],
    function (\Cake\Routing\RouteBuilder $builder) {
        // Users
        $builder->connect('/users', ['controller' => 'Users', 'action' => 'index']);
        $builder->connect('/users/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/users/add', ['controller' => 'Users', 'action' => 'add']);
        $builder->connect('/users/edit/*', ['controller' => 'Users', 'action' => 'edit']);
        $builder->connect('/users/view/*', ['controller' => 'Users', 'action' => 'view']);
        $builder->connect('/users/delete/*', ['controller' => 'Users', 'action' => 'delete']);
        
        // Roles
        $builder->connect('/roles', ['controller' => 'Roles', 'action' => 'index']);
        $builder->connect('/roles/add', ['controller' => 'Roles', 'action' => 'add']);
        $builder->connect('/roles/edit/*', ['controller' => 'Roles', 'action' => 'edit']);
        $builder->connect('/roles/view/*', ['controller' => 'Roles', 'action' => 'view']);
        $builder->connect('/roles/delete/*', ['controller' => 'Roles', 'action' => 'delete']);
        
        // Permissions
        $builder->connect('/permissions', ['controller' => 'Permissions', 'action' => 'index']);
        $builder->connect('/permissions/add', ['controller' => 'Permissions', 'action' => 'add']);
        $builder->connect('/permissions/edit/*', ['controller' => 'Permissions', 'action' => 'edit']);
        $builder->connect('/permissions/delete/*', ['controller' => 'Permissions', 'action' => 'delete']);
        $builder->connect('/permissions/export', ['controller' => 'Permissions', 'action' => 'export']);
        $builder->connect('/permissions/json-list', ['controller' => 'Permissions', 'action' => 'jsonList']);
        $builder->connect('/permissions/check-ajax', ['controller' => 'Permissions', 'action' => 'checkAjax']);
        
        // Groups
        $builder->connect('/groups', ['controller' => 'Groups', 'action' => 'index']);
        $builder->connect('/groups/add', ['controller' => 'Groups', 'action' => 'add']);
        $builder->connect('/groups/edit/*', ['controller' => 'Groups', 'action' => 'edit']);
        $builder->connect('/groups/view/*', ['controller' => 'Groups', 'action' => 'view']);
        $builder->connect('/groups/delete/*', ['controller' => 'Groups', 'action' => 'delete']);
        
        // End of plugin routes
    }
);
