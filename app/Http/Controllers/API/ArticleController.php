<?php

namespace App\Http\Controllers\API;

use App\Events\NewArticleEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveArticleRequest;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    public function index(Request $request): JsonResponse
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

        // Данные кеша общие с сайтом; ссылки пагинации ведут на текущий API URL.
        $articles->withPath($request->url());

        return response()->json(['articles' => $articles]);
    }

    public function store(SaveArticleRequest $request): JsonResponse
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

        return response()->json([
            'article' => $article,
            'message' => 'Статья создана. Письмо модератору добавлено в очередь.',
        ], 201);
    }

    public function show(Article $article): JsonResponse
    {
        Gate::authorize('view', $article);

        $article = Cache::rememberForever('articles.show.'.$article->id, fn () => $article->load([
            'comments' => fn ($query) => $query->where('is_approved', true)->with('user:id,name')->latest('id'),
        ]));

        return response()->json(['article' => $article]);
    }

    public function update(SaveArticleRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());

        Cache::flush();

        return response()->json(['article' => $article, 'message' => 'Статья обновлена.']);
    }

    public function destroy(Article $article): JsonResponse
    {
        Gate::authorize('delete', $article);

        $article->delete();

        Cache::flush();

        return response()->json(['message' => 'Статья удалена.']);
    }

    public function galery(Article $article): JsonResponse
    {
        Gate::authorize('view', $article);

        abort_unless($article->full_image, 404);

        return response()->json(['article' => $article]);
    }
}
