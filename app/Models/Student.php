<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'enrollment_date',
        'comments',
        'school_id',
        'group_id'
    ];

    protected $table = 'students';

    public function classData(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'student_class', 'student_id', 'class_id');
    }
}
