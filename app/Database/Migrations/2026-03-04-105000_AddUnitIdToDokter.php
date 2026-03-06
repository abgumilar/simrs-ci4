<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitIdToDokter extends Migration
{
    public function up()
    {
        $this->forge->addColumn('m_pegawai_dokter', [
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'pegawai_id'
            ]
        ]);
        $this->db->query('ALTER TABLE m_pegawai_dokter ADD CONSTRAINT fk_dokter_poli FOREIGN KEY (unit_id) REFERENCES poliklinik(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE m_pegawai_dokter DROP CONSTRAINT IF EXISTS fk_dokter_poli');
        $this->forge->dropColumn('m_pegawai_dokter', 'unit_id');
    }
}
