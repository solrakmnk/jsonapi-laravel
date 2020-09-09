<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function auth_users_can_create_articles()
    {
        $user = factory(User::class)->create();
        $article = factory(Article::class)->raw(['user_id' => null]);
        $this->assertDatabaseMissing('articles', $article);

        Sanctum::actingAs($user);
        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))->assertCreated();
        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article['title'],
            'slug' => $article['slug'],
            'content' => $article['content']
        ]);
    }

    /** @test */
    public function guest_users_cannot_create_articles()
    {
        $article = factory(Article::class)->raw(['user_id' => null]);

        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))->assertStatus(401);
        $this->assertDatabaseMissing('articles', $article);

    }

    /** @test */
    public function title_is_required()
    {
        $article = factory(Article::class)->raw(['title' => '']);
        Sanctum::actingAs(factory(User::class)->create());
        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/title');
        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function content_is_required()
    {
        $article = factory(Article::class)->raw(['content' => '']);
        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/content');
        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_is_required()
    {
        $article = factory(Article::class)->raw(['slug' => '']);
        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');
        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Article::class)->create(['slug' => 'same-slug']);
        Sanctum::actingAs(factory(User::class)->create());

        $article = factory(Article::class)->raw(['slug' => 'same-slug']);
        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');
        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_must_be_only_contain_letters_numbers_and_dashes()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $article = factory(Article::class)->raw(['slug' => '$3"|#']);
        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');
        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_must_not_contain_underscores()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $article = factory(Article::class)->raw(['slug' => 'slug_with_under']);
        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug')
            ->assertSee(trans('validation.no_underscores', ['attribute' => 'slug']))
        ;
        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_must_not_start_with_dashes()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $article = factory(Article::class)->raw(['slug' => '-slug-start-with-dash']);
        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug')
            ->assertSee(trans('validation.no_start_with_dash', ['attribute' => 'slug']));
        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_must_not_end_with_dashes()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $article = factory(Article::class)->raw(['slug' => 'slug-start-with-dash-']);
        $this->jsonApi()->content([
            'data' =>
                [
                    'type' => 'articles',
                    'attributes' => $article
                ]
        ])->post(route('api.v1.articles.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug')
            ->assertSee(trans('validation.no_end_with_dash', ['attribute' => 'slug']));

        $this->assertDatabaseMissing('articles', $article);
    }
}
