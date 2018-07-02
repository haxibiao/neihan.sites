<?php

namespace App;

use App\Model;

class Image extends Model
{
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

    public function url()
    {
        if (file_exists(public_path($this->path))) {
            return url($this->path);
        }
        return url_prod();
    }

    public function url_prod()
    {
        return env('APP_URL') . $this->path;
    }

    public function url_small()
    {
        //TODO:: use disk local, hxb, cos ...
        if ($this->source_url) {
            return $this->source_url;
        }
        if ($this->disk == 'hxb') {
            return env('HXB_URL') . $this->path_small();
        }
        if (file_exists(public_path($this->path_small()))) {
            return url($this->path_small());
        }
        return env('APP_URL') . $this->path_small();
    }

    public function path_small()
    {
        return str_replace($this->extension, 'small.' . $this->extension, $this->path);
    }

    public function path_top()
    {
        return str_replace($this->extension, 'top.' . $this->extension, $this->path);
    }

    public function fillForJs()
    {
        $this->path       = $this->url_prod();
        $this->path_small = $this->url_small();
    }

    public function save_file($file)
    {
        $extension       = $file->getClientOriginalExtension();
        $this->extension = $extension;
        $filename        = $this->id . '.' . $extension;
        $this->path      = '/storage/img/' . $filename;

        $local_dir = public_path('/storage/img/');
        if (!is_dir($local_dir)) {
            mkdir($local_dir, 0777, 1);
        }

        $img = \ImageMaker::make($file->path());
        if ($extension != 'gif') {
            $big_width = $img->width() > 900 ? 900 : $img->width();
            $img->resize($big_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            //save big
            $img->save(public_path($this->path));

            $this->width  = $img->width();
            $this->height = $img->height();
        } else {
            $file->move($local_dir, $filename);
        }

        //save top
        if ($extension != 'gif') {
            if ($img->width() >= 760) {
                $img->crop(760, 327);
                $this->path_top = '/storage/img/' . $this->id . '.top.' . $extension;
                $img->save(public_path($this->path_top));
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
    }
}
