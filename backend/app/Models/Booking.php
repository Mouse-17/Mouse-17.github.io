<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    
    protected $fillable = [
        'id_kh', 
        'id_san', 
        'id_kg', 
        'Ngay_dat', 
        'Trang_thai',
        'Ten_KH',
        'SDT',
        'Email',
        'Ghi_chu',
        'Tong_tien',
        'phuong_thuc_thanh_toan',
        'trang_thai_thanh_toan',
        'ma_giao_dich',
        'thoi_gian_thanh_toan',
        'ghi_chu_thanh_toan'
    ];

    /**
     * Lấy thông tin khách hàng liên kết với đặt sân
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'id_kh');
    }

    /**
     * Lấy thông tin sân đã đặt
     */
    public function field()
    {
        return $this->belongsTo(San::class, 'id_san');
    }

    /**
     * Lấy thông tin khung giờ đã đặt
     */
    public function timeSlot()
    {
        return $this->belongsTo(KhungGio::class, 'id_kg');
    }
    
    /**
     * Check if booking is confirmed
     */
    public function isConfirmed()
    {
        return $this->Trang_thai == 2;
    }
    
    /**
     * Check if booking is pending
     */
    public function isPending()
    {
        return $this->Trang_thai == 1;
    }
    
    /**
     * Check if booking is cancelled
     */
    public function isCancelled()
    {
        return $this->Trang_thai == 0;
    }

    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function isPaid()
    {
        return $this->trang_thai_thanh_toan == 2;
    }
    
    /**
     * Kiểm tra phương thức thanh toán
     */
    public function getPaymentMethodName()
    {
        $methods = [
            1 => 'Thanh toán tại sân',
            2 => 'Chuyển khoản ngân hàng',
            3 => 'Thanh toán online'
        ];
        
        return $methods[$this->phuong_thuc_thanh_toan] ?? 'Không xác định';
    }
}
