<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\HomeController@getHomePage');

Route::get('/home', 'App\Http\Controllers\HomeController@getHomePage');

Route::get('/catalog', 'App\Http\Controllers\CatalogController@getCatalogPage');

Route::get('/product/{id}', 'App\Http\Controllers\ProductController@getProductPage');

Route::get('/store/{id}', 'App\Http\Controllers\StoreController@getStorePage');

Route::get('/account', 'App\Http\Controllers\AccountController@getAccountPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/seller', 'App\Http\Controllers\SellerCabinetController@getSellerPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/admin', 'App\Http\Controllers\AdminCabinetController@getAdminPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/shopping-cart', 'App\Http\Controllers\ShoppingCartController@getShoppingCartPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/orders', 'App\Http\Controllers\OrderController@getOrdersPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::get('/order-making', 'App\Http\Controllers\OrderMakingController@getOrderMakingPage')
    ->middleware(\App\Http\Middleware\RedirectIfNotAuthorized::class);

Route::post('/authorization', 'App\Http\Controllers\AuthorizationController@tryAuthorize');
Route::get('/logout', 'App\Http\Controllers\AuthorizationController@logout');
Route::post('/registration', 'App\Http\Controllers\RegistrationController@tryRegistrate');
Route::post('/modify-user', 'App\Http\Controllers\AccountController@modifyUser');
Route::post('/modify-user-avatar', 'App\Http\Controllers\AccountController@modifyUserAvatar');
Route::post('/modify-store-avatar', 'App\Http\Controllers\StoreController@modifyStoreAvatar');
Route::post('/modify-store-name', 'App\Http\Controllers\StoreController@modifyStoreName');
Route::post('/add-product', 'App\Http\Controllers\ProductController@addProduct');
Route::post('/add-comment', 'App\Http\Controllers\ProductController@addComment');
Route::post('/cart', 'App\Http\Controllers\ShoppingCartController@addCart');
Route::delete('/cart', 'App\Http\Controllers\ShoppingCartController@deleteCart');
Route::post('/order', 'App\Http\Controllers\OrderController@addOrders');
Route::put('/user-set-seller', 'App\Http\Controllers\AdminCabinetController@setUserSeller');
Route::put('/user-unset-seller', 'App\Http\Controllers\AdminCabinetController@unsetUserSeller');
Route::put('/user-ban', 'App\Http\Controllers\AdminCabinetController@banUser');
Route::put('/cancel-order', 'App\Http\Controllers\OrderController@cancelOrder');

Route::get('/properties/{id}', 'App\Http\Controllers\SellerCabinetController@getPropertiesOfCategory');
