<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function managementSchool(): BelongsTo
    {
        return $this->belongsTo(ManagementSchool::class);
    }

    public function activeClass(): ClassModel
    {
        return $this->classData()->latest()->first();
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
