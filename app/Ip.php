<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ip extends Model
{
    protected $fillable = [
        'user_id',
        'ipable_type',
        'ipable_id',
        'ip',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ipable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function createIpRecord($type, $id, $userId): Ip
    {
        return Ip::create([
            'ipable_type' => $type,
            'ipable_id'   => $id,
            'user_id'     => $userId,
            'ip'          => getIp(),
        ]);
    }
}
