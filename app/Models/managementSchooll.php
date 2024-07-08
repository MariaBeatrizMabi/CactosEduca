<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class managementSchooll extends Model
{
    use HasFactory; 

    protected $fillable = ['name', 'address', 'city', 'zip_code', 'acess_cod', 'password', 'type', 'user_id'];
    
    public function user() {
        return $this->belongsTo(User::class); 
    }
}