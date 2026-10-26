<?php

namespace App\Events;

use App\Models\Article;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NewArticleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public array $article;

    public function __construct(Article $article)
    {
        $this->article = [
            'id' => $article->id,
            'name' => $article->name,
            'url' => route('articles.show', $article),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('articles'),
        ];
    }

    public function broadcastWith(): array
    {
        return ['article' => $this->article];
    }
}
