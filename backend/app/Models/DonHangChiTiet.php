<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHangChiTiet extends Model
{
    use HasFactory;
    
    protected $table = 'don_hang_chi_tiet';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'ID_DH',
        'ID_SP',
        'So_luong',
        'Thanh_tien'
    ];
    
    /**
     * Get the order that owns the item
     */
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ID_DH', 'id');
    }
    
    /**
     * Get the product
     */
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ID_SP', 'id');
    }
    
    /**
     * Get the color
     */
    public function mauSac()
    {
        return $this->belongsTo(MauSac::class, 'id_mau', 'id');
    }
    
    /**
     * Get the size
     */
    public function size()
    {
        return $this->belongsTo(Size::class, 'id_size', 'id');
    }
} 