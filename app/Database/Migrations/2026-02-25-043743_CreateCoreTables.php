<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreTables extends Migration
{
    public function up()
    {
        // 1. Users Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'fullname'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'        => ['type' => 'VARCHAR', 'constraint' => 20], // admin, dokter, perawat, apoteker, kasir, resepsionis
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // 2. Poliklinik Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_poli'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'lokasi'      => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('poliklinik');

        // 3. Dokter Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_user'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_poli'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'specialis'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'sip'         => ['type' => 'VARCHAR', 'constraint' => 50], // Surat Izin Praktik
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_poli', 'poliklinik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dokter');
    }

    public function down()
    {
        $this->forge->dropTable('pasien');
        $this->forge->dropTable('dokter');
        $this->forge->dropTable('poliklinik');
        $this->forge->dropTable('users');
    }
}
