<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    protected $table = 'binh_luan';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_kh',
        'id_sp',
        'bai_viet_id',
        'san_id',
        'Noi_dung',
        'Trang_thai'
    ];
    
    /**
     * Get the user who made the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_kh');
    }
    
    /**
     * Get the post associated with the comment
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'bai_viet_id');
    }
    
    /**
     * Get the product associated with the comment
     */
    public function product()
    {
        return $this->belongsTo(SanPham::class, 'id_sp');
    }
    
    /**
     * Get the field associated with the comment
     */
    public function field()
    {
        return $this->belongsTo(San::class, 'san_id');
    }
}
