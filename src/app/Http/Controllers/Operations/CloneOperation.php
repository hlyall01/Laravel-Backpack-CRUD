<?php

namespace hlyall01\Backpack\CRUD\app\Http\Controllers\Operations;

use hlyall01\Backpack\CRUD\app\Exceptions\BackpackProRequiredException;

if (! backpack_pro()) {
    trait CloneOperation
    {
        public function setupCloneOperationDefaults()
        {
            throw new BackpackProRequiredException('CloneOperation');
        }
    }
} else {
    trait CloneOperation
    {
        use \Backpack\Pro\Http\Controllers\Operations\CloneOperation;
    }
}
