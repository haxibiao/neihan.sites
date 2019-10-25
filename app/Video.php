<?php

namespace App;

use App\Model;
use App\Traits\VideoAttrs;
use App\Traits\VideoMutator;
use App\Traits\VideoRepo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;
    use VideoMutator;
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
        //同步article status
        static::updated(function ($model) {
            if ($article = $model->article) {
                $article->status = $model->status;
            }
        });
    }

    public function getPath()
    {
        //TODO: save this->extension, 但是目前基本都是mp4格式
        $extension = 'mp4';
        return '/storage/video/' . $this->id . '.' . $extension;
    }

}
