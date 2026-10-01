<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()->orderByDesc('published_at')->orderByDesc('id')->get();

        return view('articles.index', ['articles' => $articles]);
    }

    public function galery(Article $article): View
    {
        abort_unless($article->full_image, 404);

        return view('galery', ['article' => $article]);
    }
}
