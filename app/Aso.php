<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aso extends Model
{
    protected $fillable = ['group', 'name', 'value'];

    public static function getValue($group, $name)
    {
        $item = \App\Aso::whereGroup($group)->whereName($name)->first();
        return $item ? $item->value : '';
    }
}
