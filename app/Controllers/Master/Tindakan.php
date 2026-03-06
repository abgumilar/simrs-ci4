<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\TindakanModel;

class Tindakan extends BaseController
{
    protected $tindakanModel;

    public function __construct()
    {
        $this->tindakanModel = new TindakanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Tindakan | SIMRS',
            'menu'  => 'tindakan',
            'tindakan' => $this->tindakanModel->findAll()
        ];

        return view('master/tindakan/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Tindakan | SIMRS',
            'menu'  => 'tindakan',
        ];

        return view('master/tindakan/create', $data);
    }

    public function store()
    {
        $this->tindakanModel->save([
            'nama_tindakan' => $this->request->getPost('nama_tindakan'),
            'tarif'         => $this->request->getPost('tarif'),
        ]);

        return redirect()->to('/master/tindakan')->with('success', 'Data tindakan berhasil disimpan.');
    }
}
