<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ClassScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = optional(User::find(auth()->user()?->id));

        if (!isset($user) || $user?->isAdmin()) return;

        if ($user?->isTeacher()) $builder->where('teacher_id', $user->teacher->id);
        else if ($user?->isSchool()) $builder->where('school_id', $user->managementSchool->id);
    }
}
