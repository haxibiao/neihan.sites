<?php

namespace App;

use App\Model;
use App\Traits\PhotoTool;
use App\Helpers\QcloudUtils;

class Image extends Model
{ 
    use PhotoTool;

    protected $fillable = [
        'path',
        'path_origin',
        'path_small',
    ];

    public function articles()
    {
        return $this->belongsToMany('App\Article');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- service ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function fillForJs()
    {
        $this->url       = $this->url();
        $this->url_small = $this->url_small();
    }

    /**
     * @Author      XXM
     * @DateTime    2018-10-25
     * @description [保存外部图片到Cos]
     * @return      [String]        [Image path]
     */
    public function save_image($image_url, $clientName = null)
    {
        ini_set("memory_limit", -1); //为上传文件处理截图临时允许大内存使用
        try{
            $image = \ImageMaker::make($image_url);

            //获取image extension
            $image_mime_arr = explode("/",$image->mime());
            $extension = end($image_mime_arr);
            $this->extension = $extension;
            $this->title = $clientName;
            $filename = $this->id . '.' . $extension;

            //保存原始图片(宽度被处理:最大900)
            if ($extension != 'gif') {
                $big_width = $image->width() > 900 ? 900 : $image->width();
                $image->resize($big_width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $tmp_path = '/tmp/' . $filename; //将处理后的文件保存在系统的临时文件夹(该文件夹下的文件会定期清除)
                $image->save($tmp_path);
                $cos_file_info = QcloudUtils::uploadFile($tmp_path, $filename);
                if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                    throw new \Exception('上传到COS失败');
                }
                $this->hash = md5_file($tmp_path) ?: null;
            } else {
                $cos_file_info = QcloudUtils::uploadFile($file->path(), $filename);
                if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                    throw new \Exception('上传到COS失败');
                }
            }

            $this->path = $cos_file_info['data']['custom_url']; //custom_url为CDN的地址
            $this->width = $image->width();
            $this->height = $image->height();

            //保存轮播图
            if ($image->width() >= 760) {
                if ($extension != 'gif') {
                    $image->crop(760, 327);
                    $top_filename = $this->id . '.top.' . $extension;
                    $tmp_path = '/tmp/' . $top_filename;
                    $image->save($tmp_path);
                    $cos_file_info = QcloudUtils::uploadFile($tmp_path, $top_filename);
                    if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                        throw new \Exception('上传到COS失败');
                    }
                    $this->path_top = $cos_file_info['data']['custom_url'];
                    //git图片存储逻辑后面需要调整
                } else {
                    $this->path_top = $this->path;
                }
            }
            //保存缩略图
            if ($image->width() / $image->height() < 1.5) {
                $image->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image->resize(null, 240, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image->crop(300, 240);
            $small_filename = $this->id . '.small.' . $extension;

            $tmp_path = '/tmp/' . $small_filename;
            $image->save($tmp_path);

            $cos_file_info = QcloudUtils::uploadFile($tmp_path, $small_filename);
            //上传到COS失败
            if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                throw new \Exception('上传到COS失败');
            }

            $this->disk = config("app.name");
            $this->save();

        }catch(\Exception $e){
            return $e->getMessage();
        }

        return $this->path;
    }

}
