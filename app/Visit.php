<?php

namespace App;

use App\Traits\VisitAttrsCache;
use App\Traits\VisitRepo;
use App\Traits\VisitResolvers;
use App\Model;

class Visit extends Model
{
    use VisitResolvers;
    use VisitAttrsCache;
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

    public function scopeOfType($query, $value)
    {
        return $query->where('visited_type', $value);
    }

    public function scopeOfUserId($query, $value)
    {
        return $query->where('user_id', $value);
    }

    public static function saveVisits($user, $visits, $visitedType)
    {
        $visitsObj = [];

        foreach ($visits as $visit) {
            $visited = [
                'visited_type' => $visitedType,
                'visited_id'   => $visit->id,
                'user_id'      => $user->id,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
            array_push($visitsObj, $visited);
        }

        return Visit::insert($visitsObj);
    }
}
