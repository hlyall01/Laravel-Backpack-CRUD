<?php

namespace hlyall01\Backpack\CRUD\Tests\Unit\Models;

use hlyall01\Backpack\CRUD\app\Models\Traits\CrudTrait;

class TestModel extends \Illuminate\Database\Eloquent\Model
{
    use CrudTrait;
}
