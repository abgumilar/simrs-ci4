<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Bridging\BPJSService;

class BpjsTools extends BaseController
{
    public function index()
    {
        // Get current config for display
        $db = \Config\Database::connect();
        $config = $db->table('m_bpjs_config')->where('is_active', true)->get()->getRowArray();

        $data = [
            'title'      => 'BPJS V-Claim Tools',
            'activeEnv'  => 'Administrator',
            'activeMenu' => 'admin/bpjs',
            'activeIcon' => 'fas fa-wrench',
            'config'     => $config
        ];

        return view('admin/bpjs_tools', $data);
    }

    public function generate_signature()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $customTimestamp = $this->request->getPost('timestamp');
        
        $db = \Config\Database::connect();
        $config = $db->table('m_bpjs_config')->where('is_active', true)->get()->getRowArray();

        if (!$config) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Config not found']);
        }

        date_default_timezone_set('UTC');
        $timestamp = $customTimestamp ? $customTimestamp : (string)time();
        
        $signature = hash_hmac('sha256', $config['consid'] . "&" . $timestamp, $config['secret'], true);
        $encodedSignature = base64_encode($signature);

        return $this->response->setJSON([
            'status'      => 'success',
            'consid'      => $config['consid'],
            'secret'      => $config['secret'],
            'timestamp'   => $timestamp,
            'signature'   => $encodedSignature,
            'url_encoded' => urlencode($encodedSignature)
        ]);
    }

    public function check_peserta()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $nomor = $this->request->getPost('nomor');
        $jenis = $this->request->getPost('jenis'); // 'nik' or 'nokartu'
        
        if (empty($nomor)) {
            return $this->response->setJSON(['metaData' => ['code' => 400, 'message' => 'Nomor wajib diisi']]);
        }

        $bpjs = new BPJSService();
        $tgl = date('Y-m-d');
        
        $endpoint = ($jenis === 'nik') 
            ? "Peserta/nik/{$nomor}/tglSEP/{$tgl}" 
            : "Peserta/nokartu/{$nomor}/tglSEP/{$tgl}";

        $result = $bpjs->vclaimRequest('GET', $endpoint);

        return $this->response->setJSON($result);
    }
}
