<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table            = 't_registrasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['no_registrasi', 'pasien_id', 'tgl_registrasi', 'jenis_pelayanan', 'status_reg'];

    protected $useTimestamps = false; // We use database level CURRENT_TIMESTAMP

    public function generateNoReg()
    {
        $date = date('Ymd');
        $prefix = "REG/" . $date . "/";
        $lastReg = $this->like('no_registrasi', $prefix, 'after')->orderBy('no_registrasi', 'DESC')->first();
        
        $number = 1;
        if ($lastReg) {
            $parts = explode('/', $lastReg['no_registrasi']);
            $number = (int)end($parts) + 1;
        }
        
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
