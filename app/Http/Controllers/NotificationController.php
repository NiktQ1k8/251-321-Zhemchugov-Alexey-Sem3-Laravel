<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read(Request $request, string $notification): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($notification);
        $article = Article::find($notification->data['article_id']);

        $notification->markAsRead();

        if (! $article) {
            return redirect()->route('home')->with('warning', 'Статья из уведомления уже удалена.');
        }

        return redirect()->route('articles.show', $article);
    }
}
