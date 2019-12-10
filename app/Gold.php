<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gold extends Model
{
    protected $fillable = [
        'user_id',
        'gold',
        'wallet_id',
        'balance',
        'remark',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class);
    }

    //repo
    public static function makeOutcome($user, $gold, $remark)
    {

        $goldBalance = $user->gold - $gold;
        $gold        = Gold::create([
            'user_id'   => $user->id,
            'wallet_id' => $user->goldWallet->id,
            'gold'    => -$gold,
            'balance' => $goldBalance,
            'remark'  => $remark,
        ]);
        //更新user表上的冗余字段
        User::where('id', $user->id)->update(['gold' => $goldBalance]);

        return $gold;
    }

    public static function makeIncome($user, $gold, $remark)
    {
        $goldBalance = $user->gold + $gold;
        $gold        = Gold::create([
            'user_id' => $user->id,
            'wallet_id' => $user->goldWallet->id,
            'gold'    => $gold,
            'balance' => $goldBalance,
            'remark'  => $remark,
        ]);
        //更新user表上的冗余字段
        User::where('id', $user->id)->update(['gold' => $goldBalance]);

        return $gold;
    }

    public function resolveGolds($rootValue, array $args, $context, $resolveInfo){
        app_track_user("查看账单", 'list_golds', getUserId());
        return Gold::orderBy('id', 'desc')->where('user_id',$args['user_id']);
    }
}
