<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBpjsFieldsToRawatJalan extends Migration
{
    public function up()
    {
        $fields = [
            'tujuan_kunjungan' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'default'    => '0',
                'after'      => 'no_sep'
            ],
            'asal_rujukan' => [
                'type'       => 'VARCHAR',
                'constraint' => 2,
                'null'       => true,
                'after'      => 'tujuan_kunjungan'
            ],
            'no_rujukan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'asal_rujukan'
            ],
            'diag_awal' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'after'      => 'no_rujukan'
            ],
        ];
        
        $this->forge->addColumn('t_rawat_jalan', $fields);

        // Also add unit_id to m_pegawai_dokter so we can link them to poli
        $this->forge->addColumn('m_pegawai_dokter', [
            'unit_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'pegawai_id']
        ]);
        $this->db->query('ALTER TABLE m_pegawai_dokter ADD CONSTRAINT fk_dokter_poli FOREIGN KEY (unit_id) REFERENCES poliklinik(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('t_rawat_jalan', ['tujuan_kunjungan', 'asal_rujukan', 'no_rujukan', 'diag_awal']);
    }
}
