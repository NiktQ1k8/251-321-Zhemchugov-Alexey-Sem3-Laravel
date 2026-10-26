<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class DailyStatisticsMail extends Mailable
{
    public function __construct(public string $date, public int $views, public int $comments) {}

    public function build(): static
    {
        return $this->subject('Статистика сайта за '.$this->date)
            ->view('mail.statistics.daily');
    }
}
