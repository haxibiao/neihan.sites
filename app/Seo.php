<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $fillable = ['group', 'name', 'value'];

    public static function getValue($group, $name)
    {
        $item = self::whereGroup($group)->whereName($name)->first();
        return $item ? $item->value : '';
    }
}
