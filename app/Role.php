<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Role extends Model
{
    protected $fillable = [
        'name',
        'rank',
    ];

    const NORMAL_USER    = 'NORMAL_USER';
    const MODERATOR_USER = 'MODERATOR_USER';
    const EDITOR_USER    = 'EDITOR_USER';
    const ROOT_USER      = 'ROOT_USER';
    const STAR_USER      = 'STAR_USER';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeName($query, $value)
    {
        return $query->where('name', $value);
    }

    public function isNormalUser()
    {
        return $this->name == self::NORMAL_USER;
    }

    public function isEditor()
    {
        return $this->name == self::EDITOR_USER;
    }

    public function hasEditor()
    {
        return $this->rank >= Role::byNameRank(self::EDITOR_USER);
    }

    public function hasAdmin()
    {
        return $this->name == self::ROOT_USER;
    }

    public function hasModerator()
    {
        return $this->name >= Role::byNameRank(self::MODERATOR_USER);
    }

    public function getNameCnAttribute()
    {
        return Arr::get(self::getRoles(), $this->name, '暂无本地化名称');
    }

    public static function getRoles()
    {
        return [
            self::MODERATOR_USER => '版主',
            self::EDITOR_USER    => '内容管理',
            self::ROOT_USER      => '超级管理',
            self::STAR_USER      => '优质内容作者',
            self::NORMAL_USER    => '普通用户',
        ];
    }

    public static function byNameRank($value)
    {
        return self::name($value)->first()->rank ?? 0;
    }
}
