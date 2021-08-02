<?php

use Illuminate\Http\Request;
use App\Jobs\DownloadPhotoJob;
use App\Events\DownloadPhotoEvent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GalleryController;
use App\Models\Gallery;

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
//user sign
Route::post('signin', [AuthController::class , 'signin' ]);
//user signup
Route::post('signup', [AuthController::class , 'signup' ]);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'auth:api'
], function () {
    //user signout
    Route::post('signout', [AuthController::class , 'signout' ]);

    //receive image
    Route::get('images', [GalleryController::class, 'index']);

    //post image
    Route::post('images', [GalleryController::class, 'store']);
});