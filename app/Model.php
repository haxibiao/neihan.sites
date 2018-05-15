<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Traits\TimeAgo;
use App\Traits\UserRelated;

class Model extends BaseModel
{
	use timeAgo; 
	use UserRelated;
}
