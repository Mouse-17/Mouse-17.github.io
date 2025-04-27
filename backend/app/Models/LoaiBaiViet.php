<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiBaiViet extends Model
{
    use HasFactory;
    
    protected $table = 'loai_bai_viet';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'Ten_loai',
        'Mo_ta',
        'slug'
    ];
    
    /**
     * Get posts for this category
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'ID_Loai');
    }
}
