<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
        'email_verified_at',
        'status',
        'otp',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is field owner
     */
    public function isFieldOwner()
    {
        return $this->role === 'field_owner';
    }

    /**
     * Check if user account is active
     */
    public function isActive()
    {
        // Kiểm tra xem bảng có cột status không
        if (Schema::hasColumn('users', 'status')) {
            return $this->status === 'active';
        }
        
        // Nếu không có cột status, coi như tài khoản đang active
        return true;
    }

    /**
     * Check if user email is verified
     */
    public function isVerified()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Get user's bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get fields owned by this user
     */
    public function fields()
    {
        return $this->hasMany(San::class, 'user_id');
    }

    /**
     * Get user's comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get user's ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
