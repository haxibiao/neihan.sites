<?php

namespace App\Nova\Actions;

use App\Dongdezhuan\App;
use App\OAuth;
use App\User;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Nova;

class BindDongdezhuanAccount extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '绑定懂得赚账号';

    public function uriKey()
    {
        return str_slug(Nova::humanize($this));
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     * @throws \Exception
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count()>1){
            return Action::danger('此操作不可以操作多个用户!');
        }

        $app = App::whereName(config('app.name_cn'))->first();
        $user = \App\User::find($models->first()->id);
        $ddzUser = \App\Dongdezhuan\User::where('phone',$fields->ddz_account)->first();

        if ($ddzUser === null){
            return Action::danger('懂得赚内此手机号不存在,请检查是否输入正确');
        }

        if (!password_verify($fields->ddz_password, $ddzUser->password)) {
            return Action::danger('懂得赚账号或者密码不正确');
        }

//        2.解绑
        try {
            \DB::beginTransaction();

            if ($user->checkUserIsBindDongdezhuan()){
                \App\Dongdezhuan\UserApp::whereUserId($ddzUser->id)->where('app_id',$app->id)->delete();
                \App\OAuth::whereUserId($user->id)->where('oauth_type','dongdezhuan')->delete();
            }

            \App\OAuth::where('oauth_type', 'dongdezhuan')->where('oauth_id', $ddzUser->id)->delete();

            if ($ddzUser->apps()->where('app_id',$app->id)->first() !== null){
                \App\Dongdezhuan\UserApp::whereUserId($ddzUser->id)->where('app_id',$app->id)->delete();
            }

//            3.绑定
            $user->bindDongdezhuanUser($user,$ddzUser);

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            return Action::danger($exception->getMessage());
        }

    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('懂得赚账号','ddz_account'),
            Text::make('懂得赚密码','ddz_password'),
        ];
    }
}
