<?php

namespace App;

use App\Product;
use App\Traits\StoreAttrs;
use App\Traits\StoreRepo;
use App\Traits\StoreResolvers;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use StoreResolvers;
    use StoreAttrs;
    use StoreRepo;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function product()
    {
        return $this->hasMany(\App\Product::class);
    }

    public function image()
    {
        return $this->hasMany(\App\Image::class);
    }

}
