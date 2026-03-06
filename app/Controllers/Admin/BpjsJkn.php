<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Bridging\AntreanService;
use App\Models\PasienModel;

class BpjsJkn extends BaseController
{
    protected $antrean;
    protected $pasienModel;

    public function __construct()
    {
        $this->antrean     = new AntreanService();
        $this->pasienModel = new PasienModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $config = $db->table('m_bpjs_config')->where('id', 1)->get()->getRowArray();

        $data = [
            'title'      => 'BPJS MJKN Tools',
            'activeEnv'  => 'Administrator',
            'activeMenu' => 'admin/bpjsjkn',
            'activeIcon' => 'fas fa-tools',
            'config'     => $config
        ];

        return view('admin/bpjs_jkn', $data);
    }

    /** AJAX Fetch Dashboard Per Tanggal */
    public function get_dashboard_tanggal()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $tgl   = $this->request->getPost('tanggal');
        $waktu = $this->request->getPost('waktu') ?: 'rs';
        return $this->response->setJSON($this->antrean->getDashboardTanggal($tgl, $waktu));
    }

    /** AJAX Fetch Dashboard Per Bulan */
    public function get_dashboard_bulan()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $waktu = $this->request->getPost('waktu') ?: 'rs';
        return $this->response->setJSON($this->antrean->getDashboardBulan($bulan, $tahun, $waktu));
    }

    /** AJAX Fetch Antrean Per Tanggal */
    public function get_antrean_tanggal()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $tgl = $this->request->getPost('tanggal');
        return $this->response->setJSON($this->antrean->getAntreanTanggal($tgl));
    }

    /** AJAX Fetch Antrean Per Kode Booking */
    public function get_antrean_booking()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $kode = $this->request->getPost('kodebooking');
        return $this->response->setJSON($this->antrean->getAntreanKodeBooking($kode));
    }

    /** AJAX Fetch Task Logs Per Kode Booking */
    public function get_task_logs()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $kode = $this->request->getPost('kodebooking');
        return $this->response->setJSON($this->antrean->getTaskLogs($kode));
    }

    /**
     * AJAX: Cari pasien lokal berdasarkan NIK atau No Kartu JKN
     * Dipanggil saat modal check-in dibuka untuk menampilkan data pasien
     */
    public function cari_pasien_local()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $nik     = $this->request->getPost('nik');
        $nokapst = $this->request->getPost('nokapst');

        $pasien = null;
        if ($nik) {
            $pasien = $this->pasienModel->where('nik', $nik)->first();
        }
        if (!$pasien && $nokapst) {
            $pasien = $this->pasienModel->where('no_jkn', $nokapst)->first();
        }

        if ($pasien) {
            return $this->response->setJSON(['found' => true, 'pasien' => $pasien]);
        }

        return $this->response->setJSON([
            'found'   => false,
            'message' => 'Pasien tidak ditemukan di database RS.'
        ]);
    }

    /**
     * AJAX: Proses Check-in booking mJKN → buat registrasi lokal
     * Alur: booking BPJS → cari NORM pasien → insert t_registrasi → kirim Task 1 ke BPJS
     */
    public function do_checkin()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $nik         = $this->request->getPost('nik');
        $nokapst     = $this->request->getPost('nokapst');
        $kodebooking = $this->request->getPost('kodebooking');
        $kodepoli    = $this->request->getPost('kodepoli');
        $kodedokter  = $this->request->getPost('kodedokter');
        $tanggal     = $this->request->getPost('tanggal') ?: date('Y-m-d');
        $noref       = $this->request->getPost('nomorreferensi');
        $noantrean   = $this->request->getPost('noantrean');

        // 1. Cari pasien lokal (NIK prioritas, fallback No Kartu JKN)
        $pasien = null;
        if ($nik) {
            $pasien = $this->pasienModel->where('nik', $nik)->first();
        }
        if (!$pasien && $nokapst) {
            $pasien = $this->pasienModel->where('no_jkn', $nokapst)->first();
        }

        if (!$pasien) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Pasien belum terdaftar di database RS. Daftarkan terlebih dahulu melalui Master Pasien.'
            ]);
        }

        $db = \Config\Database::connect();

        // 2. Cek apakah sudah ada registrasi untuk booking ini (idempoten)
        $existing = $db->table('t_registrasi')
            ->where('kodebooking_jkn', $kodebooking)
            ->get()->getRowArray();

        if ($existing) {
            return $this->response->setJSON([
                'status'  => 'warning',
                'message' => 'Pasien ini sudah di-check-in sebelumnya.',
                'norm'    => $pasien['norm'],
                'no_reg'  => $existing['no_registrasi'] ?? '-'
            ]);
        }

        // 3. Cari unit/poli dari kode BPJS
        $unit = $db->table('m_unit')
            ->groupStart()
                ->where('kode_bpjs', $kodepoli)
                ->orWhere('kode', $kodepoli)
            ->groupEnd()
            ->get()->getRowArray();
        $unitId = $unit['id'] ?? null;

        // 4. Cari dokter dari kode BPJS
        $dokter = $db->table('m_pegawai_dokter')
            ->where('kode_bpjs', $kodedokter)
            ->get()->getRowArray();
        $dokterId = $dokter['id'] ?? null;

        // 5. Generate no registrasi (format: Ymd + 4 digit sequence)
        $date  = date('Ymd');
        $last  = $db->table('t_registrasi')
            ->like('no_registrasi', $date, 'after')
            ->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        $seq   = $last ? (intval(substr($last['no_registrasi'], -4)) + 1) : 1;
        $noReg = $date . str_pad($seq, 4, '0', STR_PAD_LEFT);

        // 6. Insert registrasi
        $db->table('t_registrasi')->insert([
            'no_registrasi'   => $noReg,
            'pasien_id'       => $pasien['id'],
            'norm'            => $pasien['norm'],
            'unit_id'         => $unitId,
            'dokter_id'       => $dokterId,
            'tgl_registrasi'  => $tanggal,
            'jenis_kunjungan' => 1,         // Rawat Jalan
            'penjamin'        => 'BPJS',
            'no_jkn'          => $nokapst ?: ($pasien['no_jkn'] ?? null),
            'no_referensi'    => $noref,
            'kodebooking_jkn' => $kodebooking,
            'no_antrian'      => $noantrean,
            'status'          => 'checkin',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        // 7. Kirim Task 1 ke BPJS (mulai waktu tunggu admisi) — fire and forget
        $this->antrean->updateWaktuAntrean([
            'kodebooking' => $kodebooking,
            'taskid'      => 1,
            'waktu'       => date('Y-m-d H:i:s'),
            'wakturs'     => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Check-in berhasil! Pasien masuk antrian RS.',
            'norm'    => $pasien['norm'],
            'no_reg'  => $noReg,
        ]);
    }
}
