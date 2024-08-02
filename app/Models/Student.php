<?php

namespace App\Models;

use App\Models\Scopes\StudentScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'enrollment_date',
        'enrollment',
        'people_with_disabilities',
        'school_id'
    ];

    protected $casts = [
        'people_with_disabilities' => 'boolean'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new StudentScope);
    }

    public function classData(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'student_class', 'student_id', 'class_id');
    }
        

    public function managementSchool(): BelongsTo
    {
        return $this->belongsTo(ManagementSchool::class);
    }

    public function activeClass(): ?ClassModel
    {
        return $this->classData()->where('active', true)->latest()->first();
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
