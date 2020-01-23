<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'user_id',
        'firends_total_contribute',
        'success_firends_count',
        'firends_count',
        'phase_id',
        'rate',
        'next_increment_at',
        'red_packet_phase_amount',
        'red_packet_invites_count',
    ];

    protected $casts = [
        'next_increment_at' => 'datetime',
    ];

    public function scopeUserId($query, $value)
    {
        return $query->where('user_id', $value);
    }

}
