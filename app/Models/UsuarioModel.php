<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuario';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre', 
        'primerApellido', 
        'segundoApellido', 
        'fechaNacimiento', 
        'estado',
        'email',   
        'password' ,
        'id_rol'  
    ];


    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'fechaCreacion';
    protected $updatedField  = 'ultimaActualizacion';
    
}