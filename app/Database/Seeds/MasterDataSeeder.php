<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Seed Poliklinik
        $db->query('TRUNCATE TABLE poliklinik CASCADE');
        $poliklinik = [
            ['nama_poli' => 'Poli Umum', 'lokasi' => 'Lantai 1, Gedung A', 'kode_bpjs' => '001'],
            ['nama_poli' => 'Poli Gigi', 'lokasi' => 'Lantai 1, Gedung A', 'kode_bpjs' => '002'],
            ['nama_poli' => 'Poli Mata', 'lokasi' => 'Lantai 2, Gedung B', 'kode_bpjs' => '003'],
            ['nama_poli' => 'IGD', 'lokasi' => 'Lantai 1, Lobby', 'kode_bpjs' => 'IGD'],
        ];
        $db->table('poliklinik')->insertBatch($poliklinik);
        $poliIds = [];
        foreach ($poliklinik as $p) {
            $poliIds[$p['nama_poli']] = $db->table('poliklinik')->where('nama_poli', $p['nama_poli'])->get()->getRow()->id;
        }

        // 2. Fetch Profesi ID for Dokter
        $profesiDokter = $db->table('m_profesi')->where('nama_profesi', 'Dokter Umum')->get()->getRow();
        if (!$profesiDokter) {
            // Fallback if RBACSeeder hasn't run or names differ
            $profesiDokter = $db->table('m_profesi')->like('nama_profesi', 'Dokter')->get()->getRow();
        }
        $profesiId = $profesiDokter ? $profesiDokter->id : null;

        // 3. Seed M_Pegawai and M_Pegawai_Dokter
        // Truncate cascade to handle foreign keys
        $db->query('TRUNCATE TABLE m_pegawai CASCADE');
        
        $doctors = [
            [
                'pegawai' => [
                    'nik' => '3207010101010001',
                    'nama_pegawai' => 'dr. Andi Budiman',
                    'no_hp' => '081223344556',
                    'profesi_id' => $profesiId,
                    'jabatan' => 'Dokter Umum',
                ],
                'dokter_info' => [
                    'unit_id' => $poliIds['Poli Umum'],
                    'spesialisasi' => 'Umum',
                    'no_sip' => 'SIP/2024/0001',
                    'kode_dokter_bpjs' => '12345'
                ]
            ],
            [
                'pegawai' => [
                    'nik' => '3207010101010002',
                    'nama_pegawai' => 'dr. Siti Aminah, Sp.M',
                    'no_hp' => '081223344557',
                    'profesi_id' => $profesiId,
                    'jabatan' => 'Dokter Spesialis Mata',
                ],
                'dokter_info' => [
                    'unit_id' => $poliIds['Poli Mata'],
                    'spesialisasi' => 'Mata',
                    'no_sip' => 'SIP/2024/0002',
                    'kode_dokter_bpjs' => '12346'
                ]
            ]
        ];

        foreach ($doctors as $d) {
            $db->table('m_pegawai')->insert($d['pegawai']);
            $pegawaiId = $db->insertID();
            
            $d['dokter_info']['pegawai_id'] = $pegawaiId;
            $db->table('m_pegawai_dokter')->insert($d['dokter_info']);
        }
    }
}
