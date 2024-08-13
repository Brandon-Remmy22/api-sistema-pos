<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_rol'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nombre'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'descripcion'     => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'estado'          => [
                'type'           => 'TINYINT',
                'constraint'     => '1',
                'default'        => 1,
            ],
            'fechaCreacion'   => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'ultimaActualizacion' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ]
        ]);
        $this->forge->addKey('id_rol', true);
        $this->forge->createTable('rol');
    }

    public function down()
    {
        $this->forge->dropTable('rol');
    }
}
