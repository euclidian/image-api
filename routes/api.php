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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/image/upload', 'Api\Client\ImageUploaderApi@upload');

Route::get('/list', 'Api\Web\HomeController@listImage');
Route::get('/getUser', 'Api\Web\HomeController@getUser');

Route::post('/upload', 'Api\Web\HomeController@upload');
Route::get('/delete/{id}', 'Api\Web\HomeController@delete');
Route::get('/detail/{id}', 'Api\Web\HomeController@detail');
Route::get('/download/{id}', 'Api\Web\HomeController@download');

Route::get('/listuser',"Api\Web\Admin\AdminController@listuser");
Route::get('/toAdmin/{id}',"Api\Web\Admin\AdminController@toAdmin");
Route::get('/toActive/{id}',"Api\Web\Admin\AdminController@toActive");
Route::get('api/generateToken/{id}',"Api\Web\Admin\AdminController@generateToken");
