<?php

namespace App\Models;

use App\Models\Scopes\ClassScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    protected $fillable = [
        'id',
        'name',
        'shift',
        'active',
        'year',
        'school_id',
        'teacher_id'
    ];

    protected $casts = [
        'active' => 'boolean'
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

    public function studentsInClass(): HasMany
    {
        return $this->hasMany(StudentInClass::class, 'class_id', 'id', 'id', 'student_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, StudentInClass::class, 'class_id', 'id', 'id', 'student_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(ManagementSchool::class, 'school_id', 'id');
    }
}

