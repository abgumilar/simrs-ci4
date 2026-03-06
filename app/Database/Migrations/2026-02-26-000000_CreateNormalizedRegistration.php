<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNormalizedTables extends Migration
{
    public function up()
    {
        // 1. SATUSEHAT Configuration Table
        $this->forge->addField([
            'id'               => ['type' => 'SERIAL'],
            'env'              => ['type' => 'VARCHAR', 'constraint' => 20],
            'organization_id'  => ['type' => 'VARCHAR', 'constraint' => 50],
            'client_id'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'client_secret'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'auth_token'       => ['type' => 'TEXT', 'null' => true],
            'token_expires'    => ['type' => 'TIMESTAMPTZ', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('m_satusehat_config', true);

        // 2. Refined Pendaftaran (Hub - renamed from operational migration if needed, 
        // but since we are refactoring, we create lean t_registrasi)
        $this->forge->addField([
            'id'              => ['type' => 'BIGSERIAL'],
            'no_reg'          => ['type' => 'VARCHAR', 'constraint' => 20],
            'pasien_id'       => ['type' => 'INT', 'unsigned' => true],
            'tgl_registrasi'  => ['type' => 'TIMESTAMPTZ', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'jenis_pelayanan' => ['type' => 'VARCHAR', 'constraint' => 10], // RJ, RI, IGD
            'status_reg'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('no_reg');
        $this->forge->addForeignKey('pasien_id', 'pasien', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('t_registrasi', true);

        // 3. Rawat Jalan Detail Table
        $this->forge->addField([
            'id'               => ['type' => 'BIGSERIAL'],
            'reg_id'           => ['type' => 'BIGINT'],
            'unit_id'          => ['type' => 'INT', 'unsigned' => true],
            'dokter_id'        => ['type' => 'INT', 'unsigned' => true],
            'penjamin'         => ['type' => 'VARCHAR', 'constraint' => 50], // Umum, BPJS, etc.
            'sumber_daftar'    => ['type' => 'VARCHAR', 'constraint' => 50], // Loket, MJKN, APM, Online
            'no_sep'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'ihs_encounter_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'no_antrian'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'status_panggilan' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Waiting'],
            'keluhan'          => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('reg_id', 't_registrasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'poliklinik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dokter_id', 'dokter', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('t_rawat_jalan', true);

        // 4. Also add missing IHS fields to master tables via forge
        $this->db->query("ALTER TABLE pasien ADD COLUMN IF NOT EXISTS no_jkn VARCHAR(20)");
        $this->db->query("ALTER TABLE pasien ADD COLUMN IF NOT EXISTS ihs_number VARCHAR(50)");
        $this->db->query("ALTER TABLE dokter ADD COLUMN IF NOT EXISTS ihs_practitioner VARCHAR(50)");
        $this->db->query("ALTER TABLE dokter ADD COLUMN IF NOT EXISTS kode_bpjs VARCHAR(50)");
        $this->db->query("ALTER TABLE poliklinik ADD COLUMN IF NOT EXISTS ihs_location VARCHAR(50)");
        $this->db->query("ALTER TABLE poliklinik ADD COLUMN IF NOT EXISTS kode_bpjs VARCHAR(50)");
    }

    public function down()
    {
        $this->forge->dropTable('t_rawat_jalan');
        $this->forge->dropTable('t_registrasi');
        $this->forge->dropTable('m_satusehat_config');
    }
}
