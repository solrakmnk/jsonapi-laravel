<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_update_articles()
    {
        $article = factory(Article::class)->create();

        $this->jsonApi()->patch(route('api.v1.articles.update', $article))
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_update_their_articles()
    {
        $article = factory(Article::class)->create();
        Sanctum::actingAs($article->user);

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'id' => $article->getRouteKey(),
                'attributes' => [
                    'title' => 'title changed',
                    'slug' => 'slug-changed',
                    'content' => 'content changed'
                ]
            ]
        ])->patch(route('api.v1.articles.update', $article))
            ->assertStatus(200);
    }

    /** @test */
    public function authenticated_users_can_updata_only_title()
    {
        $article = factory(Article::class)->create();
        Sanctum::actingAs($article->user);

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'id' => $article->getRouteKey(),
                'attributes' => [
                    'title' => 'title changed',
                ]
            ]
        ])->patch(route('api.v1.articles.update', $article))
            ->assertStatus(200);
    }

    /** @test */
    public function authenticated_users_can_updata_only_slug()
    {
        $article = factory(Article::class)->create();
        Sanctum::actingAs($article->user);

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'id' => $article->getRouteKey(),
                'attributes' => [
                    'slug' => 'slug-changed',
                ]
            ]
        ])->patch(route('api.v1.articles.update', $article))
            ->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'slug' => 'slug-changed'
        ]);
    }

    /** @test */
    public function authenticated_users_cannot_update_other_articles()
    {
        $article = factory(Article::class)->create();

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'id' => $article->getRouteKey(),
                'attributes' => [
                    'title' => 'title changed',
                    'slug' => 'slug-changed',
                    'content' => 'content changed'
                ]
            ]
        ])->patch(route('api.v1.articles.update', $article))
            ->assertStatus(403);
    }
}
