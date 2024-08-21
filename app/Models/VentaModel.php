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


    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
}
