<?php

namespace hlyall01\Backpack\CRUD\app\Http\Controllers\Operations;

use hlyall01\Backpack\CRUD\app\Exceptions\BackpackProRequiredException;

if (! backpack_pro()) {
    trait InlineCreateOperation
    {
        public function setupInlineCreateOperationDefaults()
        {
            throw new BackpackProRequiredException('InlineCreateOperation');
        }
    }
} else {
    trait InlineCreateOperation
    {
        use \Backpack\Pro\Http\Controllers\Operations\InlineCreateOperation;
    }
}
