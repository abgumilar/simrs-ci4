<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUrlsToSatusehatConfig extends Migration
{
    public function up()
    {
        $fields = [
            'base_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'auth_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('m_satusehat_config', $fields);

        // Update existing rows with default URLs
        $this->db->query("UPDATE m_satusehat_config SET 
            base_url = 'https://api-satusehat-stg.kemkes.go.id/fhir-r4/v1', 
            auth_url = 'https://api-satusehat-stg.kemkes.go.id/oauth2/v1' 
            WHERE env = 'Sandbox'");

        $this->db->query("UPDATE m_satusehat_config SET 
            base_url = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1', 
            auth_url = 'https://api-satusehat.kemkes.go.id/oauth2/v1' 
            WHERE env = 'Production'");
    }

    public function down()
    {
        $this->forge->dropColumn('m_satusehat_config', ['base_url', 'auth_url']);
    }
}
