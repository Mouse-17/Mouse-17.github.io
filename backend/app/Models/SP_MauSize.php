<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SP_MauSize extends Model
{
    use HasFactory;
    protected $table = 'san_pham_mau_size';
    protected $fillable = [
        'id',
        'ID_Mau',
        'ID_Kichthuoc',
        'ID_SP',
    ];

    public function sanpham(){
        return $this->belongsTo(SanPham::class, 'ID_SP');
    }
    
    public function mau(){
        return $this->belongsTo(MauSac::class, 'ID_Mau');
    }
    
    public function size(){
        return $this->belongsTo(Size::class, 'ID_Kichthuoc');
    }
}
