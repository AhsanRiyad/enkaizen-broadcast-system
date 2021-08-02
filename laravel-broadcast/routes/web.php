<?php

use App\Jobs\DownloadPhotoJob;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debugbar', function () {
    dispatch(new DownloadPhotoJob( 1 , 'https://images.all-free-download.com/images/graphicthumb/beautiful_natural_scenery_04_hd_pictures_166229.jpg'));
    return view('welcome');
    // return "good one";
});
