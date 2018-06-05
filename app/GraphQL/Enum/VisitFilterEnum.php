<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class VisitFilterEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'VisitFilter',
        'description' => 'The filters of Visit query',
        'values' => [
            'TODAY' => 'TODAY',   //今日浏览记录
            'EARLY' => 'EARLY',   //更早浏览记录
        ], 
    ];
}