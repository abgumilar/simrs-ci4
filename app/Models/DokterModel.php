<?php

namespace App\Models;

use CodeIgniter\Model;

class DokterModel extends Model
{
    protected $table            = 'm_pegawai_dokter';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'pegawai_id', 'id_poli', 'specialis', 'sip', 'kode_bpjs', 'ihs_practitioner'
    ];

    public function getDokterWithPoli($id = null)
    {
        $builder = $this->select('m_pegawai_dokter.*, mp.nama_pegawai as fullname, 
                                 (SELECT STRING_AGG(p.nama_poli, \', \') 
                                  FROM m_pegawai_unit pu 
                                  JOIN poliklinik p ON p.id = pu.unit_id 
                                  WHERE pu.pegawai_id = m_pegawai_dokter.pegawai_id) as nama_poli')
                        ->join('m_pegawai mp', 'mp.id = m_pegawai_dokter.pegawai_id');
        
        if ($id) {
            $result = $builder->where('m_pegawai_dokter.id', $id)->first();
            if ($result) {
                // Also fetch unit IDs as array
                $db = \Config\Database::connect();
                $result['unit_ids'] = array_column($db->table('m_pegawai_unit')
                                            ->where('pegawai_id', $result['pegawai_id'])
                                            ->get()->getResultArray(), 'unit_id');
                // Cast all to int for JS consistency
                $result['unit_ids'] = array_map('intval', $result['unit_ids']);
            }
            return $result;
        }

        $results = $builder->findAll();
        if (!empty($results)) {
            $db = \Config\Database::connect();
            foreach ($results as &$row) {
                $row['unit_ids'] = array_column($db->table('m_pegawai_unit')
                                                ->where('pegawai_id', $row['pegawai_id'])
                                                ->get()->getResultArray(), 'unit_id');
                $row['unit_ids'] = array_map('intval', $row['unit_ids']);
            }
        }
        return $results;
    }

    public function syncUnits($pegawaiId, array $unitIds)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('m_pegawai_unit');
        
        // Remove existing
        $builder->where('pegawai_id', $pegawaiId)->delete();
        
        // Insert new
        if (!empty($unitIds)) {
            $data = [];
            foreach ($unitIds as $unitId) {
                $data[] = [
                    'pegawai_id' => $pegawaiId,
                    'unit_id'    => $unitId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            $builder->insertBatch($data);
        }
    }
}
