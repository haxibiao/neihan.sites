<?php

namespace App;

use App\Notifications\MyOwnResetPassword as ResetPasswordNotification;
use App\Traits\TimeAgo;
use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use App\Helpers\QcloudUtils;

class User extends Authenticatable
{
    use Notifiable, TimeAgo;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email',
        'password',
        'qq',
        'gender',
        'avatar',
        'introduction',
        'website',
        'is_editor',
        'is_signed',
        'seo_meta',
        'seo_push',
        'seo_tj',
        'api_token',
        'enable_tips',
        'tip_words',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function feedbacks() {
        return $this->hasMany(\App\Feedback::class);
    }
    //relations
    public function questions()
    {
        return $this->hasMany(\App\Question::class);
    }
    public function answers()
    {
        return $this->hasMany(\App\Answer::class);
    }
    public function querylogs()
    {
        return $this->hasMany(\App\Querylog::class);
    }

    public function transactions()
    {
        return $this->hasMany(\App\Transaction::class);
    }

    public function favorites()
    {
        return $this->hasMany(\App\Favorite::class);
    }

    public function favoritedArticles()
    {
        return $this->hasMany(\App\Favorite::class)->where('faved_type', 'articles');
    }

    public function likes()
    {
        return $this->hasMany(\App\Like::class);
    }

