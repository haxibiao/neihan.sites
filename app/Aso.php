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
            $hash     = md5_file($file->path());
            $aso      = Aso::where('name', $name)->first();
            $aso_path = $aso->value;

            $old_file = public_path($aso_path);

            $old_hash = md5_file($old_file);
            if ($old_hash == $hash) {
                abort(500, '重复图片');
            }

            copy($file->path(), $old_file);

            return $aso_path;
        }
    }
}
