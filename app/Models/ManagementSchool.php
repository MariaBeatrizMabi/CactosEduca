<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagementSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'zip_code',
        'user_id'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (ManagementSchool $model) {
            $model->user->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
