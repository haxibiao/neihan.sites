<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aso extends Model
{
    protected $fillable = ['group', 'name', 'value'];

    public static function getValue($group, $name)
    {
        $item = \App\Aso::whereGroup($group)->whereName($name)->first();
        return $item ? $item->value : '';
    }

    public function saveDownloadImage($file, $name)
    {

        if ($file) {
            $aso      = Aso::where('name', $name)->first();
            $aso_path = $aso->value;
            
            
            if(\str_contains($aso_path, env('COS_DOMAIN'))){
                $aso_path = \str_after($aso_path,env('COS_DOMAIN'));
            }
            
            $cosDisk     = \Storage::cloud();
            $cosDisk->put($aso_path, \file_get_contents($file->path()));
            
            return $cosDisk->url($aso_path);
        }
    }
}
