<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClienteTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nombre'              => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'direccion'      => [
                'type'           => 'VARCHAR',
                'constraint'     => '200',
            ],
            'telefono'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => true,
            ],
            'numDocumento'     => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => true,
            ],
            'estado'              => [
                'type'           => 'TINYINT',
                'constraint'     => '1',
                'default'        => 1,
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

        $this->forge->addKey('id', true);
        $this->forge->createTable('cliente');
    }

    public function down()
    {
        $this->forge->dropTable('cliente');
    }
}
