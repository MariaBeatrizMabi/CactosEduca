<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cities extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'name',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(ManagementSchool::class, 'id');
    }
}
