<?php

namespace Modules\FootballMatch\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\FootballMatch\Console\Commands\Seed;
use Modules\FootballMatch\Enums\Match\MatchEventType;

class MatchServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', key: 'football');
        $this->commands([Seed::class]);

        Route::middleware('api')
            ->prefix('api/v1/football')
            ->group(__DIR__ . '/../../routes/api_v1.php');

        Relation::morphMap(MatchEventType::morphMap());
    }
}
