<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function classData(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'group_id');
    }
}
