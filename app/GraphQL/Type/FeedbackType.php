<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class FeedbackType extends BaseType
{
    protected $attributes = [
		'name' => 'FeedbackType',
		'description' => 'feedback type',
	];

	public function fields() {
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the Feedback',
			],
			'user_id' => [
				'type' => Type::int(),
				'description' => '反馈用户',
			],
			'content' => [
				'type' => Type::string(),
				'description' => '反馈内容',
			],
			'contact' => [
				'type' => Type::string(),
				'description' => '联系方式',
			],
			'contact_type' => [
				'type' => Type::string(),
				'description' => '联系方式类型(邮箱/电话号码)',
				'resolve' => function ($root, $args) {
					$type = null;
					switch ($root->contact_type) {
					case 'phone':
						$type = '电话';
						break;
					case 'email':
						$type = '邮箱';
						break;
					default:
						break;
					}
					return $type;
				},
			],
			'images' => [
				'type' => Type::listOf(Type::string()),
				'description' => '图片描述',
				'resolve' => function ($root, $args) {
					$urls = [];
					foreach ($root->images as $image) {
						$urls[] = $image->url();
					}
					return $urls;
				},
			],
			'users' => [
				'type' => GraphQL::type('User'),
				'description' => '反馈用户信息',
				'resolve' => function ($root, $args) {
					return $root->user;
				},
			],
			'created_at' => [
				'type' => Type::string(),
				'description' => '反馈创建时间',
				'resolve' => function ($root, $args) {
					return $root->created_at->toDateTimeString();
				},
			],
		];
	}
}
