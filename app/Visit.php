<?php

namespace App;

use App\Traits\VisitAttrs;
use App\Traits\VisitRepo;
use App\Traits\VisitResolvers;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use VisitResolvers;
    use VisitAttrs;
    use VisitRepo;

    protected $fillable = [
        'user_id',
        'visited_id',
        'visited_type',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function visited()
    {
        return $this->morphTo();
    }

}
