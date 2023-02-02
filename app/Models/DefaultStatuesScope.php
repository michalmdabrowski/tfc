<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DefaultStatuesScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->whereIn('status', [Status::OPEN, Status::IN_PROGRESS]);
    }
}
