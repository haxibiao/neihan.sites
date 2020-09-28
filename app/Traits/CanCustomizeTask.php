<?php


namespace App\Traits;




trait CanCustomizeTask
{
   public   function visitedCollection($type,array $ids){
       return $this->visits()
           ->where('visited_type',$type)
           ->whereIn('visited_id',$ids)
           ->whereBetween('created_at', [today(), today()->addDay()]);
   }
}