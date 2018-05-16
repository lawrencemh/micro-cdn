<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return \App\Models\User|null
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $apiToken    = $this->retrieveApiKeyFromRequest($request);
            $minsToCache = env('CACHE_USER_TOKEN_MINUTES', null);

            $user = app('cache')->remember("user_$apiToken", $minsToCache, function () use ($apiToken) {
                return User::where('api_token', $apiToken)->first();
            });

            return $user;
        });
    }

    /**
     * Retrieve the api token form the request headers or parameters.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string|null
     */
    protected function retrieveApiKeyFromRequest(Request $request)
    {
        if ($request->headers->has('api-token')) {
            return $request->header('api-token');
        }

        return $request->get('api_token', null);
    }
}
