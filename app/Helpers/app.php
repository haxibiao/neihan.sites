<?php

function app_link_with_title($id, $title)
{
    if (is_in_app()) {
    	return 'javascript: appGoDetail(' . $id . ', \'' . $title . '\')';
    } else {
        return "/article/" . $id;
    }
}

function app_link($article)
{
    return app_link_with_title($article->id, $article->title);
}
