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
