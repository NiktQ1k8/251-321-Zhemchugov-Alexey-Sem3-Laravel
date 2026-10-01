<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()->orderByDesc('published_at')->orderByDesc('id')->paginate(5);

        return view('articles.index', ['articles' => $articles]);
    }

    public function create(): View
    {
        return view('articles.create', ['article' => new Article(['published_at' => today()])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $article = Article::create($this->validateArticle($request));

        return redirect()->route('articles.show', $article)->with('status', 'Статья создана.');
    }

    public function show(Article $article): View
    {
        return view('articles.show', ['article' => $article]);
    }

    public function edit(Article $article): View
    {
        return view('articles.edit', ['article' => $article]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->update($this->validateArticle($request));

        return redirect()->route('articles.show', $article)->with('status', 'Статья обновлена.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('home')->with('status', 'Статья удалена.');
    }

    public function galery(Article $article): View
    {
        abort_unless($article->full_image, 404);

        return view('galery', ['article' => $article]);
    }

    private function validateArticle(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string', 'max:1000'],
            'body' => ['required', 'string', 'max:20000'],
            'published_at' => ['required', 'date_format:Y-m-d'],
            'preview_image' => ['nullable', 'required_with:full_image', Rule::in(['preview.jpg', 'preview_2.jpg'])],
            'full_image' => ['nullable', 'required_with:preview_image', Rule::in(['full.jpeg', 'full_2.jpeg'])],
        ], [
            'required' => 'Поле «:attribute» обязательно.',
            'required_with' => 'Выберите и превью, и полное изображение.',
            'string' => 'Поле «:attribute» должно быть строкой.',
            'max' => 'Поле «:attribute» должно содержать не более :max символов.',
            'date_format' => 'Укажите корректную дату публикации.',
            'in' => 'Выберите изображение из списка.',
        ], [
            'name' => 'название',
            'short_desc' => 'краткое описание',
            'body' => 'текст статьи',
            'published_at' => 'дата публикации',
            'preview_image' => 'превью',
            'full_image' => 'полное изображение',
        ]);
    }
}
