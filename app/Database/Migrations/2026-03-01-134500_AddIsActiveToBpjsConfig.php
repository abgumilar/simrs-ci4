<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToBpjsConfig extends Migration
{
    public function up()
    {
        $this->forge->addColumn('m_bpjs_config', [
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'after'   => 'env',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('m_bpjs_config', 'is_active');
    }
}
