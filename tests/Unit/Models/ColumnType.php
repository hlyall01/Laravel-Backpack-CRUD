<?php

namespace hlyall01\Backpack\CRUD\Tests\Unit\Models;

use hlyall01\Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ColumnType extends Model
{
    use CrudTrait;

    protected $table = 'column_types';
}
