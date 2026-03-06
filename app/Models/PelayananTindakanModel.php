<?php

namespace App\Models;

use CodeIgniter\Model;

class PelayananTindakanModel extends Model
{
    protected $table            = 't_pelayanan_tindakan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reg_id', 'unit_id', 'pelaksana_id', 'kode_tarif', 
        'nama_tindakan', 'qty', 'tarif_satuan', 'tarif_total', 
        'waktu_tindakan', 'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByRegistrasi(int $regId, int $unitId = null)
    {
        $builder = $this->select('t_pelayanan_tindakan.*, m_pegawai.nama_pegawai as nama_pelaksana')
                        ->join('m_pegawai', 'm_pegawai.id = t_pelayanan_tindakan.pelaksana_id', 'left')
                        ->where('reg_id', $regId);
        if ($unitId) {
            $builder->where('unit_id', $unitId);
        }
        return $builder->orderBy('waktu_tindakan', 'DESC')->get()->getResultArray();
    }
}
