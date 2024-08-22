<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetalleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'precio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'cantidad' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'importe' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'id_producto' => [
                'type'       => 'INT',
                'null'       => true,
                'unsigned'   => true,
            ],
            'id_venta' => [
                'type'       => 'INT',
                'null'       => true,
                'unsigned'   => true,
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

        // Claves foráneas
        $this->forge->addForeignKey('id_venta', 'venta', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('id_producto', 'producto', 'id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('detalle');
    }

    public function down()
    {
        //
    }
}
