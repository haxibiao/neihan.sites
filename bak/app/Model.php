<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Model;
use App\Traits\TimeAgo;

class Model extends BaseModel
{
	use timeAgo;    
}
