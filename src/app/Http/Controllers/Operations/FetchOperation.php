<?php

namespace hlyall01\Backpack\CRUD\app\Http\Controllers\Operations;

use hlyall01\Backpack\CRUD\app\Exceptions\BackpackProRequiredException;

if (! backpack_pro()) {
    trait FetchOperation
    {
        public function setupFetchOperationDefaults()
        {
            throw new BackpackProRequiredException('FetchOperation');
        }
    }
} else {
    trait FetchOperation
    {
        use \Backpack\Pro\Http\Controllers\Operations\FetchOperation;
    }
}
