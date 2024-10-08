<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'producto';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'precio', 
        'stock', 
        'codigo',
        'sexo',
        'talla',
        'color',
        'img',
        'estado',
        'id_categoria'
    ];


    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
}
