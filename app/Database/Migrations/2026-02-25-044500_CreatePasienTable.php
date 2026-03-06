<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePasienTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'norm' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],

            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'no_jkn' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'ihs_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'nama_pasien' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'tgl_lahir' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'jenis_kelamin' => [
                'type'       => 'VARCHAR',
                'constraint' => 1, // L / P
                'null'       => true,
            ],

            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'rt' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => true,
            ],

            'rw' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => true,
            ],

            'kelurahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'provinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'kode_pos' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],

            'no_telp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'agama' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'status_perkawinan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'pekerjaan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'pendidikan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'kewarganegaraan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'tanggal_daftar_pertama' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],

            'tanggal_kunjungan_terakhir' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],

            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],

            'is_deceased' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],

            'tanggal_meninggal' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],

            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],

            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);

        // primary key
        $this->forge->addKey('id', true);

        // unique
        $this->forge->addUniqueKey('norm');

        // index penting
        $this->forge->addKey('nik');
        $this->forge->addKey('no_jkn');
        $this->forge->addKey('nama_pasien');

        $this->forge->createTable('pasien');
    }

    public function down()
    {
        $this->forge->dropTable('pasien');
    }
}
