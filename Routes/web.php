<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::prefix('admin/shop')->group(function() {
    Route::resource('shop', 'ShopController');

    Route::resource('category', 'CategoryController');
    Route::post('category-sort', 'CategoryController@sort_item')->name('category-sort');
    Route::resource('product', 'ProductController');
    Route::get('product/status/{product}', 'ProductController@status')->name('product.status');

    Route::resource('seller', 'SellerController');
    Route::get('seller/status/{seller}', 'SellerController@status')->name('seller.status');

    Route::get('sellerProduct/{seller}', 'SellerProductController@index')->name('sellerProduct.index');
    Route::get('sellerProduct/{seller}/create', 'SellerProductController@create')->name('sellerProduct.create');
    Route::post('sellerProduct/{seller}/store', 'SellerProductController@store')->name('sellerProduct.store');
    Route::get('sellerProduct/{seller}/edit/{ProductSeller}', 'SellerProductController@edit')->name('sellerProduct.edit');
    Route::patch('sellerProduct/{seller}/update/{ProductSeller}', 'SellerProductController@update')->name('sellerProduct.update');
    Route::delete('sellerProduct/{seller}/destroy/{ProductSeller}', 'SellerProductController@destroy')->name('sellerProduct.destroy');
});

Route::namespace('Front')->group(function() {
    Route::get('checkout', 'ShopController@checkout')->name('checkout')->middleware('auth');
    Route::post('submit-order', 'ShopController@submit_order')->name('submit-order')->middleware('auth');

    Route::get('callback', 'ShopController@callback')->name('callback');
    Route::get('success/{id}', 'ShopController@success')->name('success');

    Route::get('add-to-wishlist/{product}','WishlistController@add')->name('add-to-wishlist');
    Route::get('delete-wishlist/{wishlist}','WishlistController@delete')->name('delete-wishlist');

    Route::get('add-to-compare/{product}','CompareController@add')->name('add-to-compare');
    Route::get('delete-compare/{product}','CompareController@delete')->name('delete-compare');

    Route::get('add-to-cart/{id}','ShopController@add')->name('add-to-cart');
    Route::get('delete-from-cart/{basket}','ShopController@delete')->name('delete-from-cart');

    Route::get('change-quantity/{id}/{action}', 'ShopController@change_quantity')->name('change-quantity');
    Route::post('add-address','ShopController@add_address')->name('add-address');
    Route::post('update-address/{id}','ShopController@update_address')->name('update-address');
    Route::get('delete-address/{id}','ShopController@delete_address')->name('delete-address');
});
