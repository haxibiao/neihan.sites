<?php

namespace App\GraphQL\Query;

use App\Action;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ActionsQuery extends Query {
	protected $attributes = [
		'name' => 'Actions',
	];

	public function type() {
		return Type::listOf(GraphQL::type('Action'));
	}

	public function args() {
		return [
			'user_id' => ['name' => 'user_id', 'type' => Type::int()],
			'offset' => ['name' => 'offset', 'type' => Type::int()],
			'limit' => ['name' => 'limit', 'type' => Type::int()],
			// 'filter'  => ['name' => 'filter', 'type' => GraphQL::type('ActionFilter')],
		];
	}

	public function resolve($root, $args) {
		$qb = Action::orderBy('id', 'desc')
			->where('actionable_type', '!=', 'favorites');

		if (isset($args['user_id'])) {
			$qb = $qb->where('user_id', $args['user_id']);
		}

		if (isset($args['offset'])) {
			$qb = $qb->skip($args['offset']);
		}
		$limit = 10;
		if (isset($args['limit'])) {
			$limit = $args['limit'];
		}
		$qb = $qb->take($limit);
		return $qb->get();
	}
}
