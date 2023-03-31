<?php

namespace hlyall01\Backpack\CRUD\Tests\Unit\Models;

use hlyall01\Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use CrudTrait;

    protected $table = 'roles';
    protected $fillable = ['name'];

    /**
     * Get the user for the account details.
     */
    public function user()
    {
        return $this->belongsToMany('Backpack\CRUD\Tests\Unit\Models\User', 'user_role');
    }

    public function getRoleNameAttribute()
    {
        return $this->name.'++';
    }
}
