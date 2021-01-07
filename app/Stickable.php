<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stickable extends Model
{
    protected $fillable = [
        'name',
        'page',
        'area',
        'item_type',
        'item_id',
    ];

    public function item()
    {
        return $this->morphTo('item');
    }

    public function getSubjectAttribute()
    {
        return $this->name;
    }

    public static function items($sticks)
    {
        $result = [];
        foreach ($sticks as $stick) {
            $result[] = $stick->item;
        }
        return $result;
    }
}
