<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncludeAuthorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_include_authors()
    {
        $article=factory(Article::class)->create();

        /*$url=route('api.v1.articles.read',$article).'?include=authors';

        $this->jsonApi()
            ->get($url)
            ->assertSee($article->user->name);*/
        $this->jsonApi()
            ->includePaths('authors')
            ->get(route('api.v1.articles.read',$article))
            ->assertSee($article->user->name);

    }
}
