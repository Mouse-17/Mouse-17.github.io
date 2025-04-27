<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $table = 'don_hang';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'ID_KH',
        'ho_ten',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'thanh_pho',
        'phuong_xa',
        'ghi_chu',
        'phuong_thuc_thanh_toan',
        'trang_thai_thanh_toan',
        'Trang_thai',
        'Ma_don_hang',
        'Tong_tien',
        'Ngay_mua',
        'id_san_pham',
        'ten_san_pham',
        'ID_Khuyenmai'
    ];
    
    /**
     * Get the user who placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_KH');
    }
    
    /**
     * Get the order items for this order
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'ID_DH');
    }
    
    /**
     * Get the primary product for this order
     */
    public function primaryProduct()
    {
        return $this->belongsTo(SanPham::class, 'id_san_pham');
    }
    
    /**
     * Get the promotion for this order
     */
    public function promotion()
    {
        return $this->belongsTo(KhuyenMai::class, 'ID_Khuyenmai');
    }
    
    /**
     * Check if order is pending
     */
    public function isPending()
    {
        return $this->Trang_thai == 1;
    }
    
    /**
     * Check if order is processing
     */
    public function isProcessing()
    {
        return $this->Trang_thai == 2;
    }
    
    /**
     * Check if order is shipped
     */
    public function isShipped()
    {
        return $this->Trang_thai == 3;
    }
    
    /**
     * Check if order is delivered
     */
    public function isDelivered()
    {
        return $this->Trang_thai == 4;
    }
    
    /**
     * Check if order is cancelled
     */
    public function isCancelled()
    {
        return $this->Trang_thai == 0;
    }
}
