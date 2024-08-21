<?php

namespace App\Models;

use CodeIgniter\Model;

class ComprobanteModel extends Model
{
    protected $table            = 'comprobante';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre', 
        'catindad', 
        'igv', 
        'serie', 
    ];


    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
}
