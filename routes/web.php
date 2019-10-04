<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'v1'], function () use ($router)
{

    $router->group(['prefix' => 'auth'], function () use ($router)
    {
        $router->post('login', 'AuthController@login');

        /*$router->group(['middleware' => App\Http\Middleware\AuthMiddleware::class], function () use ($router)
        {
            $router->post('logout', 'AuthController@logout');

            $router->post('refresh', 'AuthController@refresh');

            $router->get('check', 'AuthController@check');
        });*/

    });

    $router->group(['prefix' => 'graph'], function () use ($router) {

        $router->get('/', 'OnboardFlowController@createGraph');

        //$router->group(['middleware' => App\Http\Middleware\AuthMiddleware::class], function () use ($router) {

        // });

    });

});
