<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleModel extends Model
{
    protected $table            = 'detalle';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'precio',
        'cantidad',
        'importe',
        'estado',
        'id_producto',
        'id_venta'
    ];

    public function getProductosByVentaId($idVenta)
    {
        return $this->select('producto.nombre AS nombre_producto, detalle.precio, detalle.cantidad, detalle.importe')
            ->join('producto', 'detalle.id_producto = producto.id')
            ->where('detalle.id_venta', $idVenta)
            ->findAll();
    }

    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
}
