<?php
namespace App\Providers;

use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserApiRepository;
use App\Repositories\ApiRepositoryInterface;

class ApiProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(ApiRepositoryInterface::class, UserApiRepository::class);
    }
}
