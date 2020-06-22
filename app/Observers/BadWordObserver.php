<?php

namespace App\Observers;

use App\BadWord;
use haxibiao\helpers\BadWordUtils;

class BadWordObserver
{
    public function created(BadWord $badword)
    {
        $badword = $badword->word;
        BadWordUtils::addWord($badword);
    }
}
