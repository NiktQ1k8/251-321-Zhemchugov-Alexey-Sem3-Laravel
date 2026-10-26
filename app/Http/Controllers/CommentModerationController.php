<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CommentModerationController extends Controller
{
    public function index(): View
    {
        $comments = Comment::query()->where('is_approved', false)->with(['article', 'user'])->latest('id')->get();

        return view('comments.moderation', ['comments' => $comments]);
    }

    public function approve(Comment $comment): RedirectResponse
    {
        abort_unless(Comment::query()->whereKey($comment->id)->where('is_approved', false)->update(['is_approved' => true]), 404);

        Cache::forget('articles.show.'.$comment->article_id);

        return redirect()->route('comments.moderation')->with('status', 'Комментарий одобрен и опубликован.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        abort_unless(Comment::query()->whereKey($comment->id)->where('is_approved', false)->delete(), 404);

        Cache::forget('articles.show.'.$comment->article_id);

        return redirect()->route('comments.moderation')->with('status', 'Комментарий отклонён и удалён.');
    }
}
