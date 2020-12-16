<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('products', 'HomeController@productList');
Route::get('product/{id?}', 'HomeController@productDetails');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('my-dashboard', 'UserController@myDashboard');
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::post('file-uploader', 'UserController@fileUploader');
    Route::post('add-product', 'UserController@addProduct');
    Route::get('my-products', 'UserController@myProducts');
    Route::post('delete-product', 'UserController@deleteProduct');
});
