<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create t_vital_sign table
 * Mapped to SatuSehat FHIR Observation resource
 */
class CreateVitalSignTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                      => ['type' => 'BIGSERIAL'],
            'reg_id'                  => ['type' => 'BIGINT', 'null' => false],
            'created_by'              => ['type' => 'INT', 'null' => true],
            // Tanda vital
            'tekanan_darah_sistole'   => ['type' => 'SMALLINT', 'null' => true],  // mmHg – LOINC 8480-6
            'tekanan_darah_diastole'  => ['type' => 'SMALLINT', 'null' => true],  // mmHg – LOINC 8462-4
            'nadi'                    => ['type' => 'SMALLINT', 'null' => true],  // /mnt – LOINC 8867-4
            'suhu'                    => ['type' => 'DECIMAL', 'constraint' => '4,1', 'null' => true],  // °C – LOINC 8310-5
            'respirasi'               => ['type' => 'SMALLINT', 'null' => true],  // /mnt – LOINC 9279-1
            'spo2'                    => ['type' => 'SMALLINT', 'null' => true],  // %   – LOINC 59408-5
            // Antropometri
            'tinggi_badan'            => ['type' => 'SMALLINT', 'null' => true],  // cm  – LOINC 8302-2
            'berat_badan'             => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],  // kg – LOINC 29463-7
            'lingkar_kepala'          => ['type' => 'DECIMAL', 'constraint' => '5,1', 'null' => true],  // cm
            'lingkar_perut'           => ['type' => 'DECIMAL', 'constraint' => '5,1', 'null' => true],  // cm
            // Neurologi
            'gcs'                     => ['type' => 'SMALLINT', 'null' => true],
            // SatuSehat ref
            'ihs_observation_ids'     => ['type' => 'TEXT', 'null' => true],  // JSON array of FHIR Observation IDs
            'created_at'              => ['type' => 'TIMESTAMPTZ', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('t_vital_sign', true);

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_vs_reg_id ON t_vital_sign (reg_id)');
    }

    public function down()
    {
        $this->forge->dropTable('t_vital_sign', true);
    }
}
