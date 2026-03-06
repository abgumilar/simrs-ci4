<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_pasien', 'id_poli', 'tgl_daftar', 
        'no_antrian', 'status', 'keluhan'
    ];

    protected $useTimestamps = true;

    public function getListPendaftaran()
    {
        return $this->select('pendaftaran.*, pasien.norm, pasien.nama_pasien, poliklinik.nama_poli')
                    ->join('pasien', 'pasien.id = pendaftaran.id_pasien')
                    ->join('poliklinik', 'poliklinik.id = pendaftaran.id_poli')
                    ->orderBy('pendaftaran.id', 'DESC')
                    ->findAll();
    }

    public function generateQueueNumber($idPoli)
    {
        $today = date('Y-m-d');
        $lastQueue = $this->where('id_poli', $idPoli)
                          ->where('DATE(tgl_daftar)', $today)
                          ->orderBy('no_antrian', 'DESC')
                          ->first();

        return $lastQueue ? $lastQueue['no_antrian'] + 1 : 1;
    }
}
