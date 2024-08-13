<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'cliente';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre', 
        'direccion', 
        'telefono', 
        'numDocumento', 
        'estado',
    ];


    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
}
