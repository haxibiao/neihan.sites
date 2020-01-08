<?php

namespace App\DDZ;

use App\Exceptions\GQLException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Throwable;

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
        'data' => 'array'
    ];



    public function user():BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

    public function App():BelongsTo
    {
        return $this->belongsTo(\App\DDZ\App::class);
    }

    /**
     * @return mixed
     * @throws Throwable
     */
    public static function checkApp()
    {
        $appId = App::where('name', config('app.name_cn'))->first();
        if ($appId !== null) {
            return $appId->id;
        }
        return null;
    }

    public static function checkIsBind(int $userId){
        if ($appId = self::checkApp()) {
            return self::where([
                'user_id' => $userId,
                'app_id' => $appId
            ])->exists();
        }
//        throw new GQLException('当前APP没有权限绑定懂得赚哦~');
    }

    public static function bind(int $userId){
        if ($appId = self::checkApp()){
            return self::firstOrCreate([
                'user_id' => $userId,
                'app_id' => $appId,
            ]);
        }

//        throw new GQLException('当前APP没有权限绑定懂得赚哦~');
    }
}
