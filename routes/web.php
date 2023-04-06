<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\PageController@getHomePage');

Route::get('/home', 'App\Http\Controllers\PageController@getHomePage');

Route::get('/catalog', 'App\Http\Controllers\PageController@getCatalogPage');

Route::get('/product/{id}', 'App\Http\Controllers\PageController@getProductPage');

Route::get('/store/{id}', 'App\Http\Controllers\PageController@getStorePage');

Route::get('/account', 'App\Http\Controllers\PageController@getAccountPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/seller', 'App\Http\Controllers\PageController@getSellerPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/admin', 'App\Http\Controllers\PageController@getAdminPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/shopping-cart', 'App\Http\Controllers\PageController@getShoppingCartPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/orders', 'App\Http\Controllers\PageController@getOrdersPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/order-making', 'App\Http\Controllers\PageController@getOrderMakingPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::post('/authorization', 'App\Http\Controllers\AuthorizationController@tryAuthorize');
Route::get('/logout', 'App\Http\Controllers\AuthorizationController@logout');
Route::post('/registration', 'App\Http\Controllers\RegistrationController@tryRegistrate');
Route::post('/modify-user', 'App\Http\Controllers\DataController@modifyUser');
Route::post('/modify-user-avatar', 'App\Http\Controllers\DataController@modifyUserAvatar');
Route::post('/modify-store-avatar', 'App\Http\Controllers\DataController@modifyStoreAvatar');
Route::post('/modify-store-name', 'App\Http\Controllers\DataController@modifyStoreName');
Route::post('/add-product', 'App\Http\Controllers\DataController@addProduct');
Route::post('/add-comment', 'App\Http\Controllers\DataController@addComment');
Route::post('/cart', 'App\Http\Controllers\DataController@addCart');
Route::delete('/cart', 'App\Http\Controllers\DataController@deleteCart');
Route::post('/order', 'App\Http\Controllers\DataController@addOrders');
Route::put('/user-set-seller', 'App\Http\Controllers\DataController@setUserSeller');
Route::put('/user-unset-seller', 'App\Http\Controllers\DataController@unsetUserSeller');
Route::put('/user-ban', 'App\Http\Controllers\DataController@banUser');
Route::put('/cancel-order', 'App\Http\Controllers\DataController@cancelOrder');

Route::get('/properties/{id}', 'App\Http\Controllers\PageController@getPropertiesOfCategory');


Route::get('/user/{id}', function ($id) {
    return view('main');
});
