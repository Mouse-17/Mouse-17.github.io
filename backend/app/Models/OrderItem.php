<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'don_hang_chi_tiet';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ID_DH',
        'ID_SP',
        'user_id',
        'color_id',
        'size_id',
        'So_luong',
        'Gia',
        'Thanh_tien',
        'don_gia',
        'hinh_anh'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Đồng bộ giữa Gia và don_gia, và tự động tính Thanh_tien
        static::creating(function($model) {
            // Đồng bộ Gia và don_gia
            if (isset($model->Gia) && !isset($model->don_gia)) {
                $model->don_gia = $model->Gia;
            } else if (isset($model->don_gia) && !isset($model->Gia)) {
                $model->Gia = $model->don_gia;
            }

            // Tính Thanh_tien
            if (isset($model->So_luong) && isset($model->don_gia) && !isset($model->Thanh_tien)) {
                $model->Thanh_tien = $model->So_luong * $model->don_gia;
            }
        });

        static::updating(function($model) {
            // Đồng bộ Gia và don_gia
            if ($model->isDirty('Gia') && !$model->isDirty('don_gia')) {
                $model->don_gia = $model->Gia;
            } else if ($model->isDirty('don_gia') && !$model->isDirty('Gia')) {
                $model->Gia = $model->don_gia;
            }

            // Tính Thanh_tien
            if (isset($model->So_luong) && isset($model->don_gia)) {
                $model->Thanh_tien = $model->So_luong * $model->don_gia;
            }
        });
    }

    /**
     * Get the order this item belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'ID_DH');
    }

    /**
     * Get the product for this order item
     */
    public function product()
    {
        return $this->belongsTo(SanP::class, 'ID_SP');
    }

    /**
     * Get the color for this order item
     */
    public function color()
    {
        return $this->belongsTo(MauSac::class, 'color_id');
    }

    /**
     * Get the size for this order item
     */
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    /**
     * Get the total price for this order item
     */
    public function getTotalAttribute()
    {
        return $this->So_luong * $this->don_gia;
    }

    /**
     * Accessor to ensure Gia and don_gia are always the same
     */
    public function getGiaAttribute($value)
    {
        return $value ?? $this->don_gia;
    }

    /**
     * Mutator to ensure Gia and don_gia are always the same
     */
    public function setGiaAttribute($value)
    {
        $this->attributes['Gia'] = $value;
        $this->attributes['don_gia'] = $value;
    }

    /**
     * Get the user this order item belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
