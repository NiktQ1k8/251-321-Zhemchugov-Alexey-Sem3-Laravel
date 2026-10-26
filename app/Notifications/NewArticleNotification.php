<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Notifications\Notification;

class NewArticleNotification extends Notification
{
    public function __construct(public Article $article) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'article_id' => $this->article->id,
            'article_name' => $this->article->name,
        ];
    }
}
