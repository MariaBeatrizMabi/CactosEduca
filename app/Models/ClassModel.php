<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_id', 'teacher_id'];
    
    protected $table = 'class';

    public function user() {
        return $this->belongsTo(User::class); 
    }
}
