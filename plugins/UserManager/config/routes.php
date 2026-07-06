<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

$routes->plugin(
    'UserManager',
    ['path' => '/UserManager'],
    function (RouteBuilder $builder) {
        $builder->fallbacks(DashedRoute::class);
    }
);
