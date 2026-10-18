<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan_kerusakanModel extends Model
{
    use HasFactory;
    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'laporan_id';
    
    protected $fillable = [
        'tanggal_lapor',
        'lokasi_fasilitas',
        'deskripsi_kerusakan',
        'status_laporan',
        'foto_kerusakan',
        'user_id',
    ];

    // User (Tamu/Pegawai) yang membuat laporan
    public function pelapor()
    {
        return $this->belongsTo(userModel::class, 'user_id', 'user_id');
    }

    // Pembelian barang/jasa yang dilakukan untuk menangani laporan ini
    public function pembelian()
    {
        return $this->hasMany(pembelianModel::class, 'laporan_id', 'laporan_id');
    }
}
