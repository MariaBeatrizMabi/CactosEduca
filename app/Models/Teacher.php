<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
        'user_id'
    ];

    protected $table = 'teacher';

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Teacher $teacher) {
            $teacher->user->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classTeacher()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(ManagementSchool::class);
    }
}
