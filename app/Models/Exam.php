<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'reading',
        'writing',
        'action',
        'student_id',
        'class_id',
        'poll_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
    
    public function intervention()
    {
        return $this->belongsToMany(Intervention::class, 'exams_intervention');
    }
  
    public function poll(){
        return $this->belongsTo(Poll::class, 'poll_id', 'id');
    }
}
