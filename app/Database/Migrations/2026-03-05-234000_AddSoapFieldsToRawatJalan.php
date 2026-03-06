<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Add full SOAP + BPJS + SatuSehat fields to t_rawat_jalan
 */
class AddSoapFieldsToRawatJalan extends Migration
{
    public function up()
    {
        $cols = [
            // Status EMR kunjungan
            'status_emr'              => 'VARCHAR(20) NULL DEFAULT \'draft\'',
            'sumber_daftar'           => 'VARCHAR(30) NULL',
            // S - Subjective (Anamnesis)
            'keluhan_utama'           => 'TEXT NULL',
            'riwayat_penyakit'        => 'TEXT NULL',
            'riwayat_penyakit_dahulu' => 'TEXT NULL',
            'riwayat_alergi'          => 'TEXT NULL',
            'riwayat_keluarga'        => 'TEXT NULL',
            // O - Objective (Pemeriksaan Fisik)
            'keadaan_umum'            => 'VARCHAR(50) NULL',
            'kesadaran'               => 'VARCHAR(50) NULL',
            'pemeriksaan_fisik'       => 'TEXT NULL',
            // A - Assessment (Diagnosa)
            'diagnosa_utama'          => 'VARCHAR(10) NULL',
            'diagnosa_utama_nama'     => 'TEXT NULL',
            'diagnosa_sekunder'       => 'TEXT NULL',
            // P - Plan
            'terapi'                  => 'TEXT NULL',
            'edukasi'                 => 'TEXT NULL',
            'anjuran_kontrol'         => 'TEXT NULL',
            // Status akhir kunjungan
            'status_pulang'           => 'VARCHAR(30) NULL',
            'kondisi_pulang'          => 'VARCHAR(30) NULL',
            'tgl_selesai'             => 'TIMESTAMPTZ NULL',
            // BPJS
            'no_sep'                  => 'VARCHAR(25) NULL',
            'diag_awal'               => 'VARCHAR(10) NULL',
            // SatuSehat
            'ihs_encounter_id'        => 'VARCHAR(100) NULL',
        ];

        foreach ($cols as $col => $def) {
            $this->db->query("ALTER TABLE \"t_rawat_jalan\" ADD COLUMN IF NOT EXISTS \"{$col}\" {$def}");
        }
    }

    public function down()
    {
        $cols = [
            'status_emr','sumber_daftar','keluhan_utama','riwayat_penyakit',
            'riwayat_penyakit_dahulu','riwayat_alergi','riwayat_keluarga',
            'keadaan_umum','kesadaran','pemeriksaan_fisik',
            'diagnosa_utama','diagnosa_utama_nama','diagnosa_sekunder',
            'terapi','edukasi','anjuran_kontrol',
            'status_pulang','kondisi_pulang','tgl_selesai',
            'no_sep','diag_awal','ihs_encounter_id',
        ];
        foreach ($cols as $col) {
            $this->db->query("ALTER TABLE \"t_rawat_jalan\" DROP COLUMN IF EXISTS \"{$col}\"");
        }
    }
}
