<?php declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Business\Domain\Boundary\Repositories\HailingRadiusRepo;

class DataProvider extends ServiceProvider
{
    public function register()
    {

        //Complain Management
        $this->app->bind(
            \Business\Domain\Boundary\Repositories\FlowRepositoryInterface::class,
            \Business\Data\Eloquent\Repositories\MySQL\FlowRepository::class
        );

    }

}
