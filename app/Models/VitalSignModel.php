<?php

namespace App\Models;

use CodeIgniter\Model;

class VitalSignModel extends Model
{
    protected $table         = 't_vital_sign';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'reg_id', 'created_by',
        'tekanan_darah_sistole', 'tekanan_darah_diastole',
        'nadi', 'suhu', 'respirasi', 'spo2',
        'tinggi_badan', 'berat_badan', 'lingkar_kepala', 'lingkar_perut',
        'gcs', 'ihs_observation_ids',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Hitung IMT (Indeks Massa Tubuh)
     */
    public function hitungImt(array $vs): ?float
    {
        if (empty($vs['berat_badan']) || empty($vs['tinggi_badan'])) return null;
        $tb_m = $vs['tinggi_badan'] / 100;
        return round($vs['berat_badan'] / ($tb_m * $tb_m), 1);
    }

    /**
     * Format tekanan darah sebagai string "120/80"
     */
    public function formatTd(array $vs): string
    {
        $s = $vs['tekanan_darah_sistole'] ?? '-';
        $d = $vs['tekanan_darah_diastole'] ?? '-';
        return "{$s}/{$d}";
    }
}
