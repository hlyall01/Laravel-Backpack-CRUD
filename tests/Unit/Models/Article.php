<?php

namespace hlyall01\Backpack\CRUD\Tests\Unit\Models;

use hlyall01\Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use CrudTrait;

    protected $table = 'articles';
    protected $fillable = ['user_id', 'content', 'metas', 'tags', 'extras', 'cast_metas', 'cast_tags', 'cast_extras'];
    protected $casts = [
        'cast_metas'  => 'object',
        'cast_tags'   => 'object',
        'cast_extras' => 'object',
    ];

    /**
     * Get the author for the article.
     */
    public function user()
    {
        return $this->belongsTo('Backpack\CRUD\Tests\Unit\Models\User');
    }

    public function getContentComposedAttribute()
    {
        return $this->content.'++';
    }
}
