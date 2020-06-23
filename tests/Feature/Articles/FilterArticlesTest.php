<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_articles_by_title()
    {
        factory(Article::class)->create([
            'title' => 'Aprende Laravel Desde Cero'
        ]);

        factory(Article::class)->create([
            'title' => 'Other Article'
        ]);

        $url = route('api.v1.articles.index', ['filter[title]' => 'Laravel']);
        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel Desde Cero')
            ->assertDontSee('Other Article')
        ;
    }

    /** @test */
    public function can_filter_articles_by_content()
    {
        factory(Article::class)->create([
            'content' => '<div>Aprende Laravel Desde Cero</div>'
        ]);

        factory(Article::class)->create([
            'content' => '<div>Other Article</div>'
        ]);

        $url = route('api.v1.articles.index', ['filter[content]' => 'Laravel']);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel Desde Cero')
            ->assertDontSee('Other Article')
        ;
    }

    /** @test */
    public function can_filter_articles_by_year()
    {
        factory(Article::class)->create([
            'title' => 'Article from 2020',
            'created_at' => now()->year(2020)
        ]);

        factory(Article::class)->create([
            'title' => 'Article from 2021',
            'created_at' => now()->year(2021)
        ]);

        $url = route('api.v1.articles.index', ['filter[year]' => 2020]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Article from 2020')
            ->assertDontSee('Article from 2021')
        ;
    }

    /** @test */
    public function can_filter_articles_by_month()
    {
        factory(Article::class)->create([
            'title' => 'Article from February',
            'created_at' => now()->month(2)
        ]);
        factory(Article::class)->create([
            'title' => 'Another Article from February',
            'created_at' => now()->month(2)
        ]);

        factory(Article::class)->create([
            'title' => 'Article from January',
            'created_at' => now()->month(1)
        ]);

        $url = route('api.v1.articles.index', ['filter[month]' => 2]);

        $this->getJson($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from February')
            ->assertSee('Another Article from February')
            ->assertDontSee('Article from January')
        ;
    }

    /** @test */
    public function cannot_filter_articles_by_unknown_filters()
    {
        factory(Article::class)->create();

        $url = route('api.v1.articles.index', ['filter[unknown]' => 2]);

        $this->getJson($url)->assertStatus(400);
    }
}
