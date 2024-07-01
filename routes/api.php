<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PpicController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
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

Route::post('/login', [App\Http\Controllers\ApiController::class, 'authenticate']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'id' => $request->user()->id,
        'username' => $request->user()->karyawan->nama,
        'divisi_id' =>  $request->user()->divisi_id,
        'foto' => $request->user()->foto,
    ]);
});

Route::prefix('/hr')->group(function () {
    Route::prefix('/meet')->group(function () {
        Route::get('/getKaryawan', [MeetingController::class, 'getKaryawanForNotulen'])->middleware('jwt.verify');

        Route::prefix('/lokasi')->group(function () {
            Route::post('/store', [MeetingController::class, 'store_lokasi_meet']);
            Route::get('/show', [MeetingController::class, 'show_lokasi_meet']);
            Route::post('/update/', [MeetingController::class, 'update_lokasi_meet']);
            Route::post('/delete/', [MeetingController::class, 'delete_lokasi_meet']);
        });
        Route::prefix('/jadwal')->group(function () {
            Route::post('/', [MeetingController::class, 'store_jadwal_meet'])->middleware('jwt.verify');
            Route::get('/show_id/{id}', [MeetingController::class, 'show_jadwal_meet_id']);
            Route::get('/show/{status}', [MeetingController::class, 'show_jadwal_meet'])->middleware('jwt.verify');
            Route::get('/{id}', [MeetingController::class, 'detail_jadwal_meet']);
            Route::get('/print/{id}', [MeetingController::class, 'cetakUndangan']);
            Route::put('/{id}', [MeetingController::class, 'update_jadwal_meet'])->middleware('jwt.verify');
            Route::post('/update/{status}', [MeetingController::class, 'update_status_meet'])->middleware('jwt.verify');
            Route::get('/show_peserta/{id}', [MeetingController::class, 'show_peserta']);
            Route::get('/checkApproval/{id}', [MeetingController::class, 'checkApproval'])->middleware('jwt.verify');
        });
        Route::prefix('/show_dokumen_ftp')->group(function () {
            Route::get('/', [MeetingController::class, 'show_dokumen_ftp']);
        });
        Route::prefix('/jadwal_person')->group(function () {
            Route::get('/show/{status}', [MeetingController::class, 'show_jadwal_meet_person'])->middleware('jwt.verify');
            Route::get('/detail/{id}', [MeetingController::class, 'detail_jadwal_meet_person'])->middleware('jwt.verify');
            Route::post('/kehadiran', [MeetingController::class, 'update_hadir_jadwal_meet'])->middleware('jwt.verify');
        });
        Route::prefix('/notulen')->group(function () {
            Route::post('/verif', [MeetingController::class, 'verif_notulen_meet'])->middleware('jwt.verify');
            Route::post('/', [MeetingController::class, 'store_notulen_meet'])->middleware('jwt.verify');
            Route::get('/{id}', [MeetingController::class, 'show_notulen_meet']);
        });
        Route::prefix('/hasil')->group(function () {
            Route::post('/dokumen', [MeetingController::class, 'upload_dokumen']);
            Route::post('/dokumen_ftp', [MeetingController::class, 'upload_dokumen_ftp']);
            Route::post('/', [MeetingController::class, 'store_hasil_meet']);
            Route::get('/{id}', [MeetingController::class, 'show_hasil_meet']);
            Route::get('/print/{id}', [MeetingController::class, 'cetakHasil']);
            Route::get('/edit/{id}', [MeetingController::class, 'edit_hasil_meet']);
            Route::put('/{id}', [MeetingController::class, 'update_hasil_meet']);
            Route::delete('/{id}', [MeetingController::class, 'delete_hasil_meet']);
            Route::post('/upload', [MeetingController::class, 'upload_dok']);
        });
    });
});

Route::namespace('v2')->group(__DIR__ . '/yogi/api.php');
Route::namespace('inventory')->group(__DIR__ . '/inventory/api.php');
// check connection
Route::get('/check', function () {
    try {
        DB::connection()->getPdo();
        if (DB::connection()->getDatabaseName()) {
            echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
        } else {
            die("Could not find the database. Please check your configuration.");
        }
    } catch (\Exception $e) {
        die("Could not open connection to database server.  Please check your configuration." . $e->getMessage());
    }
});
