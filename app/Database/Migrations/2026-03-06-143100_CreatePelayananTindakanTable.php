<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelayananTindakanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'BIGSERIAL'],
            'reg_id'         => ['type' => 'BIGINT'],
            'unit_id'        => ['type' => 'INT', 'unsigned' => true],
            'pelaksana_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true], // m_pegawai
            'kode_tarif'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nama_tindakan'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'qty'            => ['type' => 'INT', 'default' => 1],
            'tarif_satuan'   => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'tarif_total'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'waktu_tindakan' => ['type' => 'TIMESTAMPTZ', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'created_by'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMPTZ', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'updated_at'     => ['type' => 'TIMESTAMPTZ', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('reg_id', 't_registrasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'poliklinik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pelaksana_id', 'm_pegawai', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('t_pelayanan_tindakan', true);
    }

    public function down()
    {
        $this->forge->dropTable('t_pelayanan_tindakan');
    }
}
