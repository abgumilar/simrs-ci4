<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupRegistrasiTablePart2 extends Migration
{
    public function up()
    {
        // User notice unit_id and dokter_id are still in t_registrasi but unused (moved to t_rawat_jalan)
        $fields = $this->db->getFieldNames('t_registrasi');
        
        if (in_array('unit_id', $fields)) {
            $this->forge->dropColumn('t_registrasi', 'unit_id');
        }
        
        if (in_array('dokter_id', $fields)) {
            $this->forge->dropColumn('t_registrasi', 'dokter_id');
        }
    }

    public function down()
    {
        $fields = [
            'unit_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'dokter_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ];
        $this->forge->addColumn('t_registrasi', $fields);
    }
}
