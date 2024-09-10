<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ManagementSchoolScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = optional(User::find(auth()->user()?->id));

        if (!isset($user) || $user?->isAdmin()) return;

        if ($user?->isSchool()) $builder->where('user_id', $user->id);
        else if ($user?->isTeacher()) $builder->where('id', $user->teacher->school_id);
    }
}
