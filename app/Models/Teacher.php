<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'acess_cod', 'password', 'type', 'school_id'];
    
    protected $table = 'teacher';

    public function user() {
        return $this->belongsTo(User::class); 
    }

    public function classTeacher() {
        return $this->belongsTo( ClassModel::class); 
    }

}
