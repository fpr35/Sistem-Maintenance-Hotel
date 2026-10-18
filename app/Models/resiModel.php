<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\pembelianModel;
use App\Models\mitra_bisnisModel;

class resiModel extends Model
{
    use HasFactory;
    protected $table = 'resi';
    protected $primaryKey = 'resi_id';
    
    protected $fillable = [
        'mitra_id',
        'pembelian_id',
        'nama',
        'alamat',
        'kontak',
    ];


    public function pembelian()
    {
        return $this->belongsTo(pembelianModel::class, 'pembelian_id', 'pembelian_id');
    }

    public function mitra_bisnis()
    {
        return $this->belongsTo(mitra_bisnisModel::class, 'mitra_id', 'mitra_id');
    }
}
