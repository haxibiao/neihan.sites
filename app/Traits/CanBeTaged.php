<?php


namespace App\Traits;


use App\Tag;
use App\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait CanBeTaged
{
    public static function bootCanBeTaged()
    {
        static::deleting(function($model) {
            // 强制删除时移除标签关系
            if($model->forceDeleting){
                $model->untagByNames();
            }
        });
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(\App\Tag::class, 'taggable')
            ->withTimestamps();
    }

    public function taggable()
    {
        return $this->morphMany(\App\Taggable::class, 'taggable');
    }

    public function resovlePostTags($root, array $args, $context){
        return $root->tags();
    }

    /**
     * 通过属性获取标签名，例如 $model->tag_names = '经典电影, 音乐欣赏';
     */
    public function getTagNamesAttribute($value,$delimiter=', ')
    {
        return implode($delimiter, $this->tagNames());
    }

    /**
     * 替换成Tag Ids数组中的标签
     * @param $tags array
     * @return $this
     */
    public function retagByIds($tags = [])
    {
        $syncData   = [];
        $tags       = Tag::byTagIds($tags)->get();
        foreach ($tags as $tag){
            $syncData[$tag->id] = [
                'user_id'  => $this->user_id,
                'tag_name' => $tag->name,
            ];
        }
        $this->tags()->sync($syncData);

        return $this;
    }

    /**
     * 移除Tag Ids数组中的标签
     * @param $tags array
     * @return $this
     */
    public function untagByIds($tags)
    {
        $this->tags()->detach($tags);

        return $this;
    }

    /**
     * 通过给定的Tag Ids数组给当前模型打标签
     * @param $tags array
     * @return $this
     */
    public function tagByIds($tags)
    {
        $syncData   = [];
        $tags       = Tag::byTagIds($tags)->get();
        foreach ($tags as $tag){
            $syncData[$tag->id] = [
                'user_id'  => $this->user_id,
                'tag_name' => $tag->name,
            ];
        }
        $this->tags()->sync($syncData, false);

        return $this;
    }

    /**
     * 根据数组中的标签名，给模型打标签
     * @param $tagNames  string or array
     * @return $this
     */
    public function tagByNames($tagNames)
    {
        if(!is_array($tagNames)) {
            $tagNames = func_get_args();
        }
        $tagNames = $this->makeTagArray($tagNames);

        foreach($tagNames as $tagName) {
            $this->addTagByName($tagName);
        }

        return $this;
    }

    /**
     * 以数组的形式返回当前模型的所有标签名
     */
    public function tagNames()
    {
        return $this->tags()->pluck('name')->all();
    }

    /**
     * 根据数组中的标签名，移除对应的标签
     * @param null $tagNames string or array (or null to remove all tags)
     * @return $this
     */
    public function untagByNames($tagNames=null)
    {
        if(is_null($tagNames)) {
            $tagNames = $this->tagNames();
        }

        $tagNames = $this->makeTagArray($tagNames);

        foreach($tagNames as $tagName) {
            $this->removeTagByName($tagName);
        }

        return $this;
    }

    /**
     * 根据数组中的标签名，强制同步成对应的标签
     * @param $tagNames string or array (or null to remove all tags)
     * @return $this
     */
    public function retagByNames($tagNames)
    {
        if(!is_array($tagNames)) {
            $tagNames = func_get_args();
        }
        $tagNames = $this->makeTagArray($tagNames);
        $currentTagNames = $this->tagNames();

        $deletions = array_diff($currentTagNames, $tagNames);
        $additions = array_diff($tagNames, $currentTagNames);

        $this->untagByNames($deletions);

        foreach($additions as $tagName) {
            $this->addTagByName($tagName);
        }

        return $this;
    }

    /**
     * 标签数
     */
    public function countTags()
    {
        return $this->tags()->count();
    }

    /**
     * 获取包含tagNames中任何标签的资源对象
     */
    public function scopeWithAnyTag(Builder $query, $tagNames): Builder
    {
        $tags = $this->assembleTagsForScoping($query, $tagNames);

        return $query->whereIn($this->getTable().'.'.$this->getKeyName(), $tags);
    }



    private function assembleTagsForScoping($query,$tagNames){
        if(!is_array($tagNames)){
            $tagNames = func_get_args();
            array_shift($tagNames);
        }

        $tagNames = $this->makeTagArray($tagNames);
        $className = $query->getModel()->getMorphClass();

        $tags = Taggable::query()
            ->whereIn('tag_name', $tagNames)
            ->where('taggable_type', $className)
            ->get()
            ->pluck('taggable_id');

        return $tags;
    }

    private function addTagByName($tagName)
    {
        $tag = \App\Tag::byTagName($tagName)
            ->first();

        // 如果Tag存在，不需要创建
        if($tag) {
            $count = $this->taggable()->where('tag_id', '=', $tag->id)->take(1)->count();
            // 中间表已经存在记录则跳过
            if($count >= 1) {
                return;
            } else {
                $this->tags()->attach([
                    $tag->id=>[
                        'user_id'  => $this->user_id,
                        'tag_name' => $tagName
                    ]
                ]);
            }
        // 如果Tag存在，创建一个Tag并且关联到当前对象
        } else {
            $tag = new \App\Tag();
            $tag->name = $tagName;
            $tag->save();

            $this->tags()->attach([
                $tag->id=>[
                    'user_id' => $this->user_id,
                    'tag_name'=> $tagName
                ]
            ]);
        }
        $tag->incrementCount(1);
    }

    private function removeTagByName($tagName)
    {
        $tag = $this->tags()
            ->byTagName($tagName)
            ->first();

        if ($tag) {
            $this->tags()->detach($tag->id);
            $tag->decrementCount(1);
        }
    }

    private function makeTagArray($tagNames)
    {
        if(is_array($tagNames) && count($tagNames) == 1) {
            $tagNames = reset($tagNames);
        }

        if(is_string($tagNames)) {
            $tagNames = explode(',', $tagNames);
        } elseif(!is_array($tagNames)) {
            $tagNames = array(null);
        }

        $tagNames = array_map('trim', $tagNames);

        return array_values($tagNames);
    }
}