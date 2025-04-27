<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $table = 'carts';
    protected $fillable = ['id_kh', 'session_id'];
    
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_kh');
    }
} 