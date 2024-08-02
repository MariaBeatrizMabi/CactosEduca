<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use App\Models\Scopes\StudentScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

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
