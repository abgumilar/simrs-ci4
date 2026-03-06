<?php

namespace App\Models;

use CodeIgniter\Model;

class PoliklinikModel extends Model
{
    protected $table            = 'poliklinik';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_poli', 'lokasi', 'ihs_location', 'kode_bpjs'];
}
