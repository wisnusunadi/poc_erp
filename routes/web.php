<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();

Route::get('/', function () {
    if (auth()->user()->divisi->id == 24) return redirect('/ppic/dashboard');
    else if (auth()->user()->divisi->id == 3) return redirect('/manager-teknik/dashboard');
    else return view('home');
})->middleware('auth');

Route::get('/home', function () {
    return redirect('/');
})->middleware('auth');

Route::middleware('auth')->prefix('/ppic')->group(function () {
    Route::view('/dashboard', 'spa.ppic.dashboard');
    Route::get('/data/{status}', function ($status) {
        return view('spa.ppic.data', ['status' => $status]);
    });
    Route::get('/jadwal/{status}', function ($status) {
        return view('spa.ppic.jadwal', ['status' => $status]);
    });

    //test
    Route::view('/bppb/{any}', 'spa.ppic.bppb');
    Route::view('/test', 'spa.ppic');
});

Route::middleware('auth')->prefix('/manager-teknik')->group(function () {
    Route::view('/dashboard', 'spa.manager_teknik.dashboard');
    Route::view('/persetujuan_jadwal', 'spa.manager_teknik.persetujuan_jadwal');
});

Route::middleware('auth')->prefix('/gbj')->group(function () {
    Route::view('/stok/{any?}', 'page.gbj.stok');
    Route::view('/penjualan/{any?}', 'page.gbj.penjualan');
    Route::view('/produk/{any?}', 'page.gbj.produk');
});

// Route::middleware('auth')->prefix('/penjualan')->group(function () {
//     Route::view('/produk/{any?}', 'page.penjualan.produk');
//     Route::view('/customer/{any?}', 'page.penjualan.customer');
//     Route::view('/penjualan/{any?}', 'page.penjualan.penjualan');
//     Route::view('/po/{any?}', 'page.penjualan.po');
// });

Route::group(['prefix' => 'penjualan', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => '/produk'], function () {
        Route::view('/show', 'page.penjualan.produk.show')->name('penjualan.produk.show');
        Route::view('/create', 'page.penjualan.produk.create')->name('penjualan.produk.create');
        Route::view('/edit', 'page.penjualan.produk.edit')->name('penjualan.produk.edit');
    });

    Route::group(['prefix' => '/customer'], function () {
        Route::view('/show', 'page.penjualan.customer.show')->name('penjualan.customer.show');
        Route::view('/create', 'page.penjualan.customer.create')->name('penjualan.customer.create');
        Route::view('/edit', 'page.penjualan.customer.edit')->name('penjualan.customer.edit');
    });

    Route::group(['prefix' => '/penjualan'], function () {
        Route::view('/show', 'page.penjualan.penjualan.show')->name('penjualan.penjualan.show');
        Route::view('/create', 'page.penjualan.penjualan.create')->name('penjualan.penjualan.create');
        Route::view('/edit', 'page.penjualan.penjualan.edit')->name('penjualan.penjualan.edit');
    });

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.penjualan.so.show')->name('penjualan.so.show');
        Route::view('/create', 'page.penjualan.so.create')->name('penjualan.so.create');
        Route::view('/edit', 'page.penjualan.so.edit')->name('penjualan.so.edit');
    });

    // Route::get('/dashboard', 'digidocu\DocumentsController@dashboard')->name('dc.dashboard');
    // Route::get('/dep_doc/{id?}', 'digidocu\DocumentsController@dep_doc')->name('dc.dep_doc');
});

Route::get('/test/{name?}', function ($name = null) {
    return $name;
});

// Route::group(['prefix' => '/gbj', 'middleware' => 'auth'], function () {
//     Route::view('/stok', 'page.gbj.stok_show');
// });
