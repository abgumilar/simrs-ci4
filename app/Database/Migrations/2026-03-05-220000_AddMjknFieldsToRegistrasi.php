<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Add MJKN booking fields to t_registrasi
 * Kolom tambahan untuk mendukung check-in dari Mobile JKN
 */
class AddMjknFieldsToRegistrasi extends Migration
{
    public function up()
    {
        // Tambah kolom yang dibutuhkan oleh alur check-in mJKN
        $fields = [
            // Kolom utama registrasi lokasi (jika belum ada dari migrasi awal)
            'no_registrasi'    => ['type' => 'VARCHAR', 'constraint' => 20,  'null' => true,  'after' => 'id'],
            'norm'             => ['type' => 'VARCHAR', 'constraint' => 20,  'null' => true],
            'unit_id'          => ['type' => 'INT',     'null' => true,  'unsigned' => true],
            'dokter_id'        => ['type' => 'INT',     'null' => true,  'unsigned' => true],
            'jenis_kunjungan'  => ['type' => 'SMALLINT','null' => true],   // 1=RJ, 2=RI, 3=IGD
            'penjamin'         => ['type' => 'VARCHAR', 'constraint' => 30,  'null' => true],  // BPJS, Umum, dll
            // BPJS / JKN fields
            'no_jkn'           => ['type' => 'VARCHAR', 'constraint' => 30,  'null' => true],
            'no_referensi'     => ['type' => 'VARCHAR', 'constraint' => 50,  'null' => true],
            'kodebooking_jkn'  => ['type' => 'VARCHAR', 'constraint' => 50,  'null' => true],
            'no_antrian'       => ['type' => 'VARCHAR', 'constraint' => 20,  'null' => true],
            'status'           => ['type' => 'VARCHAR', 'constraint' => 30,  'null' => true, 'default' => 'pending'],
        ];

        foreach ($fields as $col => $def) {
            $this->db->query("ALTER TABLE t_registrasi ADD COLUMN IF NOT EXISTS \"{$col}\" " . $this->buildColDef($def));
        }

        // Index untuk pencarian cepat
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reg_norm           ON t_registrasi (norm)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reg_kodebooking    ON t_registrasi (kodebooking_jkn)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reg_no_registrasi  ON t_registrasi (no_registrasi)");
    }

    public function down()
    {
        $cols = ['no_registrasi','norm','unit_id','dokter_id','jenis_kunjungan',
                 'penjamin','no_jkn','no_referensi','kodebooking_jkn','no_antrian','status'];
        foreach ($cols as $col) {
            $this->db->query("ALTER TABLE t_registrasi DROP COLUMN IF EXISTS \"{$col}\"");
        }
    }

    /**
     * Build column type string for raw ALTER TABLE
     */
    private function buildColDef(array $def): string
    {
        $type = strtoupper($def['type']);
        $constraint = isset($def['constraint']) ? "({$def['constraint']})" : '';
        $null = ($def['null'] ?? false) ? 'NULL' : 'NOT NULL';
        $default = isset($def['default']) ? "DEFAULT '{$def['default']}'" : '';
        return "{$type}{$constraint} {$null} {$default}";
    }
}
