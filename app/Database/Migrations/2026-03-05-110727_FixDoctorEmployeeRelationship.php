<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixDoctorEmployeeRelationship extends Migration
{
    public function up()
    {
        // 1. Data Cleaning & Synchronization
        $db = \Config\Database::connect();
        $doctors = $db->table('users')
                      ->where('role', 'dokter')
                      ->where('pegawai_id', null)
                      ->get()->getResultArray();

        foreach ($doctors as $doc) {
            // Generate a short NIK if missing (max 16 chars for the current schema)
            $shortNik = substr(preg_replace('/[^0-9]/', '', str_shuffle('0123456789')), 0, 10);
            
            // Create record in m_pegawai for this doctor
            $db->table('m_pegawai')->insert([
                'nama_pegawai' => $doc['fullname'],
                'nik'          => $shortNik,
                'status_aktif' => true,
                'created_at'   => date('Y-m-d H:i:s')
            ]);
            $pegawaiId = $db->insertID();

            // Link user to the new pegawai record
            $db->table('users')->where('id', $doc['id'])->update(['pegawai_id' => $pegawaiId]);
        }

        // 2. Schema Modification for m_pegawai_dokter
        $this->forge->addColumn('m_pegawai_dokter', [
            'pegawai_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id'
            ]
        ]);

        // Migrate id_user (users table) to pegawai_id (m_pegawai table)
        // using the link from users.pegawai_id
        $db->query("
            UPDATE m_pegawai_dokter pd
            SET pegawai_id = u.pegawai_id
            FROM users u
            WHERE pd.id_user = u.id
            AND u.pegawai_id IS NOT NULL
        ");

        // Now we can safely remove id_user from m_pegawai_dokter
        $this->forge->dropColumn('m_pegawai_dokter', 'id_user');
    }

    public function down()
    {
        $this->forge->addColumn('m_pegawai_dokter', [
            'id_user' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ]
        ]);
        $this->forge->dropColumn('m_pegawai_dokter', 'pegawai_id');
    }
}
