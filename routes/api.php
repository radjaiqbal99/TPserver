<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\daftarPegawaiController;
use App\Http\Controllers\daftarKasirController;
use App\Http\Controllers\dompetKasirController;
use App\Http\Controllers\dompetPegawaiController;
use App\Http\Controllers\upahPegawaiController;
use App\Http\Controllers\upahKasirController;
use App\Http\Controllers\hargaPasirController;
use App\Http\Controllers\pencatatanController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceResponse;
// header('Access-Control-Allow-Origin: http://natunasandmine.com');
// header('Access-Control-Allow-Methods: OPTIONS, POST, GET, PUT, DELETE');
// header('Access-Control-Allow-Headers: http://natunasandmine.com');
// header('Access-Control-Allow-Credentials: true');
// header('Access-Control-Allow-Credentials: true');
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
/// login
Route::post('/login', [LoginController::class,'login']);
// daftar pegawai

// Route::resource('/daftarPegawai', daftarPegawaiController::class);
// Route::resource('/daftarKasir', daftarKasirController::class);
// Route::resource('/upahKasir', daftarKasirController::class);
// Route::resource('/upahPegawai', daftarPegawaiController::class);
// Route::resource('/hargaPasir', daftarKasirController::class);
Route::get('/dompetKasir', [dompetKasirController::class,'index']);
Route::get('/dompetPegawai', [dompetPegawaiController::class,'index']);
Route::get('/rf', [ResourcesController::class, 'index']);
Route::get('/pencatatan', [pencatatanController::class, 'index']);
Route::post('/pencatatan', [pencatatanController::class, 'store']);

Route::get('/daftarPegawai', [daftarPegawaiController::class,'index']);
Route::get('/daftarPegawai/{id}', [daftarPegawaiController::class,'show']);
Route::post('/daftarPegawai', [daftarPegawaiController::class,'store']);
Route::put('/daftarPegawai/{id}', [daftarPegawaiController::class,'update']);
Route::delete('/daftarPegawai/{id}', [daftarPegawaiController::class,'destroy']);
// daftar kasir
Route::get('/daftarKasir', [daftarKasirController::class,'index']);
Route::post('/daftarKasir', [daftarKasirController::class,'store']);
Route::put('/daftarKasir/{id}', [daftarKasirController::class,'update']);
Route::delete('/daftarKasir/{id}', [daftarKasirController::class,'destroy']);
// Dompet 
// Upah Kasir
Route::get('/upahKasir', [upahKasirController::class, 'index']);
Route::post('/upahKasir', [upahKasirController::class, 'store']);
Route::put('/upahKasir/{id}', [upahKasirController::class, 'update']);
Route::delete('/upahKasir/{id}', [upahKasirController::class, 'destroy']);
// Upah Pegawai
Route::get('/upahPegawai', [upahPegawaiController::class, 'index']);
Route::post('/upahPegawai', [upahPegawaiController::class, 'store']);
Route::put('/upahPegawai/{id}', [upahPegawaiController::class, 'update']);
Route::delete('/upahPegawai/{id}', [upahPegawaiController::class, 'destroy']);
// Harga Pasir
Route::get('/hargaPasir', [hargaPasirController::class, 'index']);
Route::post('/hargaPasir', [hargaPasirController::class, 'store']);
Route::put('/hargaPasir/{id}', [hargaPasirController::class, 'update']);
Route::delete('/hargaPasir/{id}', [hargaPasirController::class, 'destroy']);
// Pencatatan