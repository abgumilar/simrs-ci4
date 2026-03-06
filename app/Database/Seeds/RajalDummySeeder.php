<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: RajalDummySeeder
 * Membuat data dummy kunjungan rawat jalan lengkap:
 * - 10 pasien (jika belum ada)
 * - 1 poli (Poli Umum) dan 1 dokter (jika belum ada)
 * - 10 registrasi rajal hari ini
 * - 10 data vital sign
 * - 10 data SOAP di t_rawat_jalan
 * - 10 data diagnosa ICD-10
 * Termasuk tabel master ICD-10 mini (50 kode umum)
 */
class RajalDummySeeder extends Seeder
{
    private array $icd10 = [
        ['J06.9', 'Infeksi saluran pernapasan akut atas, tidak spesifik'],
        ['K30',   'Dispepsia fungsional'],
        ['I10',   'Hipertensi esensial (primer)'],
        ['E11.9', 'Diabetes melitus tipe 2 tanpa komplikasi'],
        ['J18.9', 'Pneumonia, tidak spesifik'],
        ['M54.5', 'Nyeri punggung bawah'],
        ['A09',   'Diare dan gastroenteritis'],
        ['N39.0', 'Infeksi saluran kemih, tidak spesifik'],
        ['J45.9', 'Asma, tidak spesifik'],
        ['K29.7', 'Gastritis, tidak spesifik'],
        ['B34.9', 'Infeksi virus, tidak spesifik'],
        ['R51',   'Sakit kepala'],
        ['M25.5', 'Nyeri sendi'],
        ['L30.9', 'Dermatitis, tidak spesifik'],
        ['E03.9', 'Hipotiroidisme, tidak spesifik'],
        // Sekunder umum
        ['I11.0', 'Penyakit jantung hipertensif dengan gagal jantung'],
        ['E78.5', 'Hiperlipidemia, tidak spesifik'],
        ['Z87.1', 'Riwayat pribadi penyakit ginjal'],
        ['Z82.3', 'Riwayat keluarga dengan diabetes melitus'],
    ];

    private array $pasienDummy = [
        ['Siti Rahayu',        '3201010101800001', '0001234567890', '1980-01-01', 'P', '081234567890'],
        ['Budi Santoso',       '3201020202750001', '0001234567891', '1975-02-02', 'L', '081234567891'],
        ['Dewi Kartini',       '3201030303900001', '0001234567892', '1990-03-03', 'P', '081234567892'],
        ['Ahmad Fauzi',        '3201040404850001', '0001234567893', '1985-04-04', 'L', '081234567893'],
        ['Sri Wahyuni',        '3201050505920001', '0001234567894', '1992-05-05', 'P', '081234567894'],
        ['Rudi Hartono',       '3201060606700001', '0001234567895', '1970-06-06', 'L', '081234567895'],
        ['Ningsih Suryani',    '3201070707880001', '0001234567896', '1988-07-07', 'P', '081234567896'],
        ['Hendra Wijaya',      '3201080808960001', null,            '1996-08-08', 'L', '081234567897'],
        ['Marlina Puspitasari','3201090909820001', '0001234567898', '1982-09-09', 'P', '081234567898'],
        ['Eko Prasetyo',       '3201101010780001', '0001234567899', '1978-10-10', 'L', '081234567899'],
    ];

