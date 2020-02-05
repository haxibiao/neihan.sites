<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = [
        'name',
        'build',
        'description',
        'file_size',
        'url',
        'status',
        'type',
        'is_force',
        'created_at',
        'updated_at',
        'package',
    ];

    /**
     * 1:运行中 0:已下架 -1:已删除
     */
    const RUNNING = 1;
    const STOPPED = 0;
    const DELETED = -1;

    /**
     * 1：正式包 0:内测包
     */
    const RELEASE = 1;
    const STAGING = 0;

    public static function getOses()
    {
        return [
            'Android' => 'Android',
            'IOS'     => 'IOS',
        ];
    }

    public static function getStatuses()
    {
        return [
            self::RUNNING => '运行中',
            self::STOPPED => '已下架',
            self::DELETED => '已删除',
        ];
    }

    public static function getTypes()
    {
        return [
            self::RELEASE => '正式包',
            self::STAGING => '内测包',
        ];
    }

    public static function getIsForces()
    {
        return [
            1 => '强制更新',
            0 => '非强制更新',
        ];
    }

    public function getStatusToChinese()
    {
        $statuses = self::getStatuses();
        return array_key_exists($this->status, $statuses) ? $statuses[$this->status] : null;
    }

    public static function getLatestVersion(): Version
    {
        return Version::latest('id')->first();
    }
}
