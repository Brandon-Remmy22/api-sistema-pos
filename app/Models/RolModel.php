<?php

namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model
{
    protected $table            = 'rol';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'estado',
    ];


    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
}
