<?php

namespace App\Dongdezhuan;

use App\Exceptions\GQLException;
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
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'array'
    ];



    public function user():BelongsTo
    {
        return $this->belongsTo(\App\Dongdezhuan\User::class);
    }

    public function App():BelongsTo
    {
        return $this->belongsTo(\App\Dongdezhuan\App::class);
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public static function checkApp()
    {
        $appId = App::where('name', config('app.name_cn'))->first();
        throw_if($appId === null, GQLException::class, '当前App没有权限绑定懂得赚哦~');
        return $appId->id;
    }

    public static function checkIsBind(int $userId){
        $appId = self::checkApp();
        return self::where([
            'user_id' => $userId,
            'app_id' => $appId
        ])->exists();
    }

    public static function bind(int $userId){
        $appId = self::checkApp();
        return self::firstOrCreate([
            'user_id' => $userId,
            'app_id' => $appId,
        ]);
    }
}
