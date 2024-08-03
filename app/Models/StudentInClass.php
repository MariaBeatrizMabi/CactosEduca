<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentInClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
    ];

    protected $table = 'student_class';

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Teacher $teacher) {
            $teacher->user->delete();
        });
    }

    public function classData(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function studentsChart(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

}