<?php

namespace App\Nova\Actions;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use App\Collection as AppCollection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Http\Requests\ActionRequest;


class TopCollection extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '置顶该集合';
    
    public function handleRequest(ActionRequest $request)
    {
        $file = $request->file('logo');
        AppCollection::setTopCover($file);
        parent::handleRequest($request);
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {

        // 取消原来的置顶集合
        $odlTopCollections=AppCollection::top()->get();
        foreach ($odlTopCollections as $oldCollection) {
            $oldCollection->update(['sort_rank'=>0]);
         }
         //将选中的集合置顶
        foreach ($models as $collection) {
           $collection->update(['sort_rank'=>AppCollection::TOP_COLLECTION]);
        }
        return Action::message('置顶成功');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $image=Image::make('封面图片', 'logo')
                ->store(function (Request $request) {
                    $file = $request->file('logo');
                    return AppCollection::setTopCover($file);
                })->thumbnail(function () {
                    return AppCollection::getTopCover();
                })->preview(function () {
                    return AppCollection::getTopCover();
                })->disableDownload();
                
        return    [$image];
    }
}
