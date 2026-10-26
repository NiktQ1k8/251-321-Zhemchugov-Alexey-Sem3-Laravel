<?php

namespace App\Jobs;

use App\Mail\NewArticleMail;
use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class VeryLongJob implements ShouldQueue
{
    use Queueable;

    public bool $deleteWhenMissingModels = true;

    public function __construct(public Article $article) {}

    public function handle(): void
    {
        $recipient = config('mail.moderator_address');

        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)
            || (config('mail.default') === 'smtp'
                && (! config('mail.mailers.smtp.username') || ! config('mail.mailers.smtp.password')))) {
            throw new RuntimeException('Почта модератора не настроена. Проверьте MAIL_* в .env.');
        }

        try {
            Mail::to($recipient)->send(new NewArticleMail($this->article));
        } catch (TransportExceptionInterface) {
            // SMTP-транскрипт может содержать реквизиты: в очередь ошибок пишем только безопасное сообщение.
            throw new RuntimeException('Не удалось отправить письмо о статье №'.$this->article->id.'. Проверьте SMTP-настройки и соединение.');
        }
    }
}
