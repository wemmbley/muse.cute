<?php

use App\Http\Controllers\Admin\TracksController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect(url('/admin/tracks'));
    })->name('dashboard');
    Route::get('/tracks', [TracksController::class, 'getAllTracks'])->name('/admin/tracks');
    Route::get('/get-track/{trackName}', [TracksController::class, 'getTrack'])->name('/admin/get-track/{trackName}');
    Route::post('/update-track/{trackName}', [TracksController::class, 'updateTrack']);
    Route::post('/upload-track', [TracksController::class, 'uploadTrack']);
    Route::post('/delete-track', [TracksController::class, 'deleteTrack']);
});

Route::get('/{user}/{track}', [TracksController::class, 'loadTrack']);
