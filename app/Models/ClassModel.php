<?php

namespace App\Models;

use App\Models\Scopes\ClassScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    protected $fillable = [
        'name',
        'year',
        'shift',
        'school_id',            
        'teacher_id'
    ];

    protected $table = 'class';

    protected static function booted(): void
    {
        static::addGlobalScope(new ClassScope);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function studentsChart(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_class', 'class_id', 'student_id');
    }

    public function studentsInClass(): HasMany
    {
        return $this->hasMany(StudentInClass::class, 'class_id', 'id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_class', 'class_id', 'student_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(ManagementSchool::class, 'school_id', 'id');
    }
}