<?php

namespace App\Controllers;

use App\Models\PendaftaranModel;
use App\Models\PoliklinikModel;

class Monitoring extends BaseController
{
    protected $pendaftaranModel;
    protected $poliModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->poliModel = new PoliklinikModel();
    }

    public function queue()
    {
        $today = date('Y-m-d');
        $poliklinik = $this->poliModel->findAll();
        
        $stats = [];
        foreach ($poliklinik as $poli) {
            $stats[] = [
                'nama_poli' => $poli['nama_poli'],
                'lokasi'    => $poli['lokasi'],
                'total'     => $this->pendaftaranModel->where('id_poli', $poli['id'])->where('DATE(tgl_daftar)', $today)->countAllResults(),
                'waiting'   => $this->pendaftaranModel->where('id_poli', $poli['id'])->where('DATE(tgl_daftar)', $today)->where('status', 'Terdaftar')->countAllResults(),
                'current'   => $this->pendaftaranModel->where('id_poli', $poli['id'])->where('DATE(tgl_daftar)', $today)->where('status', 'Diperiksa')->orderBy('no_antrian', 'DESC')->first()['no_antrian'] ?? 0
            ];
        }

        $data = [
            'title'      => 'Monitoring Antrian Real-time',
            'activeEnv'  => 'Pelayanan',
            'activeMenu' => 'monitoring/queue',
            'activeIcon' => 'fas fa-tv',
            'stats'      => $stats
        ];

        return view('monitoring/queue', $data);
    }
}
