<?php

namespace App\GraphQL\Mutation\feedback;

use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class CreateFeedbackMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateFeedbackMutation',
        'description' => '创建意见反馈',
    ];

    public function type() {
        return GraphQL::type('Feedback');
    }

    public function args() {
        return [
            'content' => [
                'name' => 'content',
                'type' => Type::string(),
            ],
            'image_urls' => [
                'name' => 'image_urls',
                'type' => Type::listOf(Type::string()),
            ],
            'contact' => [
                'name' => 'contact',
                'type' => Type::string(),
            ],
        ];
    }

    public function rules() {
        return [
            'content' => ['required'],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info) {
        $user = getUser();
        \DB::beginTransaction();
        try {
            $feedback = new \App\Feedback();
            $feedback->fill($args);
            $feedback->user_id = $user->id;
            //TODO 工具类的封装is_email is_phone
            if (isset($args['contact']) && !empty($args['contact'])) {
                if (preg_match("/^1[34578]\d{9}$/", $args['contact'])) {
                    $feedback->contact_type = 'phone';
                } else if (filter_var($args['contact'], FILTER_VALIDATE_EMAIL)) {
                    $feedback->contact_type = 'email';
                }
            }
            $feedback->save();
            if (isset($args['image_urls']) && !empty($args['image_urls'])) {
                //此处将图片路径转换成图片ID
                //切割字符串
                $image_ids = array_map(function ($url) {
                    return intval(pathinfo($url)['filename']);
                }, $args['image_urls']);
                $feedback->images()->sync($image_ids);
            }
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw new \Exception('系统错误');

        }
        \DB::commit();
        return $feedback;
    }
}
