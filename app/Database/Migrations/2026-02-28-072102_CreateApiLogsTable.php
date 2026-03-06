<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'service_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'endpoint' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'method' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
            ],
            'request_payload' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'response_payload' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_code' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'execution_time' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('service_name');
        $this->forge->addKey('created_at');
        $this->forge->createTable('t_api_logs');
    }

    public function down()
    {
        $this->forge->dropTable('t_api_logs');
    }
}
