<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Workspace extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Fetch all environments/modules for the launcher (PostgreSQL compliant GROUP BY)
        $modules = $db->table('menus')
                      ->select('environment, icon, MIN(id) as sort_id')
                      ->groupBy('environment, icon')
                      ->orderBy('sort_id', 'ASC')
                      ->get()->getResultArray();

        $data = [
            'title'   => 'SIMRS Workspace',
            'modules' => $modules,
            'user'    => session()->get()
        ];

        return view('workspace/shell', $data);
    }

    public function render_menu(...$paths)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Direct access not allowed');
        }

        // Combine URI segments and strip query string for file existence check
        $fullPath = implode('/', $paths);
        $cleanPath = explode('?', $fullPath)[0];
        $viewPath = $cleanPath;
        
        // Robustness: If path is a directory, try appending /index
        if (is_dir(APPPATH . 'Views/' . $viewPath) && is_file(APPPATH . 'Views/' . $viewPath . '/index.php')) {
            $viewPath .= '/index';
        }

        // Logic for parameter-based views (e.g. edit/12)
        // If file doesn't exist but has a num at the end, strip it
        if (!is_file(APPPATH . 'Views/' . $viewPath . '.php')) {
            $parts = explode('/', $viewPath);
            if (count($parts) > 1 && is_numeric(end($parts))) {
                $id = array_pop($parts);
                $viewPath = implode('/', $parts);
                $data['id'] = $id; // Pass ID to the view
            }
        }

        $data = ['title' => 'Menu'];

        // Logic to fetch data based on the requested path
        if (strpos($fullPath, 'pendaftaran') !== false) {
            $db = \Config\Database::connect();
            if (strpos($viewPath, 'rajal') !== false || strpos($viewPath, 'ranap') !== false || strpos($viewPath, 'igd') !== false || strpos($viewPath, 'create') !== false) {
                $norm = $this->request->getGet('norm');
                $pasienModel = new \App\Models\PasienModel();
                $data['pasien'] = $norm ? $pasienModel->where('norm', $norm)->first() : null;
                $data['poliklinik'] = (new \App\Models\PoliklinikModel())->findAll();
                $data['dokter'] = (new \App\Models\DokterModel())->getDokterWithPoli();
                $data['title'] = 'Pendaftaran Baru';
                
                if (strpos($viewPath, 'rajal') !== false) $data['title'] = 'Pendaftaran Rawat Jalan';
                if (strpos($viewPath, 'ranap') !== false) $data['title'] = 'Pendaftaran Rawat Inap';
                if (strpos($viewPath, 'igd') !== false) $data['title'] = 'Pendaftaran IGD';
            } else {
                $data['pendaftaran'] = $db->table('t_registrasi r')
                                          ->select('r.*, p.nama_pasien, p.norm, rj.no_antrian, rj.sumber_daftar, pl.nama_poli, mp.nama_pegawai as nama_dokter')
                                          ->join('pasien p', 'p.id = r.pasien_id')
                                          ->join('t_rawat_jalan rj', 'rj.reg_id = r.id', 'left')
                                          ->join('poliklinik pl', 'pl.id = rj.unit_id', 'left')
                                          ->join('m_pegawai_dokter md', 'md.id = rj.dokter_id', 'left')
                                          ->join('m_pegawai mp', 'mp.id = md.pegawai_id', 'left')
                                          ->where('DATE(r.tgl_registrasi)', date('Y-m-d'))
                                          ->orderBy('r.tgl_registrasi', 'DESC')
                                          ->get()->getResultArray();
                $data['title'] = 'Daftar Kunjungan Pasien';
            }
        } elseif (strpos($viewPath, 'master/pasien') !== false) {
            $pasienModel = new \App\Models\PasienModel();
            if (strpos($viewPath, 'edit') !== false && isset($data['id'])) {
                $data['pasien'] = $pasienModel->find($data['id']);
                $data['title'] = 'Edit Pasien: ' . $data['pasien']['norm'];
            } elseif (strpos($viewPath, 'create') !== false) {
                $data['title'] = 'Registrasi Pasien Baru';
            } else {
                // DON'T load data here! It crashes with 356k rows.
                // The DataTable in the view will load it via AJAX.
                $data['pasien'] = []; 
                $data['title'] = 'Data Master Pasien';
            }
        } elseif (strpos($viewPath, 'master/dokter') !== false) {
            $dokterModel = new \App\Models\DokterModel();
            $db = \Config\Database::connect();
            if (strpos($viewPath, 'edit') !== false && isset($data['id'])) {
                $data['dokter'] = $dokterModel->find($data['id']);
                $data['users'] = $db->table('users')->where('role', 'dokter')->get()->getResultArray();
                $data['poliklinik'] = $db->table('poliklinik')->get()->getResultArray();
                $data['title'] = 'Edit Praktisi';
            } elseif (strpos($viewPath, 'create') !== false) {
                $data['users'] = $db->table('users')->where('role', 'dokter')->get()->getResultArray();
                $data['poliklinik'] = $db->table('poliklinik')->get()->getResultArray();
                $data['title'] = 'Tambah Praktisi';
            } else {
                $data['dokter'] = $dokterModel->getDokterWithPoli();
                $data['title'] = 'Master Praktisi';
            }
        } elseif (strpos($viewPath, 'master/poliklinik') !== false) {
            $poliModel = new \App\Models\PoliklinikModel();
            if (strpos($viewPath, 'edit') !== false && isset($data['id'])) {
                $data['poli'] = $poliModel->find($data['id']);
                $data['title'] = 'Edit Poliklinik';
            } elseif (strpos($viewPath, 'create') !== false) {
                $data['title'] = 'Tambah Poliklinik';
            } else {
                $data['poliklinik'] = $poliModel->findAll();
                $data['title'] = 'Master Poliklinik';
            }
        } elseif (strpos($viewPath, 'monitoring/queue') !== false) {
            $poliModel = new \App\Models\PoliklinikModel();
            $today = date('Y-m-d');
            $poliklinik = $poliModel->findAll();
            $db = \Config\Database::connect();
            
            $stats = [];
            foreach ($poliklinik as $poli) {
                $total = $db->table('t_rawat_jalan rj')->join('t_registrasi r', 'r.id = rj.reg_id')->where('rj.unit_id', $poli['id'])->where('DATE(r.tgl_registrasi)', $today)->countAllResults();
                $waiting = $db->table('t_rawat_jalan rj')->join('t_registrasi r', 'r.id = rj.reg_id')->where('rj.unit_id', $poli['id'])->where('DATE(r.tgl_registrasi)', $today)->where('r.status_reg', 'Active')->countAllResults();
                $currentRow = $db->table('t_rawat_jalan rj')->join('t_registrasi r', 'r.id = rj.reg_id')->where('rj.unit_id', $poli['id'])->where('DATE(r.tgl_registrasi)', $today)->orderBy('rj.no_antrian', 'DESC')->get()->getRow();

                $stats[] = [
                    'nama_poli' => $poli['nama_poli'],
                    'lokasi'    => $poli['lokasi'],
                    'total'     => $total,
                    'waiting'   => $waiting,
                    'current'   => $currentRow ? $currentRow->no_antrian : 0
                ];
            }
            $data['stats'] = $stats;
            $data['title'] = 'Monitoring Antrian Real-time';
        } elseif (strpos($viewPath, 'rajal/pemeriksaan') !== false) {
            $db = \Config\Database::connect();
            $rajalModel = new \App\Models\RawatJalanModel();
            $unitId = $this->request->getGet('unit_id');
            $tgl    = $this->request->getGet('tgl') ?: date('Y-m-d');
            
            $data['units'] = $db->table('poliklinik')->get()->getResultArray();
            $data['daftar'] = $unitId ? $rajalModel->getWorklistHarian((int)$unitId, $tgl) : [];
            $data['selected_unit'] = $unitId;
            $data['tgl'] = $tgl;
            $data['title'] = 'Pemeriksaan Poli';
            $data['activeEnv'] = 'Rawat Jalan';
            $data['activeMenu'] = 'rajal/pemeriksaan';
            
            $viewPath = 'rajal/worklist';
        } elseif (strpos($fullPath, 'admin/bpjsjkn') !== false) {
            $db = \Config\Database::connect();
            $data['config'] = $db->table('m_bpjs_config')->where('id', 1)->get()->getRowArray();
            $data['title'] = 'BPJS MJKN Tools';
            $viewPath = 'admin/bpjs_jkn';
        } elseif (strpos($fullPath, 'admin/bpjs') !== false) {
            $db = \Config\Database::connect();
            $data['config'] = $db->table('m_bpjs_config')->where('id', 1)->get()->getRowArray();
            $data['title'] = 'BPJS V-Claim Tools';
            $viewPath = 'admin/bpjs_tools'; // Explicitly set the view path
        }

        if (!is_file(APPPATH . 'Views/' . $viewPath . '.php')) {
            return '<div class="p-5 text-center text-muted">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h5>Halaman tidak ditemukan</h5>
                        <p>File <code>app/Views/' . $viewPath . '.php</code> belum dibuat.</p>
                    </div>';
        }

        return view($viewPath, $data);
    }

    public function get_sidebar($env)
    {
        if (!$this->request->isAJAX()) return "";
        
        helper('auth');
        $menus = get_menus($env);
        
        $html = '';
        foreach ($menus as $menu) {
            $icon = $menu['item_icon'] ?? $menu['icon'];
            $html .= '<a href="javascript:void(0)" 
                         class="nav-link sidebar-item" 
                         data-url="' . $menu['url'] . '" 
                         data-title="' . $menu['title'] . '">
                        <i class="' . $icon . '"></i>
                        <span>' . $menu['title'] . '</span>
                      </a>';
        }
        return $html;
    }
}
