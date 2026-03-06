<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StandardizeMPegawaiDokter extends Migration
{
    public function up()
    {
        // 1. Rename columns to match modern SIMRS standards used in premium UI
        $fields = [
            'pegawai_id' => [
                'name' => 'id_user',
                'type' => 'INT',
            ],
            'unit_id' => [
                'name' => 'id_poli',
                'type' => 'INT',
            ],
            'spesialisasi' => [
                'name' => 'specialis',
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'no_sip' => [
                'name' => 'sip',
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'kode_dokter_bpjs' => [
                'name' => 'kode_bpjs',
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ];
        
        $this->forge->modifyColumn('m_pegawai_dokter', $fields);

        // 2. Add SatuSehat / Kemenkes missing fields
        $this->forge->addColumn('m_pegawai_dokter', [
            'ihs_practitioner' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'kode_bpjs'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        // Reverse standardisation
        $fields = [
            'id_user' => [
                'name' => 'pegawai_id',
                'type' => 'INT',
            ],
            'id_poli' => [
                'name' => 'unit_id',
                'type' => 'INT',
            ],
            'specialis' => [
                'name' => 'spesialisasi',
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'sip' => [
                'name' => 'no_sip',
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'kode_bpjs' => [
                'name' => 'kode_dokter_bpjs',
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ];

        $this->forge->modifyColumn('m_pegawai_dokter', $fields);
        $this->forge->dropColumn('m_pegawai_dokter', ['ihs_practitioner', 'created_at', 'updated_at']);
    }
}
