<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarioTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usuario'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nombre'              => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'primerApellido'      => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'segundoApellido'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => true,
            ],
            'fechaNacimiento'     => [
                'type'           => 'DATE',
            ],
            'estado'              => [
                'type'           => 'TINYINT',
                'constraint'     => '1',
            ],
            'fechaCreacion'       => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'ultimaActualizacion' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ]
        ]);

        $this->forge->addKey('id_usuario', true);
        $this->forge->createTable('usuario');
    }

    public function down()
    {
        $this->forge->dropTable('usuario');
    }
}
