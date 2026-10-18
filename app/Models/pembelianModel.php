<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\mitra_bisnisModel;

class pembelianModel extends Model
{
    use HasFactory;
    protected $table = 'pembelian';
    protected $primaryKey = 'pembelian_id';
    
    protected $fillable = [
        'mitra_id',
        'barang_id',
        'laporan_id',
        'jumlah_beli',
        'total_harga',
        'status_pembelian',
        'status_bayar',
        'bukti_bayar',
        'nama_barang',
        'jenis',
    ];

    public function mitra_bisnis()
    {
        return $this->belongsTo(mitra_bisnisModel::class, 'mitra_id');
    }

    // Laporan kerusakan yang ditangani oleh pembelian ini (boleh kosong)
    public function laporan()
    {
        return $this->belongsTo(laporan_kerusakanModel::class, 'laporan_id', 'laporan_id');
    }

    public function getStatusBayarAttribute()
    {
        return $this->bukti_bayar ? 'Sudah Bayar' : 'Belum Bayar';
    }
}
