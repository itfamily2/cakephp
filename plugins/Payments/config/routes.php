<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

$routes->plugin(
    'Payments',
    ['path' => '/Payments'],
    function (RouteBuilder $builder) {
        $builder->fallbacks(DashedRoute::class);
    }
);
