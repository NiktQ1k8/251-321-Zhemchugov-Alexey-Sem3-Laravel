<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function store(Request $request, Article $article): RedirectResponse
    {
        Gate::authorize('create-comment');

        $comment = new Comment($this->validateComment($request));
        $comment->user()->associate($request->user());
        $article->comments()->save($comment);

        Cache::forget('articles.show.'.$article->id);

        return redirect()->route('articles.show', $article)->withFragment('comments')
            ->with('status', 'Комментарий добавлен и ожидает модерации.');
    }

    public function edit(Article $article, Comment $comment): View
    {
        Gate::authorize('update-comment', $comment);

        return view('comments.edit', ['article' => $article, 'comment' => $comment]);
    }

    public function update(Request $request, Article $article, Comment $comment): RedirectResponse
    {
        Gate::authorize('update-comment', $comment);

        $comment->fill($this->validateComment($request));
        $textChanged = $comment->isDirty('body');

        if ($textChanged) {
            $comment->is_approved = false;
        }

        $comment->save();

        Cache::forget('articles.show.'.$article->id);

        return redirect()->route('articles.show', $article)->withFragment('comments')
            ->with('status', $textChanged ? 'Комментарий обновлён и ожидает модерации.' : 'Комментарий не изменён.');
    }

    public function destroy(Article $article, Comment $comment): RedirectResponse
    {
        Gate::authorize('delete-comment', $comment);

        $comment->delete();

        Cache::forget('articles.show.'.$article->id);

        return redirect()->route('articles.show', $article)->withFragment('comments')
            ->with('status', 'Комментарий удалён.');
    }

    private function validateComment(Request $request): array
    {
        return $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ], [
            'body.required' => 'Введите текст комментария.',
            'body.string' => 'Текст комментария должен быть строкой.',
            'body.max' => 'Комментарий должен содержать не более :max символов.',
        ]);
    }
}
