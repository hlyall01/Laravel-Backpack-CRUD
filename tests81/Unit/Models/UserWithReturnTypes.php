<?php

namespace hlyall01\Backpack\CRUD\Tests81\Unit\Models;

class UserWithReturnTypes extends \hlyall01\Backpack\CRUD\Tests\Unit\Models\User
{
    public function isAnAttribute(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return false;
    }

    public function isARelation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->bang();
    }
}
