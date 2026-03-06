<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperationalTables extends Migration
{
    public function up()
    {
        // 1. Master Obat & Alkes
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_obat'   => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama_obat'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'satuan'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'harga_jual'  => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'stok'        => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_obat');

        // 2. Master Tindakan
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_tindakan'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'tarif'          => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_tindakan');

        // 3. Pendaftaran
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_pasien'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_poli'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_dokter'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tgl_daftar'     => ['type' => 'DATETIME'],
            'no_antrian'     => ['type' => 'INT', 'constraint' => 11],
            'status'         => ['type' => 'VARCHAR', 'constraint' => 20], // Terdaftar, Diperiksa, Selesai, Batal
            'keluhan'        => ['type' => 'TEXT'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_pasien', 'pasien', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_poli', 'poliklinik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_dokter', 'dokter', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pendaftaran');

        // 4. Pemeriksaan (Soap & Clinical Data)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_daftar'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'subjektif'      => ['type' => 'TEXT'],
            'objektif'       => ['type' => 'TEXT'],
            'asesmen'        => ['type' => 'TEXT'], // ICD-10 Code/Diagnosis
            'rencana'        => ['type' => 'TEXT'],
            'tanya_tanda_vital' => ['type' => 'VARCHAR', 'constraint' => 255], // Tensi, Nadi, etc.
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_daftar', 'pendaftaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pemeriksaan');

        // 5. Resep
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_daftar'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_obat'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah'         => ['type' => 'INT', 'constraint' => 11],
            'aturan_pakai'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'status_tebus'   => ['type' => 'VARCHAR', 'constraint' => 20], // Belum, Selesai
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_daftar', 'pendaftaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_obat', 'master_obat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('resep');

        // 6. Penunjang (Lab & Rad)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_daftar'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jenis'          => ['type' => 'VARCHAR', 'constraint' => 20], // Lab, Radiologi
            'item_pemeriksaan' => ['type' => 'VARCHAR', 'constraint' => 255],
            'hasil'          => ['type' => 'TEXT', 'null' => true],
            'status'         => ['type' => 'VARCHAR', 'constraint' => 20], // Order, Selesai
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_daftar', 'pendaftaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('permintaan_penunjang');

        // 7. Pembayaran (Billing)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_daftar'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'total_biaya'   => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'metode_bayar'   => ['type' => 'VARCHAR', 'constraint' => 50], // Tunai, Transfer, BPJS
            'status_bayar'   => ['type' => 'VARCHAR', 'constraint' => 20], // Lunas, Belum
            'kasir_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tgl_bayar'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_daftar', 'pendaftaran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pembayaran');
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran');
        $this->forge->dropTable('permintaan_penunjang');
        $this->forge->dropTable('resep');
        $this->forge->dropTable('pemeriksaan');
        $this->forge->dropTable('pendaftaran');
        $this->forge->dropTable('master_tindakan');
        $this->forge->dropTable('master_obat');
    }
}
