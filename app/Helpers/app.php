<?php

function app_link_with_title($id, $title) {
	return 'javascript: appGoDetail(' . $id . ', \'' . $title . '\')';
}

function app_link($article) {
	if (is_in_app()) {
		return app_link_with_title($article->id, $article->title);
	} else {
		return get_article_url($article);
	}
}
