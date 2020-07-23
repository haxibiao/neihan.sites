<?php

namespace App;

use App\Traits\CategoryRepo;
use App\Traits\CategoryResolvers;
use  Haxibiao\Question\Category as BaseCategory;

class Category extends BaseCategory
{
    use CategoryRepo;
    use CategoryResolvers;
}
