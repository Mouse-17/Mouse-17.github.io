<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loai_san extends Model
{
    use HasFactory;
    
    protected $table = 'loai_san';
    protected $primaryKey = 'id';
    protected $fillable = [
        'Ten_loai',
        'Trang_thai',
        'Mo_ta'
    ];
    
    public function sans()
    {
        return $this->hasMany(San::class, 'ID_Loai');
    }
}
