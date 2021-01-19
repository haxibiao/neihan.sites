<?php

namespace App\Observers;

use Haxibiao\Breeze\BadWord;
use Haxibiao\Helpers\utils\BadWordUtils;

class BadWordObserver
{
    public function created(BadWord $badword)
    {
        $badword = $badword->word;
        BadWordUtils::addWord($badword);
    }
}
