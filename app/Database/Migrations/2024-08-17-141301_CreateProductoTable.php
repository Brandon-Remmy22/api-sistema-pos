<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductoTable extends Migration
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
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'descripcion' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'precio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'stock' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'codigo' => [
                'type'       => 'VARCHAR',  // Cambié a VARCHAR
                'constraint' => '50', 
            ],
            'img' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'sexo' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'talla' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'color' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'id_categoria' => [
                'type'       => 'INT',
                'null'       => true,
                'unsigned'   => true,
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addForeignKey('id_categoria', 'categoria', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('producto');
    }

    public function down()
    {
        $this->forge->dropTable('producto');
    }
}
