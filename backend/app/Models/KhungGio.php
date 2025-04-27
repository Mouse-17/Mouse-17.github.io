<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhungGio extends Model
{
    use HasFactory;

    protected $table = 'khung_gio';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'Gio_bat_dau',
        'Gio_ket_thuc',
        'Gia_thue',
        'Trang_thai'
    ];
    
    /**
     * Get bookings for this time slot
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_kg');
    }
    
    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute()
    {
        return $this->Gio_bat_dau . ' - ' . $this->Gio_ket_thuc;
    }
}
