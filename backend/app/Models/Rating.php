<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    
    protected $table = 'danh_gia';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_kh',
        'id_sp',
        'san_id',
        'So_sao',
        'Noi_dung',
        'Trang_thai'
    ];
    
    /**
     * Get the user who created the rating
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_kh');
    }
    
    /**
     * Get the product being rated
     */
    public function product()
    {
        return $this->belongsTo(SanPham::class, 'id_sp');
    }
    
    /**
     * Get the field being rated
     */
    public function field()
    {
        return $this->belongsTo(San::class, 'san_id');
    }
    
    /**
     * Scope để lấy chỉ các đánh giá đã được phê duyệt
     */
    public function scopeApproved($query)
    {
        return $query->where('Trang_thai', 1);
    }
}
