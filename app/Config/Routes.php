<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->resource('usuario');
$routes->resource('rol');
$routes->post('login', 'Usuario::login');