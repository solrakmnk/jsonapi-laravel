<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::applySorts(request('sort'))->get();

        return ArticleCollection::make($articles);
    }

    public function show(Article $article)
    {
        return ArticleResource::make($article);
    }
}
