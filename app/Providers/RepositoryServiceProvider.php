<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repositories interfaces and classes to instantiate.
     *
     * @return void
     */
    public function register()
    {
        // The container repository.
        $this->app->bind(
            'App\Repositories\Contracts\ContainerRepositoryInterface',
            'App\Repositories\Eloquent\ContainerRepository');

        // The compressed copy repository.
        $this->app->bind(
            'App\Repositories\Contracts\CompressedCopyRepositoryInterface',
            'App\Repositories\Eloquent\CompressedCopyRepository');

        // The media repository.
        $this->app->bind(
            'App\Repositories\Contracts\MediaRepositoryInterface',
            'App\Repositories\Eloquent\MediaRepository');
    }
}
