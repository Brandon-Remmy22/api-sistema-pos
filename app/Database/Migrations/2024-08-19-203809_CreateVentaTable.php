<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVentaTable extends Migration
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
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'num_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'serie' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'descuento' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'igv' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'subtotal' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'id_cliente' => [
                'type'       => 'INT',
                'null'       => true,
                'unsigned'   => true,
            ],
            'id_usuario' => [
                'type'       => 'INT',
                'null'       => true,
                'unsigned'   => true,
            ],
            'id_comprobante' => [
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
        $this->forge->addForeignKey('id_cliente', 'cliente', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('id_usuario', 'usuario', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('id_comprobante', 'comprobante', 'id', 'SET NULL', 'SET NULL');
   
        $this->forge->createTable('venta');
   
    }

    public function down()
    {
        $this->forge->dropTable('venta');
    }
}
