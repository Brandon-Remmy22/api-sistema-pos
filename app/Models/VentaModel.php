<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table            = 'venta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'total',
        'num_documento',
        'serie',
        'descuento',
        'igv',
        'subtotal',
        'estado',
        'id_cliente',
        'id_usuario',
        'id_comprobante'
    ];

    public function getVentasWithCliente()
    {
        $ventas = $this->select('venta.*, cliente.nombre AS nombre_cliente, cliente.numDocumento AS ci')
            ->join('cliente', 'venta.id_cliente = cliente.id')
            // ->where('venta.estado', 1)
            ->findAll();

        $detalleModel = new \App\Models\DetalleModel();

        foreach ($ventas as &$venta) {
            $venta['productos'] = $detalleModel->getProductosByVentaId($venta['id']);
        }

        return $ventas;
    }
    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
}
