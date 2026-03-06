<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResepObatTable extends Migration
{
    public function up()
    {
        // 1. Tabel Resep Obat (Header)
        $this->forge->addField([
            'id'           => ['type' => 'BIGSERIAL'],
            'reg_id'       => ['type' => 'BIGINT'],
            'unit_id'      => ['type' => 'INT', 'unsigned' => true],
            'dokter_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true], // m_pegawai_dokter
            'no_resep'     => ['type' => 'VARCHAR', 'constraint' => 50],
            'waktu_order'  => ['type' => 'TIMESTAMPTZ', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'status_resep' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Draft'], // Draft, Menunggu, Selesai
            'created_by'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at'   => ['type' => 'TIMESTAMPTZ', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'updated_at'   => ['type' => 'TIMESTAMPTZ', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('no_resep');
        $this->forge->addForeignKey('reg_id', 't_registrasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'poliklinik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dokter_id', 'm_pegawai_dokter', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('t_resep_obat', true);

        // 2. Tabel Detail Resep
        $this->forge->addField([
            'id'         => ['type' => 'BIGSERIAL'],
            'resep_id'   => ['type' => 'BIGINT'],
            'kode_obat'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nama_obat'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'jumlah'     => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 1],
            'signa'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'catatan'    => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMPTZ', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('resep_id', 't_resep_obat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('t_resep_detail', true);
    }

    public function down()
    {
        $this->forge->dropTable('t_resep_detail');
        $this->forge->dropTable('t_resep_obat');
    }
}
