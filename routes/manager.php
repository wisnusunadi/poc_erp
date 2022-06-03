<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::group(['prefix' => 'penjualan'], function () {
    Route::get('/show', [App\Http\Controllers\PenjualanController::class, 'manager_penjualan_show'])->name('manager.penjualan.show');
    Route::post('/show_data', [App\Http\Controllers\PenjualanController::class, 'manager_penjualan_show_data'])->name('manager.penjualan.show.data');
    Route::get('/detail', [App\Http\Controllers\PenjualanController::class, 'manager_penjualan_detail'])->name('manager.penjualan.detail');
    Route::post('/detail_data', [App\Http\Controllers\PenjualanController::class, 'manager_penjualan_detail_data'])->name('manager.penjualan.detail.data');
});

Route::group(['prefix' => 'qc'], function () {
    Route::get('/show', [App\Http\Controllers\QCController::class, 'manager_qc_show'])->name('manager.qc.show');
    Route::post('/show_data', [App\Http\Controllers\QCController::class, 'manager_qc_show_data'])->name('manager.qc.show.data');
    Route::get('/detail', [App\Http\Controllers\QCController::class, 'manager_qc_detail'])->name('manager.qc.detail');
    Route::post('/detail_data', [App\Http\Controllers\QCsController::class, 'manager_qc_detail_data'])->name('manager.qc.detail.data');
});

Route::group(['prefix' => 'logistik'], function () {
    Route::get('/show', [App\Http\Controllers\LogistikController::class, 'manager_logistik_show'])->name('manager.logistik.show');
    Route::post('/show_data', [App\Http\Controllers\LogistikController::class, 'manager_logistik_show_data'])->name('manager.logistik.show.data');
    Route::get('/detail', [App\Http\Controllers\LogistikController::class, 'manager_logistik_detail'])->name('manager.logistik.detail');
    Route::post('/detail_data', [App\Http\Controllers\LogistikController::class, 'manager_logistik_detail_data'])->name('manager.logistik.detail.data');
}); 
?>
