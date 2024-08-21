<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComprobanteTable extends Migration
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
            'catindad' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'igv' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'serie' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'fechaCreacion'  => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'ultimaActualizacion' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('comprobante');
    }

    public function down()
    {
        $this->forge->dropTable('comprobante');
    }
}
