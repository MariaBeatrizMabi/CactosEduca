<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'enrollment_date',
        'enrollment',
        'school_id'
    ];

    protected $table = 'students';

    public function classData(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'student_class', 'student_id', 'class_id');
    }
}
