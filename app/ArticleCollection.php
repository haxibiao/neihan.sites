<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleCollection extends Model {
	protected $table = 'collection_article';

	protected $fillable = [
		'article_id',
		'collection_id',
	];

	public function article() {
		return $this->belongsTo(\App\Article::class);
	}

	public function collection() {
		return $this->belongsTo(\App\Collection::class);
	}
}
