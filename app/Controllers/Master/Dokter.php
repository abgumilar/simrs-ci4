<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\DokterModel;
use App\Models\PoliklinikModel;
use App\Models\PegawaiModel;

class Dokter extends BaseController
{
    protected $dokterModel;
    protected $poliModel;
    protected $pegawaiModel;

    public function __construct()
    {
        $this->dokterModel = new DokterModel();
        $this->poliModel = new PoliklinikModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Data Dokter',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/dokter',
            'activeIcon' => 'fas fa-user-md',
            'dokter'     => $this->dokterModel->getDokterWithPoli()
        ];

        return view('master/dokter/index', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        // Eligible employees: those who aren't doctors yet OR are linked to 'dokter' users
        $pegawai = $db->table('m_pegawai')->get()->getResultArray();
        
        $data = [
            'title'      => 'Tambah Dokter',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/dokter',
            'activeIcon' => 'fas fa-user-md',
            'poliklinik' => $this->poliModel->findAll(),
            'pegawai'    => $pegawai
        ];

        return view('master/dokter/create', $data);
    }

    public function edit($id)
    {
        $dokter = $this->dokterModel->find($id);
        if (!$dokter) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();
        $pegawai = $db->table('m_pegawai')->get()->getResultArray();

        $data = [
            'title'      => 'Edit Dokter',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/dokter',
            'activeIcon' => 'fas fa-user-md',
            'poliklinik' => $this->poliModel->findAll(),
            'pegawai'    => $pegawai,
            'dokter'     => $dokter
        ];

        return view('master/dokter/edit', $data);
    }

    public function store()
    {
        $pegawaiId = $this->request->getPost('pegawai_id');
        $unitIds = $this->request->getPost('unit_ids');

        // Handle new employee creation
        if ($pegawaiId === 'new') {
            $employeeData = [
                'nama_pegawai' => $this->request->getPost('nama_baru'),
                'nik'          => $this->request->getPost('nik_baru'),
                'no_hp'        => $this->request->getPost('hp_baru'),
                'status_aktif' => true,
            ];
            
            if (!$this->pegawaiModel->save($employeeData)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membuat data pegawai baru.']);
            }
            $pegawaiId = $this->pegawaiModel->getInsertID();
        }

        $payload = [
            'pegawai_id'        => $pegawaiId,
            'specialis'         => $this->request->getPost('specialis'),
            'sip'               => $this->request->getPost('sip'),
            'kode_bpjs'         => $this->request->getPost('kode_bpjs'),
            'ihs_practitioner' => $this->request->getPost('ihs_practitioner'),
        ];

        if ($this->dokterModel->save($payload)) {
            $this->dokterModel->syncUnits($pegawaiId, $unitIds ?: []);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data Dokter berhasil ditambahkan.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambahkan dokter.']);
    }

    public function update($id)
    {
        $pegawaiId = $this->request->getPost('pegawai_id');
        $unitIds = $this->request->getPost('unit_ids');

        $payload = [
            'id'                => $id,
            'pegawai_id'        => $pegawaiId,
            'specialis'         => $this->request->getPost('specialis'),
            'sip'               => $this->request->getPost('sip'),
            'kode_bpjs'         => $this->request->getPost('kode_bpjs'),
            'ihs_practitioner' => $this->request->getPost('ihs_practitioner'),
        ];

        if ($this->dokterModel->save($payload)) {
            $this->dokterModel->syncUnits($pegawaiId, $unitIds ?: []);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data Dokter berhasil diperbarui.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui dokter.']);
    }

    public function delete($id)
    {
        if ($this->dokterModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Dokter berhasil dihapus.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus dokter.']);
    }
}
