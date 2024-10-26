<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollIntervention extends Model
{
    use HasFactory;

    protected $fillable = ['poll_id', 'intervention_id'];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}
