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
Route::get('import-csv', 'API\CSVImportController@importCSV');
Route::get('dashboard-common-statistic', 'API\StatisticController@getDashboardCommonStatistic');
Route::get('assets-statistic', 'API\StatisticController@getAssetClassStats');
Route::get('geo-statistic', 'API\StatisticController@getGeoStatistic');
Route::get('sector-statistic', 'API\StatisticController@getSectorStatistic');

// Common statistic routes
Route::get('prices', 'API\StatisticController@getPricesStatistic');
Route::get('common-statistic', 'API\StatisticController@calculateCommonRates');

Route::group(['middleware' => ['auth:api', 'role:customer']], function() {
    // Verify access to client area
    Route::post('authorization', 'API\PassportController@verify');
    // Permit access to the resource
    Route::post('permit', 'API\PassportController@permit');
    Route::get('profile', 'API\ProfileController@index');
    Route::put('profile/{id}', 'API\ProfileController@update')->where(['id' => '[0-9]+']);
    // Statistic routes
    Route::get('dashboard-statistic', 'API\StatisticController@getDashboardStatistic');
});

Route::group(['middleware' => ['auth:api', 'role:administrator']], function() {
    // Verify access for administrator's dashboard
    Route::post('verification', 'API\PassportController@verify');

    // Trading Blocks routes
    Route::get('trading-blocks', 'API\TradingBlockController@index');

    // Sectors routes
    Route::get('sectors', 'API\SectorController@index');

    // Countries routes
    Route::get('countries', 'API\CountryController@index');

    // Types routes
    Route::get('types', 'API\TypeController@index');


    // Assets routes
    Route::get('assets', 'API\AssetController@index');
    Route::post('assets', 'API\AssetController@store');
    Route::get('assets/{id}', 'API\AssetController@edit')->where(['id' => '[0-9]+']);
    Route::put('assets/{id}', 'API\AssetController@update')->where(['id' => '[0-9]+']);
    Route::delete('assets/{id}', 'API\AssetController@destroy')->where(['id' => '[0-9]+']);

    // Administrators routes
    Route::get('administrators', 'API\AdministratorController@index');
    Route::post('administrators', 'API\AdministratorController@store');
    Route::get('administrators/{id}', 'API\AdministratorController@edit')->where(['id' => '[0-9]+']);
    Route::put('administrators/{id}', 'API\AdministratorController@update')->where(['id' => '[0-9]+']);
    Route::delete('administrators/{id}', 'API\AdministratorController@destroy')->where(['id' => '[0-9]+']);

    // Customers routes
    Route::get('customers', 'API\CustomerController@index');
    Route::post('customers', 'API\CustomerController@store');
    Route::get('customers/{id}', 'API\CustomerController@edit')->where(['id' => '[0-9]+']);
    Route::put('customers/{id}', 'API\CustomerController@update')->where(['id' => '[0-9]+']);
    Route::delete('customers/{id}', 'API\CustomerController@destroy')->where(['id' => '[0-9]+']);

    Route::get('permission', 'API\RoleController@store');
    Route::post('home', 'API\HomeContentController@store');
    Route::put('home/{id}', 'API\HomeContentController@update')->where(['id' => '[0-9]+']);

    // Team routes
    Route::post('team', 'API\TeamController@store');
    Route::get('team/{id}', 'API\TeamController@edit')->where(['id' => '[0-9]+']);
    Route::put('team/{id}', 'API\TeamController@update')->where(['id' => '[0-9]+']);
    Route::delete('team/{id}', 'API\TeamController@destroy')->where(['id' => '[0-9]+']);

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
});
