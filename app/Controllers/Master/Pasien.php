<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\PasienModel;

class Pasien extends BaseController
{
    protected $pasienModel;
    protected $satusehat;

    public function __construct()
    {
        $this->pasienModel = new PasienModel();
        $this->satusehat = new \App\Libraries\Bridging\SatuSehatService();
    }

    /**
     * AJAX Trigger to get IHS from SatuSehat
     */
    public function get_ihs($id)
    {
        $pasien = $this->pasienModel->find($id);
        if (!$pasien || empty($pasien['nik'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIK tidak ditemukan']);
        }

        $res = $this->satusehat->getPatientByNIK($pasien['nik']);
        if ($res['status'] === 'success') {
            $ihs = $res['ihs_number'];
            $this->pasienModel->update($id, ['ihs_number' => $ihs]);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'IHS ditemukan: ' . $ihs,
                'ihs' => $ihs
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => $res['message']
        ]);
    }

    public function index()
    {
        // Handle DataTables Server-Side Processing
        if ($this->request->isAJAX() && $this->request->getGet('draw')) {
            $draw   = $this->request->getGet('draw');
            $start  = $this->request->getGet('start');
            $length = $this->request->getGet('length');
            $search = $this->request->getGet('search')['value'] ?? '';
            $order  = $this->request->getGet('order');
            
            $builder = $this->pasienModel;
            
            // 1. Total records (without filtering)
            $totalRecords = $builder->countAllResults(false);
            
            // 2. Apply filtering
            if (!empty($search)) {
                $builder = $builder->groupStart()
                                   ->like('nama_pasien', $search)
                                   ->orLike('norm', $search)
                                   ->orLike('nik', $search)
                                   ->groupEnd();
            }
            
            // 3. Count filtered records
            $totalFiltered = $builder->countAllResults(false);
            
            // 4. Ordering
            if ($order && isset($order[0])) {
                $columns = ['norm', 'nama_pasien', 'nik', 'alamat'];
                $colIndex = $order[0]['column'];
                $colDir = $order[0]['dir'];
                if (isset($columns[$colIndex])) {
                    $builder->orderBy($columns[$colIndex], $colDir);
                }
            } else {
                $builder->orderBy('id', 'DESC');
            }
            
            // 5. Get paginated data
            $data = $builder->findAll($length, $start);
            
            return $this->response->setJSON([
                'draw'            => intval($draw),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data'            => $data
            ]);
        }

        $data = [
            'title'      => 'Data Master Pasien',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/pasien',
            'activeIcon' => 'fas fa-user-injured',
            'pasien'     => [], // View will load via AJAX
            'search'     => ''
        ];

        return view('master/pasien/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Registrasi Pasien Baru',
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/pasien',
            'activeIcon' => 'fas fa-user-plus'
        ];

        return view('master/pasien/create', $data);
    }

    public function edit($id)
    {
        $pasien = $this->pasienModel->find($id);
        if (!$pasien) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'      => 'Edit Data Pasien: ' . $pasien['norm'],
            'activeEnv'  => 'Data Master',
            'activeMenu' => 'master/pasien',
            'activeIcon' => 'fas fa-user-edit',
            'pasien'     => $pasien
        ];

        return view('master/pasien/edit', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        
        if (empty($data['tanggal_daftar_pertama'])) {
            $data['tanggal_daftar_pertama'] = date('Y-m-d H:i:s');
        }

        // Automatic IHS Retrieval if missing but NIK exists
        if (empty($data['ihs_number']) && !empty($data['nik'])) {
            $ihsRes = $this->satusehat->getPatientByNIK($data['nik']);
            if ($ihsRes['status'] === 'success') {
                $data['ihs_number'] = $ihsRes['ihs_number'];
            }
        }

        if ($this->pasienModel->save($data)) {
            $msg = 'Pasien berhasil didaftarkan.';
            if (!empty($data['ihs_number'])) $msg .= ' IHS Terhubung.';
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $msg,
                'ihs' => $data['ihs_number'] ?? null
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal mendaftarkan pasien.'
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;

        // Automatic IHS Retrieval if missing but NIK exists
        if (empty($data['ihs_number']) && !empty($data['nik'])) {
            $ihsRes = $this->satusehat->getPatientByNIK($data['nik']);
            if ($ihsRes['status'] === 'success') {
                $data['ihs_number'] = $ihsRes['ihs_number'];
            }
        }

        if ($this->pasienModel->save($data)) {
            $msg = 'Data pasien berhasil diperbarui.';
            if (!empty($data['ihs_number'])) $msg .= ' IHS Terupdate.';

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $msg,
                'ihs' => $data['ihs_number'] ?? null
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui data pasien.'
        ]);
    }

    public function delete($id)
    {
        if ($this->pasienModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pasien berhasil dihapus.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus pasien.'
        ]);
    }
}
