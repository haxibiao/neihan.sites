<?php

namespace App;

use App\Model;
use App\Article;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'user_id',
        'path',
        'duration',
        'hash',
        'adstime',
    ];

    public function user() {
        return $this->belongsTo(\App\User::class);
    }

    public function article()
    {
        return $this->hasOne(\App\Article::class);
    }

    public function takeSnapshot($force = false)
    {
        \App\Jobs\TakeScreenshots::dispatch($this, $force);
    }

    public function getPath() {
        //TODO: save this->extension 
        $extension = 'mp4';
        return '/storage/video/' .$this->id . '.' . $extension;
    }

    public function url() {
        if (starts_with($this->path, 'http')) {
            return $this->path;
        }
        return file_exists(public_path($this->path)) ? url($this->path) : env('APP_URL') . $this->path;
    }

    /**
     * @Desc     保存视频文件
     * @Author   czg
     * @DateTime 2018-06-28
     * @return   [type]     [description]
     */
    public function saveFile($file){
        if($file){
            $this   ->user_id = getUserId(); 
            $this   ->save();

            //保存视频地址
            $hash  = md5_file($file->path());
            $extension   = $file->getClientOriginalExtension();
            $filename    = $this->id . '.' . $extension;
            $path        = '/storage/video/' . $filename;
            $this  -> path      = $path;
            $this  -> hash      = $hash;
            $file->move( public_path('/storage/video/'), $filename );
            $this->save();

            //截取图片
            $this->takeSnapshot(true);
            return true;
        }
        return false;
    }
}
