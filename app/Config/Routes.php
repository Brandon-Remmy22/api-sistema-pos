<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->group('/api', ['filter' => 'cors'], static function (RouteCollection $routes): void {

    $routes->get('/', 'Home::index');
    $routes->resource('usuario');
    $routes->post('crear-vendedor', 'Usuario::crearVendedor');
    $routes->resource('rol');
    $routes->resource('cliente');
    $routes->post('login', 'Usuario::login');
    $routes->post('cambiar-contrasenia', 'Usuario::changePassword');
    
});
