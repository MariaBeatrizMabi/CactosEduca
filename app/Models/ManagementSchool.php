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
        'city_id',
        'location_id',
        'user_id',
    ];

    protected $table = 'management_schools';

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

    public function address(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }
}
