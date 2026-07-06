<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

$routes->plugin(
    'API',
    ['path' => '/API'],
    function (RouteBuilder $builder) {
        $builder->fallbacks(DashedRoute::class);
    }
);
