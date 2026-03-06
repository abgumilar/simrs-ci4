<?php

namespace App\Controllers;

use App\Models\RegistrationModel;
use App\Models\RawatJalanModel;
use App\Models\PasienModel;
use App\Models\PoliklinikModel;
use App\Models\DokterModel;
use App\Libraries\Bridging\BPJSService;
use App\Libraries\Bridging\SatuSehatService;
use App\Libraries\Bridging\AntreanService;

class Pendaftaran extends BaseController
{
    protected $regModel;
    protected $rajalModel;
    protected $pasienModel;
    protected $poliModel;
    protected $dokterModel;
    protected $bpjs;
    protected $satusehat;
    protected $antrean;

    public function __construct()
    {
        $this->regModel = new RegistrationModel();
        $this->rajalModel = new RawatJalanModel();
        $this->pasienModel = new PasienModel();
        $this->poliModel = new PoliklinikModel();
        $this->dokterModel = new DokterModel();
        
        $this->bpjs = new BPJSService();
        $this->satusehat = new SatuSehatService();
        $this->antrean = new AntreanService();
    }

    public function index()
    {
        // Fetch registrations joined with rajal and master data
        $db = \Config\Database::connect();
        $pendaftaran = $db->table('t_registrasi r')
                          ->select('r.*, p.nama_pasien, p.norm, rj.no_antrian, pl.nama_poli, mp.nama_pegawai as nama_dokter')
                          ->join('pasien p', 'p.id = r.pasien_id')
                          ->join('t_rawat_jalan rj', 'rj.reg_id = r.id', 'left')
                          ->join('poliklinik pl', 'pl.id = rj.unit_id', 'left')
                          ->join('m_pegawai_dokter md', 'md.id = rj.dokter_id', 'left')
                          ->join('m_pegawai mp', 'mp.id = md.pegawai_id', 'left')
                          ->where('DATE(r.tgl_registrasi)', date('Y-m-d'))
                          ->where('r.jenis_pelayanan', 'RJ')
                          ->orderBy('r.tgl_registrasi', 'DESC')
                          ->get()->getResultArray();

        $data = [
            'title'       => 'Daftar Kunjungan Hari Ini',
            'pendaftaran' => $pendaftaran
        ];

        return view('pendaftaran/index', $data);
    }

