<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExamsIntervention extends Model
{
    use HasFactory;
    protected $table = 'exams_intervention';
    protected $fillable = ['exam_id', 'intervention_id', 'created_at', 'updated_at'];


    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}
