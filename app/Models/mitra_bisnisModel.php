<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mitra_bisnisModel extends Model
{
    use HasFactory;
    protected $table = 'mitra_bisnis';
    protected $primaryKey = 'mitra_id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_mitra',
        'user_id',
        'alamat',
        'kontak',
        'email',
        'nama_bank',
        'no_rekening',
        'kategori',
    ];

    public function user()
    {
        return $this->belongsTo(userModel::class, 'user_id', 'user_id');
    }
}
