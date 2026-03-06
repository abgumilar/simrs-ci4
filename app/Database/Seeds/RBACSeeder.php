<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RBACSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('TRUNCATE TABLE user_roles CASCADE');
        $this->db->table('role_permissions')->truncate();
        $this->db->table('users')->where('id >', 0)->delete();
        $this->db->table('roles')->where('id >', 0)->delete();
        $this->db->table('permissions')->where('id >', 0)->delete();
        $this->db->table('menus')->where('id >', 0)->delete();

        // 1. ROLES
        $roleList = ['Admin', 'Pendaftaran', 'Perawat', 'Dokter', 'Farmasi', 'Kasir', 'Laboran', 'Radiografer', 'Keuangan', 'Logistik'];
        foreach ($roleList as $r) {
            $this->db->table('roles')->insert(['name' => $r, 'description' => 'Role ' . $r]);
        }

        // 2. COMPREHENSIVE MODULES & MENUS (Restoring 20+ Environments)
        $menuData = [
            // Registrasi (Focus)
            ['env' => 'Registrasi', 'env_icon' => 'fas fa-user-plus', 'title' => 'Dashboard Registrasi', 'url' => 'pendaftaran/index', 'icon' => 'fas fa-columns', 'perm' => 'reg.dashboard'],
            ['env' => 'Registrasi', 'env_icon' => 'fas fa-user-plus', 'title' => 'Pendaftaran Rawat Jalan', 'url' => 'pendaftaran/rajal', 'icon' => 'fas fa-walking', 'perm' => 'reg.rajal'],
            ['env' => 'Registrasi', 'env_icon' => 'fas fa-user-plus', 'title' => 'Pendaftaran Rawat Inap', 'url' => 'pendaftaran/ranap', 'icon' => 'fas fa-bed', 'perm' => 'reg.ranap'],
            ['env' => 'Registrasi', 'env_icon' => 'fas fa-user-plus', 'title' => 'Pendaftaran IGD', 'url' => 'pendaftaran/igd', 'icon' => 'fas fa-ambulance', 'perm' => 'reg.igd'],
            ['env' => 'Registrasi', 'env_icon' => 'fas fa-user-plus', 'title' => 'Booking Mobile JKN', 'url' => 'pendaftaran/booking', 'icon' => 'fas fa-calendar-check', 'perm' => 'reg.booking'],
            ['env' => 'Registrasi', 'env_icon' => 'fas fa-user-plus', 'title' => 'Antrian & Monitoring', 'url' => 'monitoring/queue', 'icon' => 'fas fa-tv', 'perm' => 'reg.monitor'],
            
            // Data Master (Focus)
            ['env' => 'Data Master', 'env_icon' => 'fas fa-database', 'title' => 'Master Pasien', 'url' => 'master/pasien', 'icon' => 'fas fa-user-injured', 'perm' => 'master.pasien'],
            ['env' => 'Data Master', 'env_icon' => 'fas fa-database', 'title' => 'Master Poliklinik', 'url' => 'master/poliklinik', 'icon' => 'fas fa-clinic-medical', 'perm' => 'master.poli'],
            ['env' => 'Data Master', 'env_icon' => 'fas fa-database', 'title' => 'Master Dokter', 'url' => 'master/dokter', 'icon' => 'fas fa-user-md', 'perm' => 'master.dokter'],

            // Rawat Jalan
            ['env' => 'Rawat Jalan', 'env_icon' => 'fas fa-walking', 'title' => 'Daftar Antrian Rajal', 'url' => 'rajal/antrian', 'icon' => 'fas fa-list-ol', 'perm' => 'rajal.antrian'],
            ['env' => 'Rawat Jalan', 'env_icon' => 'fas fa-walking', 'title' => 'Pemeriksaan Poli', 'url' => 'rajal/pemeriksaan', 'icon' => 'fas fa-user-md', 'perm' => 'rajal.view'],
            
            // Rawat Inap
            ['env' => 'Rawat Inap', 'env_icon' => 'fas fa-bed', 'title' => 'Status Kamar', 'url' => 'ranap/kamar', 'icon' => 'fas fa-door-open', 'perm' => 'ranap.kamar'],
            ['env' => 'Rawat Inap', 'env_icon' => 'fas fa-bed', 'title' => 'Daftar Pasien Ranap', 'url' => 'ranap/pasien', 'icon' => 'fas fa-user-injured', 'perm' => 'ranap.view'],
            
            // IGD
            ['env' => 'IGD', 'env_icon' => 'fas fa-ambulance', 'title' => 'Triage IGD', 'url' => 'igd/triage', 'icon' => 'fas fa-heartbeat', 'perm' => 'igd.triage'],
            
            // Rekam Medis Elektronik (EMR)
            ['env' => 'EMR', 'env_icon' => 'fas fa-file-medical-alt', 'title' => 'CPPT Elektronik', 'url' => 'emr/cppt', 'icon' => 'fas fa-notes-medical', 'perm' => 'emr.cppt'],
            
            // Keperawatan
            ['env' => 'Keperawatan', 'env_icon' => 'fas fa-user-nurse', 'title' => 'Asesmen Keperawatan', 'url' => 'perawat/asesmen', 'icon' => 'fas fa-clipboard-check', 'perm' => 'perawat.asesmen'],
            
            // Farmasi
            ['env' => 'Farmasi', 'env_icon' => 'fas fa-pills', 'title' => 'E-Resep Masuk', 'url' => 'farmasi/resep', 'icon' => 'fas fa-file-prescription', 'perm' => 'far.resep'],
            ['env' => 'Farmasi', 'env_icon' => 'fas fa-pills', 'title' => 'Stok Obat', 'url' => 'farmasi/stok', 'icon' => 'fas fa-warehouse', 'perm' => 'far.stok'],
            
            // Laboratorium
            ['env' => 'Laboratorium', 'env_icon' => 'fas fa-flask', 'title' => 'Order Lab', 'url' => 'lab/order', 'icon' => 'fas fa-vial', 'perm' => 'lab.order'],
            
            // Radiologi
            ['env' => 'Radiologi', 'env_icon' => 'fas fa-x-ray', 'title' => 'Order Radiologi', 'url' => 'rad/order', 'icon' => 'fas fa-radiation', 'perm' => 'rad.order'],
            
            // Kasir & Billing
            ['env' => 'Kasir', 'env_icon' => 'fas fa-file-invoice-dollar', 'title' => 'Billing Pembayaran', 'url' => 'billing/index', 'icon' => 'fas fa-cash-register', 'perm' => 'bill.pay'],
            
            // Keuangan
            ['env' => 'Keuangan', 'env_icon' => 'fas fa-calculator', 'title' => 'Jurnal Umum', 'url' => 'keuangan/jurnal', 'icon' => 'fas fa-book', 'perm' => 'fin.jurnal'],
            
            // Rekam Medis
            ['env' => 'Rekam Medis', 'env_icon' => 'fas fa-folder-open', 'title' => 'Coding ICD-10', 'url' => 'rm/coding', 'icon' => 'fas fa-tags', 'perm' => 'rm.coding'],
            
            // CSSD
            ['env' => 'CSSD', 'env_icon' => 'fas fa-shuttle-space', 'title' => 'Sterilisasi Alat', 'url' => 'cssd/steril', 'icon' => 'fas fa-soap', 'perm' => 'cssd.view'],
            
            // IPSRS
            ['env' => 'IPSRS', 'env_icon' => 'fas fa-tools', 'title' => 'Maintenance Asset', 'url' => 'ipsrs/mainten', 'icon' => 'fas fa-wrench', 'perm' => 'ipsrs.view'],
            
            // Logistik
            ['env' => 'Logistik', 'env_icon' => 'fas fa-boxes', 'title' => 'Stok Barang Umum', 'url' => 'logistik/stok', 'icon' => 'fas fa-box', 'perm' => 'log.stok'],
            
            // SDM
            ['env' => 'SDM', 'env_icon' => 'fas fa-users', 'title' => 'Data Karyawan', 'url' => 'sdm/karyawan', 'icon' => 'fas fa-user-tie', 'perm' => 'sdm.view'],
            
            // Administrator
            ['env' => 'Administrator', 'env_icon' => 'fas fa-cogs', 'title' => 'User Management', 'url' => 'admin/users', 'icon' => 'fas fa-users-cog', 'perm' => 'admin.users'],
            ['env' => 'Administrator', 'env_icon' => 'fas fa-cogs', 'title' => 'BPJS V-Claim Tools', 'url' => 'admin/bpjs', 'icon' => 'fas fa-wrench', 'perm' => 'admin.bpjs'],
        ];

        foreach ($menuData as $m) {
            $this->db->table('permissions')->ignore(true)->insert([
                'name' => $m['perm'], 
                'description' => 'Akses ' . $m['title']
            ]);

            $this->db->table('menus')->insert([
                'environment' => $m['env'],
                'icon'        => $m['env_icon'],
                'item_icon'   => $m['icon'],
                'title'       => $m['title'],
                'url'         => $m['url'],
                'permission'  => $m['perm'],
                'sequence'    => 0
            ]);
        }

        // 3. MASTER PROFESI SEEDING
        $this->db->query('TRUNCATE TABLE m_profesi CASCADE');
        $profesiList = [
            ['nama_profesi' => 'Manajemen/Direksi'],
            ['nama_profesi' => 'Dokter Umum'],
            ['nama_profesi' => 'Dokter Spesialis'],
            ['nama_profesi' => 'Perawat'],
            ['nama_profesi' => 'Bidan'],
            ['nama_profesi' => 'Apoteker'],
            ['nama_profesi' => 'Asisten Apoteker'],
            ['nama_profesi' => 'Analis Kesehatan / Laboran'],
            ['nama_profesi' => 'Radiografer'],
            ['nama_profesi' => 'Rekam Medis'],
            ['nama_profesi' => 'Petugas Administrasi / IT'],
            ['nama_profesi' => 'Kasir / Keuangan'],
        ];
        $this->db->table('m_profesi')->insertBatch($profesiList);

        // Fetch ID for 'Petugas Administrasi / IT'
        $adminProfesiId = $this->db->table('m_profesi')->where('nama_profesi', 'Petugas Administrasi / IT')->get()->getRow()->id;

        // 4. SEED ADMIN MASTER PEGAWAI
        $this->db->query('TRUNCATE TABLE m_pegawai CASCADE');
        $this->db->table('m_pegawai')->insert([
            'nik'          => '3207123456780001', // Example NIK Admin
            'nama_pegawai' => 'Administrator SIMRS',
            'no_hp'        => '081234567890',
            'profesi_id'   => $adminProfesiId,
            'jabatan'      => 'Kepala Instalasi IT',
            'status_aktif' => true,
            'created_at'   => date('Y-m-d H:i:s')
        ]);
        $pegawaiId = $this->db->insertID();

        // 5. ADMIN USER CREATION (Linked to m_pegawai)
        $password = password_hash('password123', PASSWORD_DEFAULT);
        $this->db->table('users')->insert([
            'pegawai_id' => $pegawaiId, // FK to m_pegawai
            'username'   => 'admin',
            'password'   => $password,
            'fullname'   => 'Administrator SIMRS', // Legacy, can be optionally phased out later
            'role'       => 'admin',
            'active'     => true
        ]);
        $userId = $this->db->insertID();
        
        // Assign Role
        $adminRole = $this->db->table('roles')->where('name', 'Admin')->get()->getRow();
        $this->db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $adminRole->id]);

        // Assign All Permissions to Admin Role
        $allPerms = $this->db->table('permissions')->get()->getResultArray();
        foreach ($allPerms as $p) {
            $this->db->table('role_permissions')->insert(['role_id' => $adminRole->id, 'permission_id' => $p['id']]);
        }

        // 4. SAMPLE DOKTER (FOR TESTING)
        $this->db->table('m_pegawai')->insert([
            'nama_pegawai' => 'dr. Budi Santoso, Sp.A',
            'status_aktif' => true,
            'profesi_id'   => $this->db->table('m_profesi')->where('nama_profesi', 'Dokter Spesialis')->get()->getRow()->id ?? null
        ]);
        $pegawaiDokterId = $this->db->insertID();

        $this->db->table('users')->insert([
            'pegawai_id' => $pegawaiDokterId,
            'username'   => 'dr.budi',
            'password'   => password_hash('password123', PASSWORD_DEFAULT),
            'fullname'   => 'dr. Budi Santoso, Sp.A',
            'role'       => 'dokter',
            'active'     => 't'
        ]);

        $this->db->table('m_pegawai_dokter')->insert([
            'pegawai_id'       => $pegawaiDokterId,
            'specialis'        => 'Spesialis Anak',
            'sip'              => '445/882/SIP/I/2026',
            'kode_bpjs'        => '998877',
            'ihs_practitioner' => 'P00012345678',
            'created_at'       => date('Y-m-d H:i:s')
        ]);
        $dokterTableId = $this->db->insertID();

        // Get first poliklinik and link via junction table
        $poli = $this->db->table('poliklinik')->get()->getRow();
        if ($poli) {
            $this->db->table('m_pegawai_unit')->insert([
                'pegawai_id' => $pegawaiDokterId,
                'unit_id'    => $poli->id
            ]);
        }

        // 6. GRANULAR PERMISSIONS FOR PASIEN & ROLE ASSIGNMENTS
        $granularPerms = [
            'master.pasien.view'   => 'Akses Lihat Master Pasien',
            'master.pasien.create' => 'Akses Tambah Master Pasien',
            'master.pasien.edit'   => 'Akses Edit Master Pasien',
            'master.pasien.delete' => 'Akses Hapus Master Pasien',
        ];

        foreach ($granularPerms as $name => $desc) {
            $this->db->table('permissions')->insert(['name' => $name, 'description' => $desc]);
            $permId = $this->db->insertID();

            // Auto-assign to Admin (since they were added after the 'all perms' loop)
            $this->db->table('role_permissions')->insert(['role_id' => $adminRole->id, 'permission_id' => $permId]);

            // Assign View & Create to Pendaftaran
            if ($name == 'master.pasien.view' || $name == 'master.pasien.create') {
                $pendaftaranRole = $this->db->table('roles')->where('name', 'Pendaftaran')->get()->getRow();
                if ($pendaftaranRole) {
                    $this->db->table('role_permissions')->insert(['role_id' => $pendaftaranRole->id, 'permission_id' => $permId]);
                }
            }
        }
    }
}
