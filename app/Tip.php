<?php

namespace App;

use App\Model;

class Tip extends Model
{

    public function tipable()
    {
        return $this->morphTo();
    }
}
