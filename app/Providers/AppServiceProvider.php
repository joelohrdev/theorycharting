<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
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
        $this->bootModelsDefaults();

        Gate::define('viewTeacherPages', fn (User $user) => $user->is_admin);
        Gate::define('viewStudentPages', fn (User $user) => $user->is_admin || $user->isTeacher());
    }

    private function bootModelsDefaults(): void
    {
        Model::unguard();
    }
}
