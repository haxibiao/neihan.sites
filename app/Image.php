<?php

namespace App;

use App\Helpers\QcloudUtils;
use App\Model;
use App\Traits\ImageAttrs;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use ImageAttrs;

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

    //repo TODO: 待重构
    public function fillForJs()
    {
        $this->url       = $this->url;
        $this->url_small = $this->thumbnail;
    }

    public function save_image($image_url, $clientName = null)
    {
        ini_set("memory_limit", -1); //为上传文件处理截图临时允许大内存使用
        try {
            $image = \ImageMaker::make($image_url);

            //获取image extension
            $image_mime_arr  = explode("/", $image->mime());
            $extension       = end($image_mime_arr);
            $this->extension = $extension;
            $this->title     = $clientName;
            $filename        = $this->id . '.' . $extension;

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

            $this->path   = $cos_file_info['data']['custom_url']; //custom_url为CDN的地址
            $this->width  = $image->width();
            $this->height = $image->height();

            //保存轮播图
            if ($image->width() >= 760) {
                if ($extension != 'gif') {
                    $image->crop(760, 327);
                    $top_filename = $this->id . '.top.' . $extension;
                    $tmp_path     = '/tmp/' . $top_filename;
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
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $this->path;
    }

    public static function saveImage($source)
    {
        ini_set('memory_limit', -1); //上传时允许最大内存

        if ($base64 = self::matachBase64($source)) {
            $source = $base64;
        }
        $imageMaker = \ImageMaker::make($source);
        $mime       = explode('/', $imageMaker->mime());
        $extension  = end($mime);
        if (empty($extension)) {
            throw new \UserException('上传失败');
        }
        //随机文件名
        $imageName = uniqid();

        //保存原始图片
        $image = \ImageMaker::make($source);
        if ($extension != 'gif') {
            $big_width = $image->width() > 900 ? 900 : $image->width();
            $image->resize($big_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $image->encode($extension, 100);
        Storage::cloud()->put('images/' . $imageName . '.' . $extension, $image->__toString());

        //保存缩略图
        $thumbnail = \ImageMaker::make($source);
        if ($thumbnail->width() / $thumbnail->height() < 1.5) {
            $thumbnail->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $thumbnail->resize(null, 240, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $thumbnail->crop(300, 240);
        $thumbnail->encode($extension, 100);
        Storage::cloud()->put('images/' . $imageName . '.small.' . $extension, $thumbnail->__toString());

        //使用原图hash
        $hash = hash_file('md5', Storage::cloud()->url('images/' . $imageName . '.' . $extension));

        //hash值匹配直接返回当前image对象
        $image = self::firstOrNew([
            'hash' => $hash,
        ]);
        if (!empty($image->id)) {
            return $image;
        }

        //写入一条新记录
        $image->extension = $extension;
        $image->user_id   = getUserId();
        $image->width     = $imageMaker->width();
        $image->height    = $imageMaker->height();
        $image->path      = 'images/' . $imageName . '.' . $extension;
        $image->disk      = config('filesystems.cloud');
        $image->save();

        return $image;
    }

    public static function matachBase64($source)
    {
        //匹配base64
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $source, $res)) {
            // $extension = $res[2];
            //替换base64头部信息
            $base64_string = str_replace($res[1], '', $source);
            return base64_decode($base64_string);
        }
    }
}
