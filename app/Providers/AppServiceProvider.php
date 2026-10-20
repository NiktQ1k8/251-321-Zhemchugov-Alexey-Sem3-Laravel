<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Role;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Article::class, ArticlePolicy::class);

        Gate::before(fn (User $user) => $user->hasRole(Role::MODERATOR) ? true : null);

        Gate::define('create-comment', fn (User $user) => $user->hasRole(Role::READER)
            ? Response::allow()
            : Response::deny('Добавлять комментарии могут только читатели и модераторы.'));

        Gate::define('update-comment', fn (User $user, Comment $comment) => Response::deny('Редактировать комментарии может только модератор.'));
        Gate::define('delete-comment', fn (User $user, Comment $comment) => Response::deny('Удалять комментарии может только модератор.'));
    }
}
