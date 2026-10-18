<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\mitra_bisnisModel;

class barang_jasaModel extends Model
{
    use HasFactory;
    protected $table = 'barang_jasa';
    protected $primaryKey = 'barang_id';
    
    protected $fillable = [
        'mitra_id',
        'nama_barang',
        'deskripsi',
        'jenis',
        'stok',
        'harga_satuan',
        'status_ketersediaan',
        'foto_produk'
    ];

    public function mitra()
    {
        return $this->belongsTo(mitra_bisnisModel::class, 'mitra_id', 'mitra_id');
    }

}

