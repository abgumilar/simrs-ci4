<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToSatusehatConfig extends Migration
{
    public function up()
    {
        $fields = [
            'is_active' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
        ];
        $this->forge->addColumn('m_satusehat_config', $fields);

        // Set Sandbox as default active for safety
        $this->db->query("UPDATE m_satusehat_config SET is_active = true WHERE env = 'Sandbox'");
    }

    public function down()
    {
        $this->forge->dropColumn('m_satusehat_config', 'is_active');
    }
}
