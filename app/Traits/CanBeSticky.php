<?php

namespace App\Traits;

use App\Site;

/**
 * 添加到想要赋予置顶能力的模型上
 * Trait CanBeSticky
 * @package Haxibiao\Content\Traits
 */
trait CanBeSticky
{
    public static function bootCanBeSticky()
    {
        // 资源移除时候，自动移除置顶逻辑
        static::deleted(function ($model) {
            foreach ($model->related as $stickable) {
                $stickable->delete();
            }
        });
    }

    public function stickSites()
    {
        return $this->morphToMany(\App\Site::class, 'item','stickables')
            ->withPivot(['name', 'page', 'area'])
            ->withTimestamps();
    }

    public function related()
    {
        return $this->morphMany(\App\Stickable::class, 'item');
    }

    public function stickByIds($sites = null,$name = null, $page = null,  $area = null)
    {
        $sites       = Site::bySiteIds($sites)->get();
        foreach ($sites as $site) {
            $count = $this->stickSites()->when($name,function ($q)use($name){
                $q->where('stickables.name', $name);
            })->where('site_id',$site->id)->count();

            if ($count >= 1) {
                continue;
            } else {
                $this->stickSites()->attach([
                    $site->id => [
                        'name' =>  $name,
                        'page'  => $page,
                        'area'  => $area,
                    ]
                ]);
            }
        }
        return $this;
    }

    public function unStickByIds($sites)
    {
        $this->stickSites()->detach($sites);

        return $this;
    }

    public function scopeByStickablePage($query, $page)
    {
        return $query->where('stickables.page',$page);
    }

    public function scopeByStickableName($query, $name)
    {
        return $query->where('stickables.name',$name);
    }

    public function scopeByStickableArea($query, $area)
    {
        return $query->where('stickables.area',$area);
    }
}
