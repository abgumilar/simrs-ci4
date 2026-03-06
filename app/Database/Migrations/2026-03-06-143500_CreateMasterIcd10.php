<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterIcd10 extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kode'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_valid'   => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'TIMESTAMPTZ', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('kode', true);
        $this->forge->createTable('m_icd10', true);
        
        // Insert some standard basic data to prevent empty dropdowns
        $db = \Config\Database::connect();
        $builder = $db->table('m_icd10');
        $builder->insertBatch([
            ['kode' => 'A09.9', 'nama' => 'Gastroenteritis and colitis of unspecified origin'],
            ['kode' => 'I10',   'nama' => 'Essential (primary) hypertension'],
            ['kode' => 'J00',   'nama' => 'Acute nasopharyngitis [common cold]'],
            ['kode' => 'J06.9', 'nama' => 'Acute upper respiratory infection, unspecified'],
            ['kode' => 'E11.9', 'nama' => 'Type 2 diabetes mellitus without complications'],
            ['kode' => 'K30',   'nama' => 'Functional dyspepsia'],
            ['kode' => 'M54.5', 'nama' => 'Low back pain'],
            ['kode' => 'R50.9', 'nama' => 'Fever, unspecified'],
            ['kode' => 'K29.7', 'nama' => 'Gastritis, unspecified'],
            ['kode' => 'L02.9', 'nama' => 'Cutaneous abscess, furuncle and carbuncle, unspecified']
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('m_icd10');
    }
}
