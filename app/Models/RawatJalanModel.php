<?php

namespace App\Models;

use CodeIgniter\Model;

class RawatJalanModel extends Model
{
    protected $table         = 't_rawat_jalan';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'reg_id', 'unit_id', 'dokter_id', 'penjamin',
        'sumber_daftar', 'no_antrian', 'status_emr',
        // S
        'keluhan', 'keluhan_utama', 'riwayat_penyakit', 'riwayat_penyakit_dahulu',
        'riwayat_alergi', 'riwayat_keluarga',
        // O
        'keadaan_umum', 'kesadaran', 'pemeriksaan_fisik',
        // A
        'diagnosa_utama', 'diagnosa_utama_nama', 'diagnosa_sekunder',
        // P
        'terapi', 'edukasi', 'anjuran_kontrol',
        // Status
        'status_pulang', 'kondisi_pulang', 'tgl_selesai',
        // BPJS
        'no_sep', 'tujuan_kunjungan', 'asal_rujukan', 'no_rujukan', 'diag_awal',
        // SatuSehat
        'ihs_encounter_id',
    ];

    /**
     * Worklist harian per poli, join ke t_registrasi dan master data
     */
    public function getWorklistHarian(int $unitId, string $tgl): array
    {
        return $this->db->table('t_rawat_jalan rj')
            ->select('
                rj.id,
                r.id AS reg_id,
                r.no_registrasi,
                r.tgl_registrasi,
                r.penjamin,
                r.no_jkn,
                r.no_referensi,
                p.id AS pasien_id,
                p.norm,
                p.nama_pasien,
                p.tgl_lahir,
                p.jenis_kelamin,
                p.no_telp,
                rj.no_antrian,
                rj.status_emr,
                rj.keadaan_umum,
                rj.diagnosa_utama,
                rj.diagnosa_utama_nama,
                rj.tgl_selesai,
                mp.nama_pegawai AS nama_dokter,
                u.nama_poli
            ')
            ->join('t_registrasi r',    'r.id = rj.reg_id')
            ->join('pasien p',          'p.id = r.pasien_id')
            ->join('m_pegawai_dokter d','d.id = rj.dokter_id', 'left')
            ->join('m_pegawai mp',      'mp.id = d.pegawai_id', 'left')
            ->join('poliklinik u',      'u.id = rj.unit_id',   'left')
            ->where('rj.unit_id', $unitId)
            ->where('DATE(r.tgl_registrasi)', $tgl)
            ->orderBy('rj.no_antrian', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Detail pemeriksaan untuk form dokter
     */
    public function getDetailPemeriksaan(int $regId): ?array
    {
        $row = $this->db->table('t_rawat_jalan rj')
            ->select('
                rj.*,
                r.no_registrasi, r.tgl_registrasi, r.penjamin,
                r.no_jkn, r.no_referensi, r.kodebooking_jkn,
                p.id AS pasien_id, p.norm, p.nama_pasien,
                p.tgl_lahir, p.jenis_kelamin, p.nik, p.alamat,
                p.no_telp, p.no_jkn AS no_jkn_pasien,
                mp.nama_pegawai AS nama_dokter, d.kode_bpjs AS kode_dokter_bpjs,
                u.nama_poli, u.kode_bpjs AS kode_poli_bpjs
            ')
            ->join('t_registrasi r',    'r.id = rj.reg_id')
            ->join('pasien p',          'p.id = r.pasien_id')
            ->join('m_pegawai_dokter d','d.id = rj.dokter_id', 'left')
            ->join('m_pegawai mp',      'mp.id = d.pegawai_id', 'left')
            ->join('poliklinik u',      'u.id = rj.unit_id',   'left')
            ->where('rj.reg_id', $regId)
            ->get()->getRowArray();

        return $row ?: null;
    }

    public function generateQueueNumber(int $unitId): int
    {
        $today = date('Y-m-d');
        $last  = $this->db->table($this->table)
            ->selectMax('no_antrian')
            ->join('t_registrasi', 't_registrasi.id = t_rawat_jalan.reg_id')
            ->where('unit_id', $unitId)
            ->where('DATE(tgl_registrasi)', $today)
            ->get()->getRow();
        return $last ? ((int)$last->no_antrian + 1) : 1;
    }
}
