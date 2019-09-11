<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.update');
    $api->delete('authorizations/current', 'AuthorizationsController@destroy')
        ->name('api.authorizations.destroy');

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => 'api.auth'], function($api) {
            $api->get('games', 'GamesController@index')->name('api.games.index');
            $api->post('games/{game}/play', 'PlaysController@store')->name('api.games.plays.store');
            $api->get('games/{game}/plays', 'PlaysController@index')->name('api.games.plays.index');
        });
    });
});