    // SOAP data per pasien
    private array $soapData = [
        ['Batuk pilek 3 hari', 'TBC(-), sesak(-)', 'Baik', 'Compos Mentis', 'Pharing hiperemis +, Rhinitis +', 'J06.9', 'Infeksi saluran pernapasan akut atas', 'Amoxicillin 3x500mg / Paracetamol 3x500mg', 'Istirahat cukup, minum banyak air', '3 hari lagi jika tidak membaik', 'Pulang', 'finished'],
        ['Nyeri ulu hati',     'Mual (+), muntah (-)', 'Baik', 'Compos Mentis', 'Epigastrium NT +', 'K30', 'Dispepsia fungsional', 'Antasida 3xAC / Omeprazole 1x20mg', 'Makan teratur, hindari pedas', 'Kontrol 1 minggu', 'Pulang', 'finished'],
        ['Tekanan darah tinggi, pusing', 'HT sejak 5 thn', 'Sedang', 'Compos Mentis', 'TD 160/100, HR 88', 'I10', 'Hipertensi esensial', 'Amlodipine 1x10mg', 'Kurangi garam, olahraga rutin', 'Kontrol 2 minggu', 'Pulang', 'finished'],
        ['Gula darah tidak terkontrol', 'DM 10 thn', 'Baik', 'Compos Mentis', 'GDA 280 mg/dL', 'E11.9', 'Diabetes melitus tipe 2', 'Metformin 2x500mg / Glibenclamide 1x5mg', 'Diet DM 1700 kkal, olahraga', 'Kontrol HbA1c 3 bulan', 'Pulang', 'finished'],
        ['Sesak napas, demam', 'Batuk berdahak 1 minggu', 'Sedang', 'Compos Mentis', 'Ronkhi +/+, SpO2 95%', 'J18.9', 'Pneumonia tidak spesifik', 'Azithromycin 1x500mg / Ambroxol 3x30mg', 'Banyak istirahat', 'Kontrol 3 hari', 'Pulang', 'finished'],
        ['Nyeri pinggang', 'Kerja berat tiap hari', 'Baik', 'Compos Mentis', 'NT lumbal +, ROM terbatas', 'M54.5', 'Nyeri punggung bawah', 'Na Diklofenak 2x50mg / Muscle relaxant', 'Hindari angkat berat', 'Fisioterapi', 'Pulang', 'in-progress'],
        ['BAB cair 5x', 'Tanpa darah/lendir', 'Baik', 'Compos Mentis', 'BU meningkat, NT(-)', 'A09', 'Diare dan gastroenteritis', 'Oralit / Zink 1x20mg / Attapulgite', 'Minum air putih, hindari susu', '3 hari', 'Pulang', 'in-progress'],
        ['Nyeri buang air kecil', 'Sering BAK', 'Baik', 'Compos Mentis', 'NT suprapubis +', 'N39.0', 'Infeksi saluran kemih', 'Ciprofloxacin 2x500mg', 'Banyak minum, jaga kebersihan', '5 hari', 'Pulang', 'draft'],
        ['Sesak, mengi', 'Asma sejak kecil', 'Sedang', 'Compos Mentis', 'Wheezing +/+', 'J45.9', 'Asma tidak spesifik', 'Salbutamol inhaler / Methylprednisolon 2x8mg', 'Hindari debu/dingin', 'Kontrol 1 minggu', 'Pulang', 'draft'],
        ['Mual nyeri perut', 'Tidak ada riwayat maag', 'Baik', 'Compos Mentis', 'NT epigastrium +', 'K29.7', 'Gastritis tidak spesifik', 'Omeprazole 2x20mg / Domperidone 3x10mg', 'Makan teratur', '1 minggu', 'Pulang', 'draft'],
    ];

    // Vital sign per pasien [sistol, diastol, nadi, suhu, rr, spo2, tb, bb]
    private array $vitalData = [
        [110, 70,  88,  37.2, 20, 98, 155, 50.0],
        [130, 85,  82,  36.8, 18, 99, 168, 72.0],
        [160, 100, 88,  36.9, 22, 97, 162, 65.0],
        [140, 90,  84,  36.7, 20, 98, 158, 68.0],
        [120, 80,  98,  38.5, 24, 95, 160, 55.0],
        [120, 78,  80,  36.6, 18, 99, 170, 80.0],
        [110, 70,  96,  37.8, 22, 98, 153, 52.0],
        [118, 76,  88,  36.5, 20, 99, 165, 60.0],
        [125, 82,  90,  37.1, 21, 97, 158, 62.0],
        [135, 88,  84,  36.8, 19, 98, 172, 78.0],
    ];

