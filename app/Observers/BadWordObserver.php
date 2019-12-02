<?php

namespace App\Observers;

use App\BadWord;
use App\Helpers\BadWord\BadWordUtils;

class BadWordObserver
{
    public function created(BadWord $badword)
    {
        $badword = $badword->word;
        BadWordUtils::addWord($badword);
    }
}
