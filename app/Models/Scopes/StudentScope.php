<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StudentScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = optional(User::find(auth()->user()?->id));

        if (!isset($user) || $user?->isAdmin()) return;

        if ($user?->isSchool()) $builder->where('school_id', $user->managementSchool->id);
        else if ($user?->isTeacher()) $builder->where('school_id', $user->teacher->school_id);
    }
}
