<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;

class InvitationPhase extends Model
{
    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'amount',
        'rate',
    ];

    const DEFAULT_PHASE_ID = 1;
}
