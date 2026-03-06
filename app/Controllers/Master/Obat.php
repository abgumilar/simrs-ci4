<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\ObatModel;

class Obat extends BaseController
{
    protected $obatModel;

    public function __construct()
    {
        $this->obatModel = new ObatModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Obat & Alkes | SIMRS',
            'menu'  => 'obat',
            'obat'  => $this->obatModel->findAll()
        ];

        return view('master/obat/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Obat | SIMRS',
            'menu'  => 'obat',
        ];

        return view('master/obat/create', $data);
    }

    public function store()
    {
        $this->obatModel->save([
            'kode_obat'  => $this->request->getPost('kode_obat'),
            'nama_obat'  => $this->request->getPost('nama_obat'),
            'satuan'     => $this->request->getPost('satuan'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'stok'       => $this->request->getPost('stok'),
        ]);

        return redirect()->to('/master/obat')->with('success', 'Data obat berhasil disimpan.');
    }
}
