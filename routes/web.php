<?php

use App\Models\User;
use Illuminate\Support\Facades\Request;
use \Laravel\Lumen\Routing\Router;

/** @var Router $router */

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

$router->group(['prefix' => 'api'], function (Router $router) {
    $router->post('login', '\App\Http\Controllers\AuthController@login');
    $router->post('register', '\App\Http\Controllers\AuthController@register');
    $router->post('otp', '\App\Http\Controllers\AuthController@otp');
    $router->get('user/{userId}', function (Request $request, $userId) {
        $user = User::query()->with('articles')->findOrFail($userId);
        return \App\Http\Resources\UserViewResource::make($user);
    });
    $router->group([
        'middleware' => 'auth'
    ], function (Router $router) {
        $router->get('me', function (Request $request) {
            /** @var User $user */
            $user = \Illuminate\Support\Facades\Auth::user();
            return \App\Http\Resources\UserViewResource::make($user);
        });
        $router->post('article', '\App\Http\Controllers\ArticleController@store');
        $router->put('article/{articleId}', '\App\Http\Controllers\ArticleController@update');
        $router->delete('article/{articleId}', '\App\Http\Controllers\ArticleController@delete');
    });
});
$router->get('/', function () use ($router) {
    return $router->app->version();
});
