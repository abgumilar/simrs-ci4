<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePegawaiUnitJunction extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Create Junction Table (if not exists)
        $db->query('CREATE TABLE IF NOT EXISTS "m_pegawai_unit" (
            "id" SERIAL NOT NULL,
            "pegawai_id" INT NOT NULL,
            "unit_id" INT NOT NULL,
            "created_at" TIMESTAMP NULL,
            CONSTRAINT "pk_m_pegawai_unit" PRIMARY KEY("id")
        )');

        // 2. Ensure all m_pegawai_dokter have a pegawai_id
        $orphans = $db->table('m_pegawai_dokter')
                      ->where('pegawai_id', null)
                      ->get()->getResultArray();

        foreach ($orphans as $orphan) {
            $shortNik = substr(preg_replace('/[^0-9]/', '', str_shuffle('0123456789')), 0, 10);
            $db->table('m_pegawai')->insert([
                'nama_pegawai' => 'Dokter ' . $shortNik,
                'nik'          => $shortNik,
                'status_aktif' => true,
                'created_at'   => date('Y-m-d H:i:s')
            ]);
            $pegawaiId = $db->insertID();
            $db->table('m_pegawai_dokter')->where('id', $orphan['id'])->update(['pegawai_id' => $pegawaiId]);
        }

        // 3. Migrate existing data from m_pegawai_dokter to m_pegawai_unit
        // Avoid duplicates if some data was already migrated
        $existingMapping = $db->table('m_pegawai_dokter')
                             ->select('pegawai_id, id_poli')
                             ->where('id_poli !=', null)
                             ->get()->getResultArray();

        foreach ($existingMapping as $row) {
            $exists = $db->table('m_pegawai_unit')
                         ->where('pegawai_id', $row['pegawai_id'])
                         ->where('unit_id', $row['id_poli'])
                         ->countAllResults();
            
            if ($exists === 0) {
                $db->table('m_pegawai_unit')->insert([
                    'pegawai_id' => $row['pegawai_id'],
                    'unit_id'    => $row['id_poli'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // 4. Cleanup m_pegawai_dokter column (check if column exists first to be safe)
        $fields = $db->getFieldNames('m_pegawai_dokter');
        if (in_array('id_poli', $fields)) {
            $this->forge->dropColumn('m_pegawai_dokter', 'id_poli');
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('m_pegawai_dokter');
        if (!in_array('id_poli', $fields)) {
            $this->forge->addColumn('m_pegawai_dokter', [
                'id_poli' => [
                    'type'       => 'INT',
                    'unsigned'   => true,
                    'null'       => true,
                ]
            ]);
        }
        $this->forge->dropTable('m_pegawai_unit', true);
    }
}
