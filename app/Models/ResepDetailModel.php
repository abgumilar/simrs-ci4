<?php

namespace App\Models;

use CodeIgniter\Model;

class ResepDetailModel extends Model
{
    protected $table            = 't_resep_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'resep_id', 'kode_obat', 'nama_obat', 'jumlah', 'signa', 'catatan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = false; // No updated_at in table
}
