<?php

namespace App\Models;

use CodeIgniter\Model;

class ResepObatModel extends Model
{
    protected $table            = 't_resep_obat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reg_id', 'unit_id', 'dokter_id', 'no_resep', 
        'waktu_order', 'status_resep', 'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function generateNoResep(): string
    {
        $prefix = 'RSP' . date('ymd');
        $last = $this->like('no_resep', $prefix, 'after')
                     ->orderBy('id', 'DESC')
                     ->first();
        if (!$last) return $prefix . '0001';
        
        $num = (int)substr($last['no_resep'], -4);
        return $prefix . str_pad((string)($num + 1), 4, '0', STR_PAD_LEFT);
    }
    
    public function getWithDetails(int $regId, int $unitId = null)
    {
        $builder = $this->select('t_resep_obat.*, m_pegawai.nama_pegawai as nama_dokter')
                        ->join('m_pegawai_dokter', 'm_pegawai_dokter.id = t_resep_obat.dokter_id', 'left')
                        ->join('m_pegawai', 'm_pegawai.id = m_pegawai_dokter.pegawai_id', 'left')
                        ->where('reg_id', $regId);
        if ($unitId) {
            $builder->where('unit_id', $unitId);
        }
        $reseps = $builder->orderBy('waktu_order', 'DESC')->get()->getResultArray();
        
        if (empty($reseps)) return [];
        
        $resepIds = array_column($reseps, 'id');
        $detailModel = new \App\Models\ResepDetailModel();
        $details = $detailModel->whereIn('resep_id', $resepIds)->findAll();
        
        // Group details by resep_id
        $groupedDetails = [];
        foreach ($details as $d) {
            $groupedDetails[$d['resep_id']][] = $d;
        }
        
        // Attach
        foreach ($reseps as &$r) {
            $r['details'] = $groupedDetails[$r['id']] ?? [];
        }
        
        return $reseps;
    }
}
