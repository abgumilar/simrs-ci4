<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBpjsConfigTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'env' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Trial',
            ],
            'consid' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'secret' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'user_key_vclaim' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'user_key_antrean' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'base_url_vclaim' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'base_url_antrean' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('m_bpjs_config');

        // Seed default config
        $db = \Config\Database::connect();
        $db->table('m_bpjs_config')->insert([
            'env' => 'Trial',
            'consid' => '12345',
            'secret' => 'secret123',
            'base_url_vclaim' => 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev',
            'base_url_antrean' => 'https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('m_bpjs_config');
    }
}
