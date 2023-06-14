<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApotekController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/token', [ApotekController::class, 'token']);
Route::get('/apotek', [ApotekController::class, 'index'])->name('index'); //buat show semua data
Route::post('/apotek/add', [ApotekController::class, 'store'])->name('add'); // buat tambah data
Route::get('/apotek/trash', [ApotekController::class, 'trash'])->name('trash');
Route::get('/apotek/{id}', [ApotekController::class, 'show'])->name('show'); // buat liat satu data
Route::patch('/apotek/update/{id}', [ApotekController::class, 'update'])->name('edit'); //buat ngedit salah satu data
Route::delete('/apotek/delete/{id}', [ApotekController::class, 'destroy'])->name('softdelete'); //buat hapus terus nanti munculnya ke trash
Route::get('/apotek/trash/restore/{id}', [ApotekController::class, 'restore'])->name('restore');
Route::get('/apotek/delete/permanent/{id}', [ApotekController::class, 'permanentDelete'])->name('permanent');
