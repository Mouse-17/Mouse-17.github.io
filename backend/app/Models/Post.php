<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    protected $table = 'bai_viet';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'Tieu_de',
        'Noi_dung',
        'ID_Loai',
        'thumbnail',
        'user_id',
        'slug',
        'status'
    ];
    
    /**
     * Get the category of the post
     */
    public function category()
    {
        return $this->belongsTo(LoaiBaiViet::class, 'ID_Loai');
    }
    
    /**
     * Get comments for this post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'bai_viet_id');
    }
    
    /**
     * Get the author of the post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
