<?php

namespace App\Providers;

use App\Services\DocumentManager\DocumentManagerService;
use App\Services\DocumentManager\DocumentManagerServiceInterface;
use Illuminate\Support\ServiceProvider;

class DocumentManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DocumentManagerServiceInterface::class, DocumentManagerService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
