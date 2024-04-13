<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->set404Override(function ($err) {
    $data = [
        'success' => false,
        'data' => 'Page not found'
    ];

    $response = service('response');
    $response->setJSON($data);
    $response->setHeader('Content-Type', 'application/json');
    $response->setStatusCode(404);

    $response->send();

    return "";
});
