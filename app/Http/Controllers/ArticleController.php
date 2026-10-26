<?php

namespace App\Http\Controllers;

use App\Events\NewArticleEvent;
use App\Http\Requests\SaveArticleRequest;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Article::class);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $key = 'articles.index.'.$page;

        $articles = Cache::remember($key, 600, function () use ($page, $key) {
            // Реестр нужен, чтобы после создания статьи удалить все страницы списка.
            $keys = Cache::get('articles.index.keys', []);
            $keys[] = $key;
            Cache::forever('articles.index.keys', array_unique($keys));

            return Article::query()->orderByDesc('published_at')->orderByDesc('id')
                ->paginate(5, ['*'], 'page', $page);
        });

        // Главная и /articles используют одни данные, но свои ссылки пагинации.
        $articles->withPath($request->url());

        return view('articles.index', ['articles' => $articles]);
    }

    public function create(): View
    {
        Gate::authorize('create', Article::class);

        return view('articles.create', ['article' => new Article(['published_at' => today()])]);
    }

    public function store(SaveArticleRequest $request): RedirectResponse
    {
        $article = Article::create($request->validated());

        foreach (Cache::get('articles.index.keys', []) as $key) {
            Cache::forget($key);
        }
        Cache::forget('articles.index.keys');

        $readers = User::whereHas('role', fn ($query) => $query->where('name', Role::READER))->get();
        Notification::send($readers, new NewArticleNotification($article));

        VeryLongJob::dispatch($article);
        NewArticleEvent::dispatch($article);

        return redirect()->route('articles.show', $article)
            ->with('status', 'Статья создана. Письмо модератору добавлено в очередь.');
    }

    public function show(Article $article): View
    {
        Gate::authorize('view', $article);

        $article = Cache::rememberForever('articles.show.'.$article->id, fn () => $article->load([
            'comments' => fn ($query) => $query->where('is_approved', true)->with('user:id,name')->latest('id'),
        ]));

        return view('articles.show', ['article' => $article]);
    }

    public function edit(Article $article): View
    {
        Gate::authorize('update', $article);

        return view('articles.edit', ['article' => $article]);
    }

    public function update(SaveArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($request->validated());

        Cache::flush();

        return redirect()->route('articles.show', $article)->with('status', 'Статья обновлена.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        $article->delete();

        Cache::flush();

        return redirect()->route('home')->with('status', 'Статья удалена.');
    }

    public function galery(Article $article): View
    {
        Gate::authorize('view', $article);

        abort_unless($article->full_image, 404);

        return view('galery', ['article' => $article]);
    }
}
