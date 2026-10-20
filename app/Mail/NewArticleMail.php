<?php

namespace App\Mail;

use App\Models\Article;
use Illuminate\Mail\Mailable;

class NewArticleMail extends Mailable
{
    public function __construct(public Article $article) {}

    public function build(): static
    {
        return $this->subject('Новая статья: '.$this->article->name)
            ->view('mail.articles.created');
    }
}
