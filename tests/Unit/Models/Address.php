<?php

namespace hlyall01\Backpack\CRUD\Tests\Unit\Models;

use hlyall01\Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use CrudTrait;

    protected $table = 'addresses';
    protected $fillable = ['city', 'street', 'number'];

    /**
     * Get the author for the article.
     */
    public function accountDetails()
    {
        return $this->belongsTo('Backpack\CRUD\Tests\Unit\Models\AccountDetails', 'account_details_id');
    }

    public function bang()
    {
        return $this->belongsTo('Backpack\CRUD\Tests\Unit\Models\Bang', 'city');
    }
}
