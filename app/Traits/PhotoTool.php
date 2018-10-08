<?php

namespace App\Traits;
use App\Helpers\QcloudUtils;
/**
 * 部分地方的还在引用之前函数命名,所以下面保留了函数命名。后面可以慢慢替换
 */
trait PhotoTool
{
	private static $small    = 1;//小图
	private static $top      = 2;//小图
    
    /**
     * 获取当前环境的APP_URL
     * @return [type] [description]
     */
    public function webAddress(){
        switch (env('APP_ENV')) {
            case 'prod'://线上环境
                return env('APP_URL');
                break;
            case 'staging':
                return sprintf('http://staging.%s',env('APP_DOMAIN'));
                break;
            default:
                break;
        }
    }

    public function url()
    {
        return $this->path();
    }

    public function url_prod()
    {
        return $this->path();
    }

    public function url_small()
    {
        return$this->path_small();
    }

    public function path_small()
    {
        return $this->path(self::$small);
    }

    public function path_top()
    {
        return $this->path(self::$top);
    }

    public function path($size = 0){ 
    	$path = $this->path;
    	if(empty($path)){
    		return null;
    	}
    	$extension 	= pathinfo($path,PATHINFO_EXTENSION); 
    	$folder 	= pathinfo($path,PATHINFO_DIRNAME);
        $url_formater =  $folder .'/'. basename($path, '.' . $extension). '%s' .$extension;
        switch ($size) {
            case 1:
                return sprintf($url_formater, '.small.');
                break;
            case 2:
                return sprintf($url_formater, '.top.');
                break;
            default:
                return $path;
                break;
        }
    }

    /**
     * 保存图片
     * 切换到CDN上传图片文件，如果用户当前的环境为本地环境则不上传
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function save_file($file)
    {
    	ini_set("memory_limit",-1); //为上传文件处理截图临时允许大内存使用
    	//如果当前不是线上的环境则上传到本地
        if(env('APP_ENV')!='prod'){
        	return $this->save_file_to_local($file);
        }
        $extension       = $file->getClientOriginalExtension();
        $this->extension = $extension;
        $this->hash      = md5_file($file->path())?:null;
        $this->title     = $file->getClientOriginalName();
        $filename        = $this->id . '.' . $extension;
        try { 
            $img = \ImageMaker::make($file->path());
            
            //保存原始图片(宽度被处理:最大900)
            if ($extension != 'gif') {
                $big_width = $img->width() > 900 ? 900 : $img->width();
                $img->resize($big_width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $tmp_path = '/tmp/' . $filename;//将处理后的文件保存在系统的临时文件夹(该文件夹下的文件会定期清除)
                $img->save($tmp_path);
                $cos_file_info = QcloudUtils::uploadFile($tmp_path, $filename);
                if(empty($cos_file_info) || $cos_file_info['code'] != 0){
                    throw new \Exception('上传到COS失败');
                }
            } else {
                $cos_file_info = QcloudUtils::uploadFile($file->path(), $filename);
                if(empty($cos_file_info) || $cos_file_info['code'] != 0){
                    throw new \Exception('上传到COS失败');
                }
            }
            $this->path = $cos_file_info['data']['custom_url'];//custom_url为CDN的地址
            $this->width  = $img->width();
            $this->height = $img->height();

            //保存轮播图
            if( $img->width() >= 760 ){
                if( $extension != 'gif' ){
                    $img->crop(760, 327); 
                    $top_filename        = $this->id . '.top.' . $extension;
                    $tmp_path = '/tmp/' . $top_filename;
                    $img->save($tmp_path);
                    $cos_file_info = QcloudUtils::uploadFile($tmp_path, $top_filename); 
                    if(empty($cos_file_info) || $cos_file_info['code'] != 0){
                        throw new \Exception('上传到COS失败');
                    }
                    $this->path_top = $cos_file_info['data']['custom_url'];
                //git图片存储逻辑后面需要调整
                } else {
                    $this->path_top = $this->path;
                }
            }
            //保存缩略图
            if ($img->width() / $img->height() < 1.5) {
                $img->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $img->resize(null, 240, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } 
            $img->crop(300, 240);
            $small_filename        = $this->id . '.small.' . $extension;

            $tmp_path = '/tmp/' . $small_filename;
            $img->save($tmp_path);
            
            $cos_file_info = QcloudUtils::uploadFile($tmp_path, $small_filename);
            //上传到COS失败
            if(empty($cos_file_info) || $cos_file_info['code'] != 0){
                throw new \Exception('上传到COS失败');
            }

            $this->disk = env('DB_SERVER');   
            $this->save();
        } catch (\Excetpion $ex) {
            return $ex;
        }
        return null;
    }
    /**
     * 之前处理图片上传到本地 
     * 后面有时间重构一下相似代码
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    private function save_file_to_local($file){

        $extension       = $file->getClientOriginalExtension();
        $this->extension = $extension;
        $this->hash      = md5_file($file->path())?:null;
        $this->title     = $file->getClientOriginalName();
        $filename        = $this->id . '.' . $extension;
        $local_path            = '/storage/img/' . $filename;
        $this->path      = $this->webAddress() . $local_path;
        $local_dir = public_path('/storage/img/');
        if (!is_dir($local_dir)) {
            mkdir($local_dir, 0777, 1);
        }

        try {
             $img = \ImageMaker::make($file->path());
        } catch (\Excetpion $ex) {
            return $ex;
        }
        if ($extension != 'gif') {
            $big_width = $img->width() > 900 ? 900 : $img->width();
            $img->resize($big_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            //save big
            $img->save(public_path($local_path)); 
        } else {
            $file->move($local_dir, $filename);
        }
        $this->width  = $img->width();
        $this->height = $img->height();
        //save top
        if ($extension != 'gif') {
            if ($img->width() >= 760) {
                $img->crop(760, 327);
                $path_top = '/storage/img/' . $this->id . '.top.' . $extension;
                $this->path_top = $this->webAddress() . $path_top;
                $img->save(public_path($path_top));
             } 
        } else {
            if ($img->width() >= 760) {
                $this->path_top = $this->path;
             }
        }
        //save small
        if ($img->width() / $img->height() < 1.5) {
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio(); 
            });
        } else {
            $img->resize(null, 240, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $img->crop(300, 240);
        $this->disk = "local";
        $img->save(public_path($this->path_small()));
        $this->save();
        return null; 
    }
}
