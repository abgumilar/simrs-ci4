<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\PoliklinikModel;

class Poliklinik extends BaseController
{
    protected $poliModel;

    public function __construct()
    {
        $this->poliModel = new PoliklinikModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Data Poliklinik',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/poliklinik',
            'activeIcon' => 'fas fa-clinic-medical',
            'poliklinik' => $this->poliModel->findAll()
        ];

        return view('master/poliklinik/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Poliklinik',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/poliklinik',
            'activeIcon' => 'fas fa-clinic-medical'
        ];

        return view('master/poliklinik/create', $data);
    }

    public function edit($id)
    {
        $poli = $this->poliModel->find($id);
        if (!$poli) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'      => 'Edit Poliklinik',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/poliklinik',
            'activeIcon' => 'fas fa-clinic-medical',
            'poli'       => $poli
        ];

        return view('master/poliklinik/edit', $data);
    }

    public function store()
    {
        $data = [
            'nama_poli'     => $this->request->getPost('nama_poli'),
            'lokasi'        => $this->request->getPost('lokasi'),
            'kode_bpjs'     => $this->request->getPost('kode_bpjs'),
            'ihs_location'  => $this->request->getPost('ihs_location'),
        ];

        if ($this->poliModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data Poliklinik berhasil ditambahkan.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan poliklinik.'
        ]);
    }

    public function update($id)
    {
        $data = [
            'id'            => $id,
            'nama_poli'     => $this->request->getPost('nama_poli'),
            'lokasi'        => $this->request->getPost('lokasi'),
            'kode_bpjs'     => $this->request->getPost('kode_bpjs'),
            'ihs_location'  => $this->request->getPost('ihs_location'),
        ];

        if ($this->poliModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data Poliklinik berhasil diperbarui.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui poliklinik.'
        ]);
    }

    public function delete($id)
    {
        if ($this->poliModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Poliklinik berhasil dihapus.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus poliklinik.'
        ]);
    }
}
