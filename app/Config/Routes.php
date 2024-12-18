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
    $routes->resource('producto');
    $routes->resource('categoria');
    $routes->resource('venta');
    $routes->resource('comprobante');
    $routes->post('subir-imagen', 'Producto::uploadImg');
    $routes->get('reporte/ventas', 'Venta::obtenerReporteVentas');
    $routes->get('reporte/ventas-por-categoria', 'Venta::ventasPorCategoria');


    $routes->get('reporte/mas-vendidos', 'Venta::productosMasVendidos');
    $routes->get('cliente-search', 'Cliente::filterClients');
    $routes->get('ventas/cliente/(:num)', 'Venta::ventasPorCliente/$1');

});
