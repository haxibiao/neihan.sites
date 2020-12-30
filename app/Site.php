<?php

namespace App;

use Haxibiao\Cms\Site as BaseSite;

class Site extends BaseSite
{
    public $casts = [
        'json' => 'array',
    ];
}
