<?php

namespace App\Models;

use CodeIgniter\Model;

class DiagnosaModel extends Model
{
    protected $table         = 't_diagnosa';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'reg_id', 'kode_icd', 'nama', 'jenis', 'is_bpjs',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Ambil semua diagnosa untuk satu kunjungan, diurutkan primer dulu
     */
    public function getByReg(int $regId): array
    {
        return $this->where('reg_id', $regId)
                    ->orderBy('jenis', 'ASC')  // primer < sekunder
                    ->findAll();
    }

    /**
     * Sync diagnosa: hapus lama, insert baru
     * $list = [['kode_icd' => 'J06.9', 'nama' => '...', 'jenis' => 'primer'], ...]
     */
    public function sync(int $regId, array $list): void
    {
        $this->where('reg_id', $regId)->delete();
        if (empty($list)) return;

        $rows = array_map(fn($x) => [
            'reg_id'    => $regId,
            'kode_icd'  => $x['kode_icd'],
            'nama'      => $x['nama'] ?? null,
            'jenis'     => $x['jenis'] ?? 'primer',
            'is_bpjs'   => true,
            'created_at'=> date('Y-m-d H:i:s'),
        ], $list);

        $this->insertBatch($rows);
    }
}
