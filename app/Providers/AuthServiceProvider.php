<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('update-user', function (User $user, int $id) {
            return $user->id === $id;
        });

        Gate::define('delete-user', function (User $user, int $id) {
            return $user->id === $id;
        });

        Gate::define('update-review', function (User $user, Review $review) {
            return $user->id === $review->user_id;
        });

        Gate::define('delete-review', function (User $user, Review $review) {
            return $user->id === $review->user_id;
        });
    }
}
