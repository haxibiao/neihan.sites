<?php

namespace App;

use App\Traits\CategoryRepo;
use  Haxibiao\Question\Category as BaseCategory;

class Category extends BaseCategory
{
    use CategoryRepo;
}
