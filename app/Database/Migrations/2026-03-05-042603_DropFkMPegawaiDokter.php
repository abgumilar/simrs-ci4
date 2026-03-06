<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropFkMPegawaiDokter extends Migration
{
    public function up()
    {
        // 1. Drop the legacy foreign key that points to m_pegawai
        // CodeIgniter 4 Forge across drivers can be tricky with constraint names, 
        // especially on Postgres. We'll use direct SQL if needed, but try Forge first.
        try {
            $this->db->query('ALTER TABLE m_pegawai_dokter DROP CONSTRAINT IF EXISTS m_pegawai_dokter_pegawai_id_foreign');
        } catch (\Exception $e) {
            // Log or ignore if already dropped
        }

        // 2. We can also drop unit_id to id_poli related constraints if any exist
        try {
            $this->db->query('ALTER TABLE m_pegawai_dokter DROP CONSTRAINT IF EXISTS m_pegawai_dokter_unit_id_foreign');
        } catch (\Exception $e) {
        }
    }

    public function down()
    {
        // Re-adding would require knowing exactly what it was. 
        // For simplicity and to fix the bug, we leave it flexible.
    }
}
