<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\KualitasAirController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\inventory\AlatujiController;
use App\Http\Controllers\inventory\PerawatanController;
use App\Http\Controllers\inventory\VerifikasiController;
use App\Http\Controllers\inventory\KalibrasiPerbaikanController;
use App\Http\Controllers\LogistikController;
use App\Http\Controllers\MeetingController;

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
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});


Route::get('/home', function () {
    return redirect('/');
})->middleware('auth');

Route::get("/test", function () {
    return view('test');
});
Route::view('/modul_dashboard/show', 'auth.dashboard');

Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.administrator.dashboard');
    Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
        Route::view('/', 'page.administrator.user.show');
        Route::post('/data', [App\Http\Controllers\administrator\AdministratorController::class, 'get_data_user']);
        Route::post('/ubah_status', [App\Http\Controllers\administrator\AdministratorController::class, 'get_change_status_user'])->name('user.status');
        Route::get('/ubah_data/{id}', [App\Http\Controllers\administrator\AdministratorController::class, 'get_data_user_modal']);
        Route::get('/tambah', [App\Http\Controllers\administrator\AdministratorController::class, 'get_create_user_modal']);
        Route::post('/store', [App\Http\Controllers\administrator\AdministratorController::class, 'get_store_user']);
        Route::post('/update/{jenis}/{id}', [App\Http\Controllers\administrator\AdministratorController::class, 'get_update_user']);
        Route::post('/reset_pwd/{id}', [App\Http\Controllers\administrator\AdministratorController::class, 'reset_pwd_user'])->name('user.resetpwd');
    });
    Route::view('/{any?}', 'page.it.produk')->where('any', '.*');
    Route::group(['prefix' => 'part', 'middleware' => 'auth'], function () {
        Route::view('/', 'page.administrator.part.show');
    });
});



Route::view('/meeting/{any?}', 'page.meeting.index')->where('any', '.*');

Route::group(['prefix' => '/pdfmeet'], function () {
    Route::get('/undangan/{id}', [MeetingController::class, 'cetakUndangan']);
    Route::get('/hasil/{id}', [MeetingController::class, 'cetakHasil']);
});

Route::get('/testing/pbj', [ProduksiController::class, 'cetakTest']);

Route::view('/uit', 'page.login_page.index');


Route::namespace('v2')->group(__DIR__ . '/kesehatan/kesehatan.php');
Route::namespace('lab')->group(__DIR__ . '/inventory/web.php');
