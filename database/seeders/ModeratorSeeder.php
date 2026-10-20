<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class ModeratorSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'moderator@example.test';
        $moderator = User::where('email', $email)->first();

        if ($moderator) {
            if (! $moderator->hasRole(Role::MODERATOR)) {
                throw new RuntimeException('Email moderator@example.test занят читателем. Освободите этот email перед созданием учебного модератора.');
            }

            return;
        }

        $password = Str::random(20);
        $moderator = new User(['name' => 'Модератор', 'email' => $email, 'password' => $password]);
        $moderator->role()->associate(Role::where('name', Role::MODERATOR)->firstOrFail());
        $moderator->save();

        $this->command?->info('Модератор: '.$email);
        $this->command?->info('Пароль (показывается один раз): '.$password);
    }
}
