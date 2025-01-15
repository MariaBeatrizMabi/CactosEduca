<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyParameterValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'name_to_show',
        'literacy_parameter_id'
    ];

    public function LiteracyParameter(){
        return $this->belongsTo(LiteracyParameter::class);
    }
}
