<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', static function () {
    $url = current_url(true);
    $docs_url = str_replace($url->getPath(), "/docs", $url);
    return redirect()->to($docs_url);
});

$routes->group('url', static function ($routes) {
    $routes->get('(:num)/next', 'Primes::next/$1');
    $routes->get('(:num)/prev', 'Primes::prev/$1');
    $routes->get('(:num)/and/(:num)', 'Primes::between/$1/$2');
});


$routes->group('get', static function ($routes) {
    $routes->get('next', 'Primes::nextGet');
    $routes->get('prev', 'Primes::prevGet');
    $routes->get('between', 'Primes::betweenGet');
});

$routes->group('post', static function ($routes) {
    $routes->post('next', 'Primes::nextPost');
    $routes->post('prev', 'Primes::prevPost');
    $routes->post('between', 'Primes::betweenPost');
});

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
