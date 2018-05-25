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

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('verify', 'API\PassportController@verify');
    Route::post('get-details', 'API\PassportController@getDetails');
});
