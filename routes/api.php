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
Route::get('contact', 'API\ContactController@index');
Route::get('ico', 'API\IcoController@index');
Route::get('team/content', 'API\TeamContentController@index');
Route::get('home', 'API\HomeContentController@index');
Route::post('contact/send', 'API\ContactController@sendMessage');
Route::get('activation', 'API\PassportController@activate');

Route::group(['middleware' => ['auth:api', 'role:customer']], function() {
    // Verify access to client area
    Route::post('authorization', 'API\PassportController@verify');
});

Route::group(['middleware' => ['auth:api', 'role:administrator']], function() {
    // Administrators routes
    Route::get('administrators', 'API\AdministratorController@index');
    Route::post('administrators', 'API\AdministratorController@store');
    Route::get('administrators/{id}', 'API\AdministratorController@edit')->where(['id' => '[0-9]+']);
    Route::put('administrators/{id}', 'API\AdministratorController@update')->where(['id' => '[0-9]+']);
    Route::delete('administrators/{id}', 'API\AdministratorController@destroy')->where(['id' => '[0-9]+']);

    // Customers routes
    Route::get('customers', 'API\CustomerController@index');

    Route::get('permission', 'API\RoleController@store');
    Route::post('home', 'API\HomeContentController@store');
    Route::put('home/{id}', 'API\HomeContentController@update')->where(['id' => '[0-9]+']);

    // Team routes
    Route::post('team', 'API\TeamController@store');
    Route::get('team/{id}', 'API\TeamController@edit')->where(['id' => '[0-9]+']);
    Route::put('team/{id}', 'API\TeamController@update');
    Route::delete('team/{id}', 'API\TeamController@destroy');

    Route::post('team/content', 'API\TeamContentController@store');
    Route::put('team/content/{id}', 'API\TeamContentController@update')->where(['id' => '[0-9]+']);

    // Contact routes
    Route::post('contact', 'API\ContactController@store');
    Route::put('contact/{id}', 'API\ContactController@update')->where(['id' => '[0-9]+']);
    Route::get('contact/messages', 'API\ContactController@getContactMessages');
    Route::get('contact/messages/{id}', 'API\ContactController@getContactMessage')->where(['id' => '[0-9]+']);
    Route::delete('contact/messages/{id}', 'API\ContactController@deleteContactMessage')->where(['id' => '[0-9]+']);

    // ICO routes
    Route::post('ico', 'API\IcoController@store');
    Route::put('ico/{id}', 'API\IcoController@update');

    // Verify access for administrator's dashboard
    Route::post('verification', 'API\PassportController@verify');
});