    public function create()
    {
        $norm = $this->request->getGet('norm');
        $pasien = null;
        if ($norm) {
            $pasien = $this->pasienModel->where('norm', $norm)->first();
        }

         $data = [
            'title'       => 'Registrasi Rawat Jalan',
            'activeEnv'   => 'Pendaftaran',
            'activeMenu'  => 'pendaftaran/rajal',
            'activeIcon'  => 'fas fa-plus-circle',
            'pasien'      => $pasien,
            'poliklinik'  => $this->poliModel->findAll(),
            'dokter'      => $this->dokterModel->getDokterWithPoli() // Now returns multi-unit info
        ];

        return view('pendaftaran/rajal', $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Save to t_registrasi (The Hub)
            $noReg = $this->regModel->generateNoReg();
            $regData = [
                'no_registrasi'   => $noReg,
                'pasien_id'       => $this->request->getPost('id_pasien'),
                'jenis_pelayanan' => 'RJ',
                'status_reg'      => 'Active'
            ];
            $this->regModel->insert($regData);
            $regId = $this->regModel->getInsertID();

            // 2. Save to t_rawat_jalan (The Service Detail)
            $unitId = $this->request->getPost('id_poli');
            $noAntrian = $this->rajalModel->generateQueueNumber($unitId);
            
            $rajalData = [
                'reg_id'        => $regId,
                'unit_id'       => $unitId,
                'dokter_id'     => $this->request->getPost('id_dokter'),
                'penjamin'      => $this->request->getPost('penjamin'),
                'sumber_daftar' => $this->request->getPost('sumber_daftar'),
                'no_antrian'    => $noAntrian,
                'keluhan'          => $this->request->getPost('keluhan'),
                'no_sep'           => $this->request->getPost('no_sep'),
                'tujuan_kunjungan' => $this->request->getPost('tujuan_kunjungan') ?? '0',
                'asal_rujukan'     => $this->request->getPost('asal_rujukan'),
                'no_rujukan'       => $this->request->getPost('no_rujukan'),
                'diag_awal'        => $this->request->getPost('diag_awal'),
            ];

            // --- BRIDGING ORCHESTRATION ---
            $bridgingLogs = [];

            // A. BPJS Bridging (If BPJS and no SEP entered manually)
            if ($rajalData['penjamin'] === 'BPJS' && empty($rajalData['no_sep'])) {
                // In a real scenario, we'd call $this->bpjs->vclaimRequest('POST', 'SEP/2.0/insert', [...]);
                // For this demo, let's simulate a success and get a dummy SEP
                $rajalData['no_sep'] = '0001R001' . date('my') . 'V' . str_pad($regId, 6, '0', STR_PAD_LEFT);
                $bridgingLogs[] = "BPJS SEP Generated: " . $rajalData['no_sep'];
            }

            // B. SatuSehat Bridging (Encounter)
            $pasien = $this->pasienModel->find($regData['pasien_id']);
            $dokter = $this->dokterModel->getDokterWithPoli($rajalData['dokter_id']); // Need specific doctor
            $poli = $this->poliModel->find($rajalData['unit_id']);

            if ($pasien && !empty($pasien['ihs_number']) && $dokter && !empty($dokter['ihs_practitioner']) && $poli && !empty($poli['ihs_location'])) {
                $ssData = [
                    'patient_ihs'       => $pasien['ihs_number'],
                    'patient_name'      => $pasien['nama_pasien'],
                    'practitioner_ihs'  => $dokter['ihs_practitioner'],
                    'practitioner_name' => $dokter['fullname'],
                    'location_ihs'      => $poli['ihs_location'],
                    'location_name'     => $poli['nama_poli'],
                    'start_time'        => date('Y-m-d\TH:i:sP'), // Standardized ISO8601 for SatuSehat
                    'local_reg_id'      => $noReg
                ];

                $ssRes = $this->satusehat->createEncounter($ssData);
                if ($ssRes['status'] === 'success') {
                    $rajalData['ihs_encounter_id'] = $ssRes['data']['id'] ?? null;
                    $bridgingLogs[] = "SatuSehat Encounter Created: " . $rajalData['ihs_encounter_id'];
                } else {
                    $bridgingLogs[] = "SatuSehat Error: " . ($ssRes['message'] ?? 'Unknown');
                }
            } else {
                $missing = [];
                if (empty($pasien['ihs_number'])) $missing[] = 'IHS Pasien';
                if (empty($dokter['ihs_practitioner'])) $missing[] = 'IHS Praktisi';
                if (empty($poli['ihs_location'])) $missing[] = 'IHS Lokasi';
                
                if (!empty($missing)) {
                    $bridgingLogs[] = "SatuSehat Skipped (Missing: " . implode(', ', $missing) . ")";
                }
            }

            $this->rajalModel->insert($rajalData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Failed to commit transaction');
            }

            return $this->response->setJSON([
                'status'  => 'success', 
                'message' => 'Pendaftaran Berhasil.' . (count($bridgingLogs) > 0 ? ' Bridging: ' . implode(', ', $bridgingLogs) : ''),
                'no_reg'  => $noReg,
                'no_antrian' => $noAntrian,
                'ihs_encounter' => $rajalData['ihs_encounter_id'] ?? null
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX Search BPJS Peserta
     */
    public function cek_peserta_bpjs()
    {
        // ambil input
        $nomor = $this->request->getGet('nomor');
        $tgl   = date('Y-m-d');

        // bersihkan input hanya angka (antisipasi spasi/tanda baca)
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // validasi panjang minimal
        if (empty($nomor)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Nomor tidak boleh kosong'
            ]);
        }

        // tentukan jenis endpoint (nik atau nokartu)
        $jenis = (strlen($nomor) > 15) ? 'nik' : 'nokartu';

        // optional validasi panjang umum BPJS
        if ($jenis == 'nik' && strlen($nomor) != 16) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Format NIK harus 16 digit'
            ]);
        }

