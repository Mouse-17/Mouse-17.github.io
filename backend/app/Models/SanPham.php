<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'ten_san_pham',
        'gia',
        'gia_giam',
        'mo_ta',
        'anh_dai_dien',
        'anh_phu',
        'mau_sac',
        'kich_thuoc',
        'diem_danh_gia',
        'tong_danh_gia',
        'bestseller',
        'view',
        'id_danhmuc',
        'id_thuonghieu'
    ];

    protected $casts = [
        'anh_phu' => 'json',
        'mau_sac' => 'json',
        'kich_thuoc' => 'json',
        'diem_danh_gia' => 'float',
        'tong_danh_gia' => 'integer'
    ];

    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'id_danhmuc');
    }

    public function thuongHieu()
    {
        return $this->belongsTo(ThuongHieu::class, 'id_thuonghieu');
    }

    public function san_pham_mau_size()
    {
        return $this->hasMany(SP_MauSize::class, 'ID_SP');
    }
    
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_sp');
    }
    
    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'ID_SP', 'id');
    }
    
    public function getAverageRatingAttribute()
    {
        $danhGiaAvg = $this->danhGia()->avg('So_sao') ?? 0;
        $ratingsAvg = $this->ratings()->avg('So_sao') ?? 0;
        
        // Nếu có đánh giá từ cả hai bảng, lấy giá trị trung bình
        if ($danhGiaAvg > 0 && $ratingsAvg > 0) {
            return ($danhGiaAvg + $ratingsAvg) / 2;
        }
        
        // Nếu chỉ có một bảng có đánh giá, lấy giá trị đó
        return $danhGiaAvg > 0 ? $danhGiaAvg : $ratingsAvg;
    }
} 