<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

$routes->plugin(
    'Notification',
    ['path' => '/Notification'],
    function (RouteBuilder $builder) {
        $builder->fallbacks(DashedRoute::class);
    }
);
