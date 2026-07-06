<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

$routes->plugin(
    'Audit',
    ['path' => '/Audit'],
    function (RouteBuilder $builder) {
        $builder->fallbacks(DashedRoute::class);
    }
);
