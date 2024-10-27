<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'evaluation_type'];

    public function exam()
    {
        return $this->belongsToMany(Exam::class, 'exams_intervention');
    }
}