    public function run()
    {
        $db = $this->db;
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        // ── 1. Buat tabel master ICD-10 mini jika belum ada ──────────────
        $db->query("CREATE TABLE IF NOT EXISTS m_icd10 (
            id   SERIAL PRIMARY KEY,
            kode VARCHAR(10) NOT NULL,
            nama TEXT NOT NULL
        )");
        $existIcd = $db->table('m_icd10')->countAll();
        if (!$existIcd) {
            foreach ($this->icd10 as $row) {
                $db->table('m_icd10')->insert(['kode' => $row[0], 'nama' => $row[1]]);
            }
        }

        // ── 2. Pastikan ada minimal 1 Poli dan 1 Dokter ───────────────────
        $poli = $db->table('poliklinik')->where('nama_poli', 'Poli Umum')->get()->getRowArray();
        if (!$poli) {
            $db->table('poliklinik')->insert(['nama_poli' => 'Poli Umum', 'lokasi' => 'Lantai 1', 'kode_bpjs' => 'PU']);
            $poliId = $db->insertID();
        } else {
            $poliId = $poli['id'];
        }

        $dokter = $db->table('m_pegawai_dokter')->limit(1)->get()->getRowArray();
        $dokterId = $dokter ? $dokter['id'] : null;

        // ── 3. Insert pasien dummy ────────────────────────────────────────
        $pasienIds = [];
        foreach ($this->pasienDummy as $i => $row) {
            $exist = $db->table('pasien')->where('nik', $row[1])->get()->getRowArray();
            if (!$exist) {
                // Generate dummy NORM (RM-xxxx)
                $norm = 'RM-' . str_pad(rand(1000, 999999), 6, '0', STR_PAD_LEFT);
                $db->table('pasien')->insert([
                    'norm'         => $norm,
                    'nama_pasien'  => $row[0],
                    'nik'          => $row[1],
                    'no_jkn'       => $row[2],
                    'tgl_lahir'    => $row[3],
                    'jenis_kelamin'=> $row[4],
                    'no_telp'      => $row[5],
                    'alamat'       => 'Jl. Dummy No.' . ($i + 1) . ', Kota Contoh',
                    'is_active'    => true,
                    'created_at'   => $now,
                ]);
                $pasienIds[] = $db->insertID();
            } else {
                $pasienIds[] = $exist['id'];
            }
        }

        // ── 4. Insert t_registrasi & t_rawat_jalan & t_vital_sign ─────────
        foreach ($pasienIds as $i => $pasienId) {
            $no_reg = date('Ymd') . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $penjamin = ($i % 3 === 2) ? 'Umum' : 'BPJS';

            // Cek apakah registrasi hari ini sudah ada
            $exist = $db->table('t_registrasi')->where('norm', '')->limit(1)->get()->getRowArray();
            $existReg = $db->table('t_registrasi')
                ->where('pasien_id', $pasienId)
                ->where('DATE(tgl_registrasi)', $today)
                ->get()->getRowArray();

            if ($existReg) {
                $regId = $existReg['id'];
            } else {
                $db->table('t_registrasi')->insert([
                    'no_reg'         => $no_reg,
                    'pasien_id'      => $pasienId,
                    'tgl_registrasi' => $today . ' ' . sprintf('%02d', 7 + $i) . ':' . sprintf('%02d', rand(0,59)) . ':00',
                    'jenis_pelayanan'=> 1,
                    'penjamin'       => $penjamin,
                    'no_jkn'         => $this->pasienDummy[$i][2] ?? null,
                    'status'         => $this->soapData[$i][11] === 'finished' ? 'finished' : 'active',
                ]);
                $regId = $db->insertID();
            }

            // t_rawat_jalan
            $existRj = $db->table('t_rawat_jalan')->where('reg_id', $regId)->get()->getRowArray();
            $soap = $this->soapData[$i];
            $rjData = [
                'reg_id'             => $regId,
                'unit_id'            => $poliId,
                'dokter_id'          => $dokterId,
                'penjamin'           => $penjamin,
                'no_antrian'         => $i + 1,
                'sumber_daftar'      => 'Loket',
                'status_emr'         => $soap[11],
                // S
                'keluhan_utama'      => $soap[0],
                'riwayat_penyakit'   => $soap[1],
                'keadaan_umum'       => $soap[2],
                'kesadaran'          => $soap[3],
                'pemeriksaan_fisik'  => $soap[4],
                // A
                'diagnosa_utama'     => $soap[5],
                'diagnosa_utama_nama'=> $soap[6],
                // P
                'terapi'             => $soap[7],
                'edukasi'            => $soap[8],
                'anjuran_kontrol'    => $soap[9],
                'status_pulang'      => $soap[10],
                'tgl_selesai'        => $soap[11] === 'finished' ? $today . ' ' . sprintf('%02d', 9 + $i) . ':00:00' : null,
            ];

            if (!$existRj) {
                $db->table('t_rawat_jalan')->insert($rjData);
            }

            // t_vital_sign
            $existVs = $db->table('t_vital_sign')->where('reg_id', $regId)->get()->getRowArray();
            if (!$existVs) {
                $vs = $this->vitalData[$i];
                $db->table('t_vital_sign')->insert([
                    'reg_id'                  => $regId,
                    'tekanan_darah_sistole'   => $vs[0],
                    'tekanan_darah_diastole'  => $vs[1],
                    'nadi'                    => $vs[2],
                    'suhu'                    => $vs[3],
                    'respirasi'               => $vs[4],
                    'spo2'                    => $vs[5],
                    'tinggi_badan'            => $vs[6],
                    'berat_badan'             => $vs[7],
                    'created_at'              => $now,
                ]);
            }

            // t_diagnosa
            $existDiag = $db->table('t_diagnosa')->where('reg_id', $regId)->get()->getRowArray();
            if (!$existDiag) {
                $db->table('t_diagnosa')->insert([
                    'reg_id'    => $regId,
                    'kode_icd'  => $this->soapData[$i][5],
                    'nama'      => $this->soapData[$i][6],
                    'jenis'     => 'primer',
                    'is_bpjs'   => true,
                    'created_at'=> $now,
                ]);
            }
        }

        echo "RajalDummySeeder: 10 kunjungan rawat jalan dummy berhasil diinsert.\n";
    }
}
