<?php

namespace App;

use Haxibiao\Content\Tag as BaseTag;

class Tag extends BaseTag
{
    use \App\Traits\Searchable;

    protected $searchable = [
        'columns' => [
            'tags.name' => 1,
        ],
    ];

}
