<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersTableForPegawai extends Migration
{
    public function up()
    {
        // 1 & 2. Force drop 'dokter' table and cascade all constraints pointing to it 
        // (pendaftaran and t_rawat_jalan FKs) so we don't need to guess exact FK names.
        if ($this->db->tableExists('dokter')) {
            $this->db->query('DROP TABLE dokter CASCADE');
        }

        // 3. Alter constraint columns to map to m_pegawai_dokter (BIGINT)
        if ($this->db->tableExists('pendaftaran')) {
            $this->forge->modifyColumn('pendaftaran', [
                'id_dokter' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true]
            ]);
            $this->db->query('ALTER TABLE pendaftaran ADD CONSTRAINT pendaftaran_id_dokter_foreign FOREIGN KEY (id_dokter) REFERENCES m_pegawai_dokter(id) ON DELETE SET NULL ON UPDATE CASCADE');
        }

        if ($this->db->tableExists('t_rawat_jalan')) {
            $this->forge->modifyColumn('t_rawat_jalan', [
                'dokter_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true]
            ]);
            $this->db->query('ALTER TABLE t_rawat_jalan ADD CONSTRAINT t_rawat_jalan_dokter_id_foreign FOREIGN KEY (dokter_id) REFERENCES m_pegawai_dokter(id) ON DELETE SET NULL ON UPDATE CASCADE');
        }

        // 4. Add pegawai_id to 'users' table linking to m_pegawai
        $fields = [
            'pegawai_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id'
            ],
            'active' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
                'after'      => 'role'
            ]
        ];
        
        $this->forge->addColumn('users', $fields);
        $this->db->query('ALTER TABLE users ADD CONSTRAINT fk_user_pegawai FOREIGN KEY (pegawai_id) REFERENCES m_pegawai(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE users DROP CONSTRAINT IF EXISTS fk_user_pegawai');
        $this->forge->dropColumn('users', 'pegawai_id');
        $this->forge->dropColumn('users', 'active');
        
        // Detailed rollback for dokter table isn't strictly necessary for forward moving dev,
        // but removing the constraints is enough to allow dropping.
        $this->db->query('ALTER TABLE pendaftaran DROP CONSTRAINT IF EXISTS pendaftaran_id_dokter_foreign');
        $this->db->query('ALTER TABLE t_rawat_jalan DROP CONSTRAINT IF EXISTS t_rawat_jalan_dokter_id_foreign');
    }
}
