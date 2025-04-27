<?php
   
   namespace App\Models;
   
   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;
   
   class CartItem extends Model
   {
       use HasFactory;
       
       protected $table = 'cart_items';
       protected $fillable = ['cart_id', 'id_sp', 'so_luong', 'id_mau', 'id_size', 'don_gia'];
       
       public function product()
       {
           return $this->belongsTo(SanPham::class, 'id_sp');
       }
       
       public function color()
       {
           return $this->belongsTo(MauSac::class, 'id_mau');
       }
       
       public function size()
       {
           return $this->belongsTo(Size::class, 'id_size');
       }
   }