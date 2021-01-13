<?php

namespace App\Nova\Actions;

use App\Stickable;
use Haxibiao\Cms\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Nova;

class StickyToSite extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '置顶到站点';

    public function uriKey()
    {
        return str_slug(Nova::humanize($this));
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        if (!isset($fields->site_id)) {
            return Action::danger('必须选中要更新的站点');
        }
        $site = Site::findOrFail($fields->site_id);
        DB::beginTransaction();
        try {
            foreach ($models as $model){
                $names = data_get($fields,'names',[]);
                foreach ($names as $name){
                    $model->stickByIds([$site->id],$name);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return Action::danger('数据批量变更失败，数据回滚');
        }
        DB::commit();

    }

    public function fields()
    {
        $type = data_get($this,'meta.type','articles');
        $siteOptions = [];
        foreach (Site::all() as $site) {
            $siteOptions[$site->id] = $site->name . "(" . $site->domain . ")";
        }
        // name 按名称检索
        // page 按页面检索
        // area 按位置检索
        return [
            // TODO 二级联动
            Select::make('站点', 'site_id')->options($siteOptions),

            \Silvanite\NovaFieldCheckboxes\Checkboxes::make('位置名称','names')
                ->options(Stickable::getNameByType($type))
                ->withoutTypeCasting(),
        ];
    }
}
