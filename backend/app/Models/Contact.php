<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status'
    ];
    
    /**
     * Check if contact has been responded to
     */
    public function isResponded()
    {
        return $this->status == 'responded';
    }
    
    /**
     * Check if contact is new
     */
    public function isNew()
    {
        return $this->status == 'new';
    }
}
