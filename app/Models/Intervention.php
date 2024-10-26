<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'evaluation_type'];

    public function poll()
    {
        return $this->belongsToMany(Poll::class, 'poll_intervention');
    }
}
