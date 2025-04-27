<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;
    
    protected $table = 'don_hang';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'ID_KH',
        'ID_Khuyenmai',
        'Ngay_mua',
        'Tong_tien',
        'Trang_thai'
    ];
    
    protected $appends = ['ngay_dat'];
    
    public function getNgayDatAttribute()
    {
        return $this->Ngay_mua;
    }
    
    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_KH', 'id');
    }
    
    /**
     * Get the order items
     */
    public function chiTiet()
    {
        return $this->hasMany(DonHangChiTiet::class, 'ID_DH', 'id');
    }
} 