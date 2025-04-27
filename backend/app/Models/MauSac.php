<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MauSac extends Model
{
    use HasFactory;
    
    protected $table = 'mau_sac';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'ten_mau',
        'ma_mau'
    ];
    
    /**
     * Get the order details that have this color
     */
    public function donHangChiTiet()
    {
        return $this->hasMany(DonHangChiTiet::class, 'id_mau', 'id');
    }
}