    public function likedArticles()
    {
        return $this->hasMany(\App\Like::class)->where('liked_type', 'articles');
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function followings()
    {
        return $this->hasMany(\App\Follow::class);
    }

    public function followingCategories()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'categories');
    }

    public function followingCollections()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'collections');
    }

    public function followingUsers()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'users');
    }

    public function collections()
    {
        //默认给个文集...
        if (!Collection::where('user_id', $this->id)->count()) {
            $collection = Collection::firstOrNew([
                'user_id' => $this->id,
                'name'    => '日记本',
            ]);
            $collection->save();
            $this->count_collections = 1;
        }

        return $this->hasMany(\App\Collection::class);
    }

    public function actions()
    {
        return $this->hasMany(\App\Action::class);
    }

    public function chats()
    {
        return $this->belongsToMany(\App\Chat::class)
            ->withPivot('with_users', 'unreads')
            ->orderBy('updated_at', 'desc');
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    public function drafts()
    {
        return $this->hasMany(\App\Article::class)->where('status', 0);
    }

    public function articles()
    {
        return $this->hasMany(\App\Article::class)
            ->where('status', '>', 0)
            ->exclude(['body', 'json']);
    }

    public function removedArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', -1);
    }

    public function allArticles()
    {
        return $this->hasMany(\App\Article::class)
            ->exclude(['body', 'json']);
    }

    public function allVideoPosts()
    {
        return $this->allArticles()->where('type', 'video');
    }

    public function publishedArticles()
    {
        return $this->allArticles()->where('status', '>', 0);
    }

    public function videoPosts()
    {
        return $this->publishedArticles()->where('type', 'video');
    }

    public function adminCategories()
    {
        return $this->belongsToMany(\App\Category::class)->where('type', 'article')->wherePivot('is_admin', 1);
    }

    public function requestCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 0);
    }

    public function joinCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 1);
    }

    public function hasManyCategories()
    {
        return $this->hasMany(\App\Category::class, 'user_id', 'id')->where('type', 'article');
    }

    //computed props methods ................................................................................

    public function checkAdmin()
    {
        return $this->is_admin;
    }

    public function checkEditor()
    {
        return $this->is_editor || $this->is_admin;
    }

    public function isFollow($type, $id)
    {
        return $this->followings()->where('followed_type', get_polymorph_types($type))->where('followed_id', $id)->count() ? true : false;
    }

    public function isLiked($type, $id)
    {
        return $this->likes()->where('liked_type', get_polymorph_types($type))->where('liked_id', $id)->count() ? true : false;
    }

    public function avatar()
    {
        return $this->avatar;
        //修复没设置默认头像的
        // if (empty($this->avatar)) {
        //     $this->avatar = '/images/avatar.jpg';
        //     $this->save();
        // }

        // $avatar_url = $this->avatar;
        // if (\App::environment('local')) {
        //     if (!starts_with($this->avatar, env('APP_URL'))) {
        //         $avatar_url = file_exists(public_path($this->avatar)) ? $this->avatar : env('APP_URL') . $this->avatar;
        //     }
        // }
        // //如果用户刚更新过，刷新头像图片的浏览器缓存
        // if ($this->updated_at && $this->updated_at->addMinutes(2) > now()) {
        //     $avatar_url = $avatar_url . '?t=' . time();
        // }
        // //APP 需要返回全Uri
        // return starts_with($avatar_url, 'http') ? $avatar_url : url($avatar_url);
    }

    public function makeQQAvatar()
    {
        //尝试读取qq头像
        if (empty($this->email)) {
            $qq = $this->qq;
            if (empty($qq)) {
                $pattern = '/(\d+)\@qq\.com/';
                if (preg_match($pattern, strtolower($this->email), $matches)) {
                    $qq = $matches[1];
                }
            }
            if (!empty($qq)) {
                $avatar_path = '/storage/avatar/' . $this->id . '.qq.jpg';
                $qq_pic      = get_qq_pic($qq);
                $qq_img_data = @file_get_contents($qq_pic);
                if ($qq_img_data) {
                    file_put_contents(public_path($avatar_path), $qq_img_data);
                    $hash = md5_file(public_path($avatar_path));
                    if ($hash != md5_file(public_path('/images/qq_default.png')) && $hash != md5_file(public_path('/images/qq_tim_default.png'))) {
                        $this->avatar = $avatar_path;
                    }
                }
            }
        }
        $this->save();

        return $this->avatar;
    }

    public function unreads($type = null, $num = null)
    {
        $unreads = Cache::get('unreads_' . $this->id);
        //缓存未命中
        if (!$unreads) {
            $unreadNotifications = $this->unreadNotifications()->get();
            $unreads             = [
                'comments' => null,
                'likes'    => null,
                'follows'  => null,
                'tips'     => null,
                'others'   => null,
            ];
            //下列通知类型是进入了notification表的
            $unreadNotifications->each(function ($item) use (&$unreads) {
                switch ($item->type) {
                    //评论文章通知
                    case 'App\Notifications\ArticleCommented':
                        $unreads['comments']++;
                        break;
                    //喜欢文章通知
                    case 'App\Notifications\ArticleLiked':
                        $unreads['likes']++;
                        break;
                    //关注用户通知
                    case 'App\Notifications\UserFollowed':
                        $unreads['follows']++;
                        break;
                    //打赏文章通知
                    case 'App\Notifications\ArticleTiped':
                        $unreads['tips']++;
                        break;
                    //其他类型的通知
                    default:
                        $unreads['others']++;
                        break;
                }

            });
            //聊天消息数
            $unreads['chats'] = $this->chats->sum(function ($item) {
                return $item->pivot->unreads;
            });
            //投稿请求数
            $unreads['requests'] = $this->adminCategories()->sum('new_requests');

            //write cache
            Cache::put('unreads_' . $this->id, $unreads, 60);
        }
        if ($num) {
            $unreads[$type] = $num;
            //write cache
            Cache::put('unreads_' . $this->id, $unreads, 60);
        }
        if ($type) {
            return $unreads[$type] ? $unreads[$type] : null;
        }

        return $unreads;
    }

    public function forgetUnreads()
    {
        Cache::forget('unreads_' . $this->id);
    }

    public function newReuqestCategories()
    {
        return $this->adminCategories()->orderBy('new_requests', 'desc')->orderBy('updated_at', 'desc');
    }

    public function link()
    {
        return '<a href="/user/' . $this->id . '">' . $this->name . '</a>';
    }

    public function at_link()
    {
        return '<a href="/user/' . $this->id . '">@' . $this->name . '</a>';
    }

    public function balance()
    {
        $balance = 0;
        $last    = $this->transactions()->orderBy('id', 'desc')->first();
        if ($last) {
            $balance = $last->balance;
        }
        return $balance;
    }

    public function introduction()
    {
        $intro = $this->introduction;
        return empty($intro) ? '这个人很懒，一点介绍都没留下...' : $intro;
    }

    public function ta()
    {
        return $this->isSelf() ? '我' : '他';
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->id;
    }

    public function fillForJs()
    {
        $this->introduction = $this->introduction();
        $this->avatar       = $this->avatar();
    }

    public function blockedUsers()
    {
        $json = json_decode($this->json, true);
        if (empty($json)) {
            $json = [];
        }

        $blocked = [];
        if (isset($json['blocked'])) {
            $blocked = $json['blocked'];
        }
        return $blocked;
    }

    public function blockUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $json = json_decode($this->json, true);
        if (empty($json)) {
            $json = [];
        }

        $blocked = [];
        if (isset($json['blocked']) && is_array($json['blocked'])) {
            $blocked = $json['blocked'];
        }

        $blocked = new \Illuminate\Support\Collection($blocked);

        if ($blocked->contains('id', $user_id)) {
            //unbloock
            $blocked = $blocked->filter(function ($value, $key) use ($user_id) {
                return $value['id'] != $user_id;
            });
        } else {
            $blocked[] = [
                'id'     => $user->id,
                'name'   => $user->name,
                'avatar' => $user->avatar(),
            ];
        }

        $json['blocked'] = $blocked;
        $this->json      = json_encode($json, JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    public function report($type, $reason, $comment_id = null)
    {
        $this->count_reports = $this->count_reports + 1;

        $json = json_decode($this->json);
        if (!$json) {
            $json = (object) [];
        }

        $user    = getUser();
        $reports = [];
        if (isset($json->reports)) {
            $reports = $json->reports;
        }

        $report_data = [
            'type'   => $type,
            'reason' => $reason,
        ];
        if ($comment_id) {
            $report_data['comment_id'] = $comment_id;
        }
        $reports[] = [
            $user->id => $report_data,
        ];

        $json->reports = $reports;
        $this->json    = json_encode($json, JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    public function reports()
    {
        $json = json_decode($this->json, true);
        if (empty($json)) {
            $json = [];
        }
        $reports = [];
        if (isset($json['reports'])) {
            $reports = $json['reports'];
        }
        return $reports;
    }

    public function transfer($amount, $to_user, $log_mine = '转账', $log_theirs = '转账', $relate_id = null, $type = "打赏")
    {
        if ($this->balance() < $amount) {
            return false;
        }

        \DB::beginTransaction();

        try {
            \App\Transaction::create([
                'user_id'      => $this->id,
                'relate_id'    => $relate_id,
                'from_user_id' => $this->id,
                'to_user_id'   => $to_user->id,
                'type'         => $type,
                'log'          => $log_mine,
                'amount'       => $amount,
                'status'       => '已到账',
                'balance'      => $this->balance() - $amount,
            ]);
            \App\Transaction::create([
                'user_id'      => $to_user->id,
                'relate_id'    => $relate_id,
                'from_user_id' => $this->id,
                'to_user_id'   => $to_user->id,
                'type'         => $type,
                'log'          => $log_theirs,
                'amount'       => $amount,
                'status'       => '已到账',
                'balance'      => $to_user->balance() + $amount,
            ]);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return false;
        }

        \DB::commit();
        return true;
    }

    public function videoArticles()
    {
        return $this->hasMany('App\Article')
            ->where('articles.type', 'video');
    }

    //重写用户的重置密码邮件通知
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    /**
     * 保存用户头像
     * @return [type] [description]
     */
    public function save_avatar($file){
        //判断是否为空
        if(empty($file) || !$file->isValid()){
            return null; 
        } 
        // 获取文档相关信息 
        $extension = $file->getClientOriginalExtension();
        $realPath  = $file->getRealPath();   //临时文档的绝对路径
        $img       = \ImageMaker::make($realPath);
        $img->fit(100, 100);
        $img->encode($extension, 60);//头像的图片质量可以稍微低一点
        $filename = $this->id . '_' .time().'.'.$extension;
        $tmp_path = '/tmp/' . $filename;
        $img->save($tmp_path);//保存到临时文件夹
        $cos_file_info = QcloudUtils::uploadFile($tmp_path, $filename,'avatar');
        //上传到COS失败
        if(empty($cos_file_info) || $cos_file_info['code'] != 0){
            return null;
        }
        $this->update([
            'avatar' =>   $cos_file_info['data']['custom_url'],
        ]);
        return $this->avatar;
    }
    /**
     * 创建用户
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function createUser($input){
        $this->email        = $input['email'];
        $this->name         = $input['name']; 
        $this->password     = bcrypt($input['password']);
        $avatar_formatter = 'http://cos.' . env('DB_DATABASE') . '/storage/avatar/avatar-%d.jpg';
        $this->avatar = sprintf($avatar_formatter,rand(1, 15));
        $this->api_token    = str_random(60);
        $this->introduction = '';
        $this->save();
        //record signUp action
        $action = \App\Action::create([
            'user_id'         => $this->id,
            'actionable_type' => 'users',
            'actionable_id'   => $this->id,
        ]);
        return $this;  
    }
}
