<?php

declare(strict_types=1);

namespace App\Providers;

use App\Policies\TeacherPolicy;
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

        Gate::define('viewAllTeachers', [TeacherPolicy::class, 'viewAll']);
    }

    private function bootModelsDefaults(): void
    {
        Model::unguard();
    }
}
