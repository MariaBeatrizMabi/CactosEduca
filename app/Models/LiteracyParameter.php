<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiteracyParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'literacy_parameter'
    ];

    public function literacyParameterValues(){
        return $this->hasMany(LiteracyParameterValue::class);
    }
}
