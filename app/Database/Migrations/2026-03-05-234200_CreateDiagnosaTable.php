<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create t_diagnosa table
 * Normalized multi-diagnosa per kunjungan (ICD-10)
 */
class CreateDiagnosaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'BIGSERIAL'],
            'reg_id'      => ['type' => 'BIGINT', 'null' => false],
            'kode_icd'    => ['type' => 'VARCHAR', 'constraint' => 10],
            'nama'        => ['type' => 'TEXT', 'null' => true],
            'jenis'       => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'primer'],  // primer / sekunder
            'is_bpjs'     => ['type' => 'BOOLEAN', 'default' => true],
            'created_at'  => ['type' => 'TIMESTAMPTZ', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('t_diagnosa', true);

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_diag_reg_id ON t_diagnosa (reg_id)');
    }

    public function down()
    {
        $this->forge->dropTable('t_diagnosa', true);
    }
}
