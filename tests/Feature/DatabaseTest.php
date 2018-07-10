<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DatabaseTest extends TestCase
{
     public function testEditorCanVisitArticleCreatePage()
    {
        $editor   = \App\User::where('is_editor', 1)->take(10)->get()->random();
        $response = $this->actingAs($editor)->get('/article/create');

        $response->assertSuccessful();
    }

    public function testEditorCanPostArticle()
    {
        $rand_category_id = \App\Category::take(10)->get()->random()->id;
        $editor           = \App\User::where('is_editor', 1)->take(10)->get()->random();
        $response         = $this->actingAs($editor)->post('/article', [
            'title'        => 'test article can post by editor ....',
            'author'       => $editor->name,
            'user_id'      => $editor->id,
            'category_ids' => [$rand_category_id],
            'keywords'     => 'test, article, can, post',
            'body'         => 'this is only a test for verify editor user still can post article in our system ....',
        ]);

        $response->assertStatus(302);
    }
}
