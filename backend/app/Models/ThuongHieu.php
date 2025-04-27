<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThuongHieu extends Model
{
    use HasFactory;

    protected $table = 'thuong_hieu';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'ten_thuong_hieu',
    ];

    /**
     * Lấy tất cả sản phẩm thuộc thương hiệu này
     */
    public function sanPham()
    {
        return $this->hasMany(SanPham::class, 'id_thuonghieu');
    }
    
    /**
     * Lấy số lượng sản phẩm đang còn hàng của thương hiệu
     */
    public function getAvailableProductsCountAttribute()
    {
        return $this->sanPham()->where('So_luong', '>', 0)->count();
    }
    
    /**
     * Scope để lấy các thương hiệu có sản phẩm
     */
    public function scopeHasProducts($query)
    {
        return $query->whereHas('sanPham', function($q) {
            $q->where('So_luong', '>', 0);
        });
    }
} 