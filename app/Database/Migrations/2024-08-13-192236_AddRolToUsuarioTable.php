<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRolToUsuarioTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuario', [
            'id_rol' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_usuario',
            ]
        ]);

        // Agregar la clave foránea para establecer la relación
        $this->forge->addForeignKey('id_rol', 'rol', 'id_rol', 'SET NULL', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('usuario', 'usuario_id_rol_foreign');
        $this->forge->dropColumn('usuario', 'id_rol');
    }
}
