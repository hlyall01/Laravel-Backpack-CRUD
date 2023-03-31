<?php

namespace hlyall01\Backpack\CRUD\app\Http\Controllers\Operations;

use hlyall01\Backpack\CRUD\app\Exceptions\BackpackProRequiredException;

if (! backpack_pro()) {
    trait BulkCloneOperation
    {
        public function setupBulkCloneOperationDefaults()
        {
            throw new BackpackProRequiredException('BulkCloneOperation');
        }
    }
} else {
    trait BulkCloneOperation
    {
        use \Backpack\Pro\Http\Controllers\Operations\BulkCloneOperation;
    }
}
