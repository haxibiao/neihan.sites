<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{

    public function tipable()
    {
        return $this->morphTo();
    }
}
