<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupRegistrasiTable extends Migration
{
    public function up()
    {
        // Drop duplicate columns from AddMjknFieldsToRegistrasi and keep the normalized ones
        // Kita juga pastikan foreign key / index tidak menghambat drop
        
        $fields = $this->db->getFieldNames('t_registrasi');
        
        if (in_array('no_reg', $fields)) {
            $this->forge->dropColumn('t_registrasi', 'no_reg');
        }
        
        if (in_array('norm', $fields)) {
            // Drop index first if exists
            $this->db->query("DROP INDEX IF EXISTS idx_reg_norm");
            $this->forge->dropColumn('t_registrasi', 'norm');
        }
        
        if (in_array('jenis_kunjungan', $fields)) {
            $this->forge->dropColumn('t_registrasi', 'jenis_kunjungan');
        }
    }

    public function down()
    {
        // Re-add columns if rolled back
        $fields = [
            'no_reg'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'norm'            => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'jenis_kunjungan' => ['type' => 'SMALLINT', 'null' => true],
        ];
        $this->forge->addColumn('t_registrasi', $fields);
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reg_norm ON t_registrasi (norm)");
    }
}
