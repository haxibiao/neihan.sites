<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserApp extends Pivot
{

    protected $connection = 'dongdezhuan';

    protected $table = 'user_apps';

    protected $fillable = [
        'user_id',
        'app_id',
        'data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

    public function App(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\App::class);
    }

    public static function checkIsBind(int $userId)
    {
        $appId = App::whereName(config('app.name_cn'))->select('id')->first();
        return self::where([
            'user_id' => $userId,
            'app_id'  => $appId,
        ])->exists();
    }

    public static function bind(int $userId)
    {
        $appId = App::whereName(config('app.name_cn'))->select('id')->first();
        return self::firstOrCreate([
            'user_id' => $userId,
            'app_id'  => $appId,
        ]);
    }
}
