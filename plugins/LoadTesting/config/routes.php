<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    $routes->plugin(
        'LoadTesting',
        ['path' => '/load-testing'],
        function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Dashboard', 'action' => 'index']);
            $builder->fallbacks(DashedRoute::class);
        }
    );
};
