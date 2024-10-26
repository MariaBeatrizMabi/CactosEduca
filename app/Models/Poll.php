<?php

namespace App\Models;

use App\Models\Scopes\StudentScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    protected $table = 'poll';

    protected $fillable = [
        'name',
        'school_id',
        'class_id',
        'active',
        'year',
        'poll_number'
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function exam(){
        return $this->hasMany(Exam::class);
    }
}
