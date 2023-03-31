<?php

namespace hlyall01\Backpack\CRUD\app\Http\Controllers\Operations;

use hlyall01\Backpack\CRUD\app\Exceptions\BackpackProRequiredException;

if (! backpack_pro()) {
    trait BulkDeleteOperation
    {
        public function setupBulkDeleteOperationDefaults()
        {
            throw new BackpackProRequiredException('BulkDeleteOperation');
        }
    }
} else {
    trait BulkDeleteOperation
    {
        use \Backpack\Pro\Http\Controllers\Operations\BulkDeleteOperation;
    }
}
