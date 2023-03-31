<?php

namespace hlyall01\Backpack\CRUD\Tests\Unit\Http\Controllers;

use hlyall01\Backpack\CRUD\app\Http\Controllers\CrudController;
use hlyall01\Backpack\CRUD\Tests\Unit\Models\User;

class UserCrudController extends CrudController
{
    use hlyall01\Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use hlyall01\Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use hlyall01\Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    public function setup()
    {
        $this->crud->setModel(User::class);
        $this->crud->setRoute('users');
    }

    public function setupUpdateOperation()
    {
    }

    protected function create()
    {
        return response('create');
    }
}