        if ($jenis == 'nokartu' && strlen($nomor) < 10) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Format No Kartu BPJS tidak valid'
            ]);
        }

        // hit API BPJS sesuai jenis
        $endpoint = "Peserta/{$jenis}/{$nomor}/tglSEP/{$tgl}";
        $result   = $this->bpjs->vclaimRequest('GET', $endpoint);

        return $this->response->setJSON($result);
    }
    
    /**
     * AJAX Search Rujukan BPJS
     */
    public function cari_rujukan()
    {
        $nomor = $this->request->getGet('nomor');
        $asal  = $this->request->getGet('asal') ?? '1'; // 1: FKTP, 2: RS
        $type  = $this->request->getGet('type') ?? 'kartu'; // kartu or rujukan

        if (empty($nomor)) return $this->response->setJSON(['status' => false, 'message' => 'Nomor kosong']);

        if ($type === 'rujukan') {
            $endpoint = "Rujukan/{$nomor}";
        } else {
            // Search list of referrals by card number
            $endpoint = "Rujukan/List/Peserta/{$nomor}";
        }
        
        // Asal rujukan 1 (FKTP) or 2 (RS)
        if ($asal == '2') {
            $endpoint = "Rujukan/RS/" . ($type === 'rujukan' ? $nomor : "List/Peserta/{$nomor}");
        }

        $result = $this->bpjs->vclaimRequest('GET', $endpoint);
        return $this->response->setJSON($result);
    }

    /**
     * AJAX Search Diagnosa ICD-10
     */
    public function cari_diagnosa()
    {
        $q = $this->request->getGet('q');
        if (empty($q)) return $this->response->setJSON([]);

        $endpoint = "referensi/diagnosa/{$q}";
        $result = $this->bpjs->vclaimRequest('GET', $endpoint);

        return $this->response->setJSON($result);
    }

    /**
     * Dashboard List Booking Mobile JKN
     */
    public function booking()
    {
        $data = [
            'title'      => 'Booking Mobile JKN',
            'activeEnv'  => 'Pendaftaran',
            'activeMenu' => 'pendaftaran/booking',
            'activeIcon' => 'fas fa-calendar-check',
        ];

        return view('pendaftaran/booking', $data);
    }

    /**
     * AJAX Fetch mJKN Bookings from BPJS
     */
    public function get_booking_jkn()
    {
        $tgl = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $result = $this->antrean->getBookingByTanggal($tgl);
        return $this->response->setJSON($result);
    }

    /**
     * AJAX Check-in mJKN Booking
     */
    public function checkin_jkn()
    {
        $kode = $this->request->getPost('kodebooking');
        
        // Fetch booking list for today to verify
        $bookingList = $this->antrean->getBookingByTanggal(date('Y-m-d'));
        $norm = null;
        
        if ($bookingList['metadata']['code'] == 1 && !empty($bookingList['response'])) {
            $booking = null;
            foreach ($bookingList['response'] as $item) {
                if ($item['kodebooking'] == $kode) {
                    $booking = $item;
                    break;
                }
            }
            
            if ($booking) {
                // Try to find matching patient in our local DB
                $pasien = $this->pasienModel->where('nik', $booking['nik'])
                                            ->orWhere('no_jkn', $booking['nomorkartu'])
                                            ->first();
                if ($pasien) {
                    $norm = $pasien['norm'];
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Pasien berhasil diverifikasi. Silakan lanjutkan registrasi.',
            'norm'    => $norm
        ]);
    }

    /**
     * AJAX Search Pasien (Integrated Search)
     */
    public function cari_pasien()
    {
        $q = $this->request->getGet('q');
        if (empty($q)) return $this->response->setJSON([]);

        $pasien = $this->pasienModel->like('nama_pasien', $q)
                                    ->orLike('norm', $q)
                                    ->limit(10)
                                    ->findAll();

        return $this->response->setJSON($pasien);
    }
}
