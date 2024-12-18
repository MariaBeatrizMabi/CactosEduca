<?php

namespace App\Models;

use App\Models\Scopes\ManagementSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ManagementSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city_id',
        'location_id',
        'user_id',
    ];

    protected $table = 'management_schools';

    protected static function booted(): void
    {
        static::addGlobalScope(new ManagementSchoolScope);
    }

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (ManagementSchool $model) {
            $model->user->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'school_id');
    }

    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class, 'school_id');
    }

    public function exams(): HasManyThrough
    {
        return $this->hasManyThrough(Exam::class, Student::class, 'school_id', 'student_id');
    }
}