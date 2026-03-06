<?php

namespace App\Controllers;

use App\Models\RawatJalanModel;
use App\Models\VitalSignModel;
use App\Models\DiagnosaModel;
use App\Models\PasienModel;
use App\Libraries\Bridging\SatuSehatService;

class RawatJalan extends BaseController
{
    protected $rajalModel;
    protected $vitalModel;
    protected $diagModel;
    protected $pasienModel;
    protected $satusehat;
    protected $db;

    public function __construct()
    {
        $this->rajalModel  = new RawatJalanModel();
        $this->vitalModel  = new VitalSignModel();
        $this->diagModel   = new DiagnosaModel();
        $this->pasienModel = new PasienModel();
        $this->satusehat   = new SatuSehatService();
        $this->db          = \Config\Database::connect();
    }

    /* ─────────────────────────────────────────────────────────────
     * WORKLIST HARIAN
     * ───────────────────────────────────────────────────────────── */

    /**
     * Halaman worklist (view dirender oleh Workspace::render_menu)
     * URL: rajal/worklist
     */
    public function worklist()
    {
        $unitId = $this->request->getGet('unit_id');
        $tgl    = $this->request->getGet('tgl') ?: date('Y-m-d');

        $units = $this->db->table('poliklinik')->get()->getResultArray();
        $daftar = $unitId ? $this->rajalModel->getWorklistHarian((int)$unitId, $tgl) : [];

        $data = [
            'title'        => 'Worklist Pemeriksaan Poli',
            'activeEnv'    => 'Rawat Jalan',
            'activeMenu'   => 'rajal/pemeriksaan',
            'activeIcon'   => 'fas fa-stethoscope',
            'units'        => $units,
            'selected_unit'=> $unitId,
            'selected_tgl' => $tgl,
            'daftar'       => $daftar,
        ];

        return view('rajal/worklist', $data);
    }

    /* ─────────────────────────────────────────────────────────────
     * AJAX: Load worklist (real-time refresh tanpa reload halaman)
     * ───────────────────────────────────────────────────────────── */
    public function get_worklist()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $unitId = (int)$this->request->getPost('unit_id');
        $tgl    = $this->request->getPost('tgl') ?: date('Y-m-d');
        $daftar = $unitId ? $this->rajalModel->getWorklistHarian($unitId, $tgl) : [];
        return $this->response->setJSON(['data' => $daftar]);
    }

    /* ─────────────────────────────────────────────────────────────
     * FORM PEMERIKSAAN DOKTER
     * ───────────────────────────────────────────────────────────── */

    /**
     * Form pemeriksaan SOAP + vital sign
     * URL: rajal/periksa/{reg_id}
     */
    public function periksa(int $regId)
    {
        $detail  = $this->rajalModel->getDetailPemeriksaan($regId);
        if (!$detail) {
            return redirect()->back()->with('error', 'Data kunjungan tidak ditemukan.');
        }

        $vitalSign = $this->vitalModel->where('reg_id', $regId)->first();
        $diagList  = $this->diagModel->getByReg($regId);

        // Hitung usia pasien
        $usia = null;
        if (!empty($detail['tgl_lahir'])) {
            $usia = (int) date_diff(date_create($detail['tgl_lahir']), date_create())->y;
        }

        $data = [
            'title'      => 'Pemeriksaan – ' . ($detail['nama_pasien'] ?? 'Pasien'),
            'activeEnv'  => 'Rawat Jalan',
            'activeMenu' => 'rajal/pemeriksaan',
            'activeIcon' => 'fas fa-notes-medical',
            'detail'     => $detail,
            'vitalSign'  => $vitalSign,
            'diagList'   => $diagList,
            'usia'       => $usia,
        ];

        return view('rajal/periksa', $data);
    }

    /* ─────────────────────────────────────────────────────────────
     * AJAX: Simpan Vital Sign (oleh perawat)
     * ───────────────────────────────────────────────────────────── */
    public function save_vital(int $regId)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $data = [
            'reg_id'                  => $regId,
            'tekanan_darah_sistole'   => $this->request->getPost('sistole') ?: null,
            'tekanan_darah_diastole'  => $this->request->getPost('diastole') ?: null,
            'nadi'                    => $this->request->getPost('nadi') ?: null,
            'suhu'                    => $this->request->getPost('suhu') ?: null,
            'respirasi'               => $this->request->getPost('respirasi') ?: null,
            'spo2'                    => $this->request->getPost('spo2') ?: null,
            'tinggi_badan'            => $this->request->getPost('tinggi_badan') ?: null,
            'berat_badan'             => $this->request->getPost('berat_badan') ?: null,
            'lingkar_kepala'          => $this->request->getPost('lingkar_kepala') ?: null,
            'lingkar_perut'           => $this->request->getPost('lingkar_perut') ?: null,
            'gcs'                     => $this->request->getPost('gcs') ?: null,
            'created_by'              => session()->get('user_id'),
        ];

        // Upsert
        $existing = $this->vitalModel->where('reg_id', $regId)->first();
        if ($existing) {
            $this->vitalModel->update($existing['id'], $data);
        } else {
            $this->vitalModel->insert($data);
        }

        // Update status EMR ke 'in-progress' jika masih draft
        $rj = $this->rajalModel->where('reg_id', $regId)->first();
        if ($rj && $rj['status_emr'] === 'draft') {
            $this->rajalModel->update($rj['id'], ['status_emr' => 'in-progress']);
        }

        $vs = $this->vitalModel->where('reg_id', $regId)->first();
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Vital sign berhasil disimpan.',
            'imt'     => $this->vitalModel->hitungImt($vs ?? $data),
        ]);
    }

    /* ─────────────────────────────────────────────────────────────
     * AJAX: Simpan SOAP (oleh dokter)
     * ───────────────────────────────────────────────────────────── */
    public function save_soap(int $regId)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $rj = $this->rajalModel->where('reg_id', $regId)->first();
        if (!$rj) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        // Diagnosa dari POST: [{kode_icd, nama, jenis}, ...]
        $diagRaw  = $this->request->getPost('diagnosa') ?? [];
        $diagList = is_string($diagRaw) ? json_decode($diagRaw, true) : $diagRaw;

        // Ambil diagnosa primer untuk field shortcut
        $primer = null;

        foreach ($diagList as $d) {
            if (($d['jenis'] ?? '') === 'primer') {
                $primer = $d;
                break;
            }
        }

        if (!$primer && !empty($diagList)) {
            $primer = $diagList[0];
        }

        $updateData = [
            // S
            'keluhan_utama'           => $this->request->getPost('keluhan_utama'),
            'riwayat_penyakit'        => $this->request->getPost('riwayat_penyakit'),
            'riwayat_penyakit_dahulu' => $this->request->getPost('riwayat_penyakit_dahulu'),
            'riwayat_alergi'          => $this->request->getPost('riwayat_alergi'),
            // O
            'keadaan_umum'            => $this->request->getPost('keadaan_umum'),
            'kesadaran'               => $this->request->getPost('kesadaran'),
            'pemeriksaan_fisik'       => $this->request->getPost('pemeriksaan_fisik'),
            // A
            'diagnosa_utama'          => $primer['kode_icd'] ?? null,
            'diagnosa_utama_nama'     => $primer['nama'] ?? null,
            'diagnosa_sekunder'       => json_encode(array_filter($diagList ?? [], fn($x) => ($x['jenis'] ?? '') === 'sekunder')),
            // P
            'terapi'                  => $this->request->getPost('terapi'),
            'edukasi'                 => $this->request->getPost('edukasi'),
            'anjuran_kontrol'         => $this->request->getPost('anjuran_kontrol'),
            'status_emr'              => 'in-progress',
        ];

        $this->rajalModel->update($rj['id'], $updateData);

        // Sync tabel diagnosa
        if (!empty($diagList)) {
            $this->diagModel->sync($regId, $diagList);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data SOAP berhasil disimpan sebagai draft.',
        ]);
    }

    /* ─────────────────────────────────────────────────────────────
     * AJAX: Selesaikan Kunjungan
     * ───────────────────────────────────────────────────────────── */
    public function selesai(int $regId)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $rj     = $this->rajalModel->where('reg_id', $regId)->first();
        $reg    = $this->db->table('t_registrasi')->where('id', $regId)->get()->getRowArray();
        $pasien = $reg ? $this->pasienModel->find($reg['pasien_id']) : null;

        if (!$rj || !$reg) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data kunjungan tidak ditemukan.']);
        }

        $statusPulang = $this->request->getPost('status_pulang') ?: 'Pulang';

        // Update status rawat jalan
        $this->rajalModel->update($rj['id'], [
            'status_emr'   => 'finished',
            'status_pulang'=> $statusPulang,
            'tgl_selesai'  => date('Y-m-d H:i:s'),
        ]);

        // Update status registrasi
        $this->db->table('t_registrasi')
            ->where('id', $regId)
            ->update(['status' => 'finished']);

        // Coba update Encounter ke SatuSehat (hanya jika ada IHS ID)
        $ihsResult = null;
        if (!empty($rj['ihs_encounter_id']) && !empty($pasien)) {
            try {
                // Fire-and-forget: update status Encounter menjadi "finished"
                $ihsResult = $this->satusehat->finishEncounter($rj['ihs_encounter_id']);
            } catch (\Throwable $e) {
                log_message('error', 'SatuSehat finishEncounter error: ' . $e->getMessage());
            }
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'Kunjungan selesai. Pasien ' . ($statusPulang) . '.',
            'ihs_sync' => $ihsResult ? 'ok' : 'skipped',
        ]);
    }

    /* ─────────────────────────────────────────────────────────────
     * AJAX: Search ICD-10
     * ───────────────────────────────────────────────────────────── */
    public function get_icd()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $q = $this->request->getGet('q');
        if (strlen($q) < 2) return $this->response->setJSON([]);

        $results = $this->db->table('m_icd10')
            ->select('kode, nama')
            ->groupStart()
                ->like('kode', $q)
                ->orLike('nama', $q)
            ->groupEnd()
            ->limit(15)
            ->get()->getResultArray();

        return $this->response->setJSON($results);
    }
}
