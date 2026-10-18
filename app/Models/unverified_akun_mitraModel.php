<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unverified_akun_mitraModel extends Model
{
    use HasFactory;
    protected $table = 'unverified_akun_mitra';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nama',
        'username',
        'password',
        'alamat',
        'kontak',
        'email',
        'nama_bank',
        'no_rekening',
        'kategori',
    ];
}
