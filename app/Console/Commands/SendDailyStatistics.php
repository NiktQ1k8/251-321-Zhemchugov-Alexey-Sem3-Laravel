<?php

namespace App\Console\Commands;

use App\Mail\DailyStatisticsMail;
use App\Models\ArticleView;
use App\Models\Comment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Signature('app:send-daily-statistics')]
#[Description('Отправить модератору статистику просмотров и новых комментариев за сегодня')]
class SendDailyStatistics extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipient = config('mail.moderator_address');

        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)
            || (config('mail.default') === 'smtp'
                && (! config('mail.mailers.smtp.username') || ! config('mail.mailers.smtp.password')))) {
            $this->error('Почта модератора не настроена. Проверьте MAIL_* в .env.');

            return self::FAILURE;
        }

        $start = today();
        $end = $start->copy()->addDay();
        $views = ArticleView::where('created_at', '>=', $start)->where('created_at', '<', $end)->count();
        $comments = Comment::where('created_at', '>=', $start)->where('created_at', '<', $end)->count();
        $date = $start->format('d.m.Y');

        try {
            Mail::to($recipient)->send(new DailyStatisticsMail($date, $views, $comments));
        } catch (TransportExceptionInterface) {
            // Не выводим SMTP-транскрипт: он может содержать реквизиты почты.
            $this->error('Не удалось отправить статистику. Проверьте SMTP-настройки и соединение.');

            return self::FAILURE;
        }

        $this->info("Статистика за {$date} отправлена. Просмотры: {$views}. Новые комментарии: {$comments}.");

        return self::SUCCESS;
    }
}
