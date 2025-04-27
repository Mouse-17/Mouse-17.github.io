<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class San extends Model
{
    use HasFactory;

    protected $table = 'san';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'Ten_san',
        'Dia_chi',
        'Mo_ta',
        'Hinh_anh',
        'Gia',
        'So_luong',
        'Trang_thai',
        'hot',
        'view',
        'bestseller',
        'ID_Loai',
        'user_id',
    ];

    /**
     * Lấy thông tin loại sân
     */
    public function loaiSan()
    {
        return $this->belongsTo(LoaiSan::class, 'ID_Loai');
    }
    
    /**
     * Lấy danh sách đánh giá của sân
     */
    public function danhGia()
    {
        return $this->hasMany(DanhGiaSan::class, 'ID_San');
    }
    
    /**
     * Lấy danh sách bình luận của sân
     */
    public function binhLuan()
    {
        return $this->hasMany(BinhLuanSan::class, 'ID_San');
    }
    
    /**
     * Lấy danh sách đặt sân
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_san');
    }
    
    /**
     * Lấy thông tin chủ sân
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function isAvailable($date, $time_slot_id)
    {
        // Check if field is available for booking at the specified date and time
        return !$this->bookings()
            ->where('ngay_dat', $date)
            ->where('khung_gio_id', $time_slot_id)
            ->where('trang_thai', 'confirmed')
            ->exists();
    }
}
