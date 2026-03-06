<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuJknSeeder extends Seeder
{
    public function run()
    {
        // 1. Tambahkan Permission reg.booking jika belum ada
        $checkPerm = $this->db->table('permissions')->where('name', 'reg.booking')->get()->getRow();
        if (!$checkPerm) {
            $this->db->table('permissions')->insert([
                'name' => 'reg.booking',
                'description' => 'Akses Booking Mobile JKN'
            ]);
            $permId = $this->db->insertID();
            
            // Berikan akses otomatis ke role Admin jika ada
            $adminRole = $this->db->table('roles')->where('name', 'Admin')->get()->getRow();
            if ($adminRole) {
                $this->db->table('role_permissions')->insert([
                    'role_id' => $adminRole->id,
                    'permission_id' => $permId
                ]);
            }
        }

        // 2. Tambahkan Menu Booking JKN jika belum ada
        $checkMenu = $this->db->table('menus')->where('url', 'pendaftaran/booking')->get()->getRow();
        if (!$checkMenu) {
            $this->db->table('menus')->insert([
                'environment' => 'Registrasi',
                'icon'        => 'fas fa-user-plus',
                'item_icon'   => 'fas fa-calendar-check',
                'title'       => 'Booking Mobile JKN',
                'url'         => 'pendaftaran/booking',
                'permission'  => 'reg.booking',
                'sequence'    => 5 // Urutan diletakkan setelah IGD
            ]);
        }

        // 3. Tambahkan Permission admin.bpjsjkn jika belum ada
        $checkPermAdmin = $this->db->table('permissions')->where('name', 'admin.bpjsjkn')->get()->getRow();
        if (!$checkPermAdmin) {
            $this->db->table('permissions')->insert([
                'name' => 'admin.bpjsjkn',
                'description' => 'Akses BPJS MJKN Tools'
            ]);
            $permIdAdmin = $this->db->insertID();
            
            // Berikan akses otomatis ke role Admin jika ada
            if (isset($adminRole) && $adminRole) {
                $this->db->table('role_permissions')->insert([
                    'role_id' => $adminRole->id,
                    'permission_id' => $permIdAdmin
                ]);
            }
        }

        // 4. Tambahkan Menu BPJS MJKN Tools untuk Administrator
        $checkMenuAdmin = $this->db->table('menus')->where('url', 'admin/bpjsjkn')->get()->getRow();
        if (!$checkMenuAdmin) {
            $this->db->table('menus')->insert([
                'environment' => 'Administrator',
                'icon'        => 'fas fa-cogs',
                'item_icon'   => 'fas fa-tools',
                'title'       => 'BPJS MJKN Tools',
                'url'         => 'admin/bpjsjkn',
                'permission'  => 'admin.bpjsjkn',
                'sequence'    => 0 // 
            ]);
        }

        echo "Menu Booking Mobile JKN berhasil ditambahkan secara aman.\n";
    }
}
