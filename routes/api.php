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

// Route::get('/login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@login']);
//
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('version', function() {
    return json_encode([
        'api' => 'GRIP Investments API',
        'version' => 'v. 1.0'
    ]);
});

Route::post('login', 'API\PassportController@login');
Route::post('register', 'API\PassportController@register');
Route::get('team', 'API\TeamController@index');
Route::get('team/{id}', 'API\TeamController@edit')->where(['id' => '[0-9]+']);
Route::get('contact', 'API\ContactController@index');
Route::get('ico', 'API\IcoController@index');
Route::get('team/content', 'API\TeamContentController@index');
Route::get('home', 'API\HomeContentController@index');

Route::group(['middleware' => 'auth:api'], function() {

    // Verify access route
    Route::post('verify', 'API\PassportController@verify');

    Route::post('home', 'API\HomeContentController@store');
    Route::put('home/{id}', 'API\HomeContentController@update')->where(['id' => '[0-9]+']);

    // Team routes
    Route::post('team', 'API\TeamController@store');
    Route::put('team/{id}', 'API\TeamController@update');
    Route::delete('team/{id}', 'API\TeamController@destroy');

    Route::post('team/content', 'API\TeamContentController@store');
    Route::put('team/content/{id}', 'API\TeamContentController@update')->where(['id' => '[0-9]+']);

    // Contact routes
    Route::post('contact', 'API\ContactController@store');
    Route::put('contact/{id}', 'API\ContactController@update');

    // ICO routes
    Route::post('ico', 'API\IcoController@store');
    Route::put('ico/{id}', 'API\IcoController@update');
});
