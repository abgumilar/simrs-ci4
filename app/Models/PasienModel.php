<?php

namespace App\Models;

use CodeIgniter\Model;

class PasienModel extends Model
{
    protected $table            = 'pasien';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'norm', 'nik', 'no_jkn', 'ihs_number', 'nama_pasien', 
        'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'alamat', 
        'rt', 'rw', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'kode_pos',
        'no_telp', 'email', 'agama', 'status_perkawinan', 'pekerjaan', 
        'pendidikan', 'kewarganegaraan', 'tanggal_daftar_pertama', 
        'tanggal_kunjungan_terakhir', 'is_active', 'is_deceased', 'tanggal_meninggal'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateNorm'];

    protected function generateNorm(array $data)
    {
        // Get the latest NRM
        $lastPasien = $this->orderBy('id', 'DESC')->first();
        
        if (!$lastPasien || empty($lastPasien['norm'])) {
            $nextNorm = 1;
        } else {
            // Remove dashes and increment
            $lastNumber = (int) str_replace('-', '', $lastPasien['norm']);
            $nextNorm = $lastNumber + 1;
        }

        // Format as 00-00-01
        $formattedNorm = str_pad($nextNorm, 6, '0', STR_PAD_LEFT);
        $data['data']['norm'] = implode('-', str_split($formattedNorm, 2));

        return $data;
    }
}
