<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class userModel extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'kontak',
        'email',
        'aktif',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Data usaha jika user ber-role Mitra
    public function mitra_bisnis()
    {
        return $this->hasOne(mitra_bisnisModel::class, 'user_id', 'user_id');
    }

    // Laporan kerusakan yang dibuat user (Tamu/Pegawai)
    public function laporan()
    {
        return $this->hasMany(laporan_kerusakanModel::class, 'user_id', 'user_id');
    }
}
