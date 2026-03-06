<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterPegawaiTables extends Migration
{
    public function up()
    {
        // 1. m_profesi (Lookup table for professions)
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_profesi' => ['type' => 'VARCHAR', 'constraint' => 100],
            'keterangan'   => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('m_profesi');

        // 2. m_pegawai (Core Employee Data)
        $this->forge->addField([
            'id'               => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'nik'              => ['type' => 'VARCHAR', 'constraint' => 16, 'unique' => true],
            'nama_pegawai'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'no_hp'            => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'ihs_practitioner' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'profesi_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            // 'unit_kerja_id' => ['type' => 'INT', 'constraint' => 11], // To be added when m_unit_layanan is finalized
            'jabatan'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'status_aktif'     => ['type' => 'BOOLEAN', 'default' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('profesi_id', 'm_profesi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('m_pegawai');

        // 3. m_pegawai_dokter (Extended attributes for Doctors)
        $this->forge->addField([
            'id'               => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'pegawai_id'       => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'unique' => true],
            'spesialisasi'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'no_sip'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'kode_dokter_bpjs' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pegawai_id', 'm_pegawai', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_pegawai_dokter');

        // 4. m_pegawai_perawat (Extended attributes for Nurses)
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'pegawai_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'unique' => true],
            'no_str'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pegawai_id', 'm_pegawai', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_pegawai_perawat');
        
        // 5. m_pegawai_apoteker (Extended attributes for Pharmacists)
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'pegawai_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'unique' => true],
            'no_sipa'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pegawai_id', 'm_pegawai', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_pegawai_apoteker');
    }

    public function down()
    {
        $this->forge->dropTable('m_pegawai_apoteker');
        $this->forge->dropTable('m_pegawai_perawat');
        $this->forge->dropTable('m_pegawai_dokter');
        $this->forge->dropTable('m_pegawai');
        $this->forge->dropTable('m_profesi');
    }
}
