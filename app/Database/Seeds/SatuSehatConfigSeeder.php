<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SatuSehatConfigSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'env'             => 'Trial',
            'organization_id' => '100025441', // Dummy Trial Org ID
            'client_id'       => 'your_client_id_here',
            'client_secret'   => 'your_client_secret_here',
            'auth_token'      => null,
            'token_expires'   => null,
        ];

        $this->db->table('m_satusehat_config')->insert($data);
    }
}
