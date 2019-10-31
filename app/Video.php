<?php

namespace App;

use App\Model;
use App\Traits\VideoAttrs;
use App\Traits\VideoRepo;
use App\Traits\VideoResolvers;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;
    use VideoResolvers;
    use VideoAttrs;
    use VideoRepo;

    protected $fillable = [
        'title',
        'user_id',
        'path',
        'duration',
        'json',
        'cover',
        'hash',
        'disk',
        'qcvod_fileid',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function article()
    {
        return $this->hasOne(\App\Article::class);
    }

    public static function boot()
    {
        parent::boot();

        /**
         * 注释原因：在保存视频时，视频对象还没有封面
         */

        // //自动通过封面获得视频宽高
        // static::created(function ($video) {
        //     $coverPath = parse_url($video->cover, PHP_URL_PATH);
        //     $video->saveWidthHeight(public_path($coverPath));
        // });

        //FIXME:视频截图成功，同步发布文章上线? 现在这个需要审核了
        // static::updated(function ($model) {
        //     if ($article = $model->article) {
        //         $article->status = $model->status;
        //     }
        // });
    }

}
