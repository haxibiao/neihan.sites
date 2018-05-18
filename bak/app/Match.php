<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;

class Match extends Model
{
    protected $fillable = [
        'compare_id',
        'round',
        'type',
        'score',
        'winner',
        'TA',
        'TB',
        'start_at',
    ];

    public function compare()
    {
        return $this->belongsTo(\App\Compare::class);
    }

    public function TA()
    {
        return Team::find($this->TA)->name;
    }

    public function TB()
    {
        return Team::find($this->TB)->name;
    }
}
