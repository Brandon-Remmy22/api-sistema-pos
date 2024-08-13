<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailAndPasswordToUsuario extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuario', [
            'email'    => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true, // Asegura que el email sea único
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255', // Longitud suficiente para contraseñas encriptadas
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuario', ['email', 'password']);
    }
}
