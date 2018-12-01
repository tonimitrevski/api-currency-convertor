<?php
namespace App\Providers;

use App\Repositories\Currency\CurrencyRepository;
use App\Repositories\Currency\CurrencyRepositoryContract;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryContract;

class RepositoriesServiceProvider extends ServiceProvider
{
    public $singletons = [
        UserRepositoryContract::class => UserRepository::class,
        CurrencyRepositoryContract::class => CurrencyRepository::class
    ];

}
