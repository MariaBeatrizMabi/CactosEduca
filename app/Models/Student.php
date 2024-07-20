<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_id', 'group_id'];

    protected $table = 'student';

    public function classData() {
        return $this->belongsTo(ClassModel::class, 'group_id'); 
    }
}
