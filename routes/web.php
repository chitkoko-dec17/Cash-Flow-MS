<?php

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

@include_once('admin_web.php');

Route::get('/', function () {
   return view('welcome');
});

Route::get('/login', function () {
    return redirect()->route('login');
});

// login route setup
Route::prefix('login')->group(function () {
    Route::view('/', 'admin.authentication.login')->name('login');
    Route::view('default', 'admin.authentication.login')->name('login.index');
});


Route::get('/test-db-connection', function () {
    try {
        DB::connection()->getPdo();
       // DB::select('select * from users where active = ?', [1])

        return 'Connected to the database!';
    } catch (\Exception $e) {
        return 'Failed to connect to the database: ' . $e->getMessage();
    }
});

Route::view('sample-page', 'admin.pages.sample-page')->name('sample-page');

Route::prefix('dashboard')->group(function () {
    Route::view('/', 'admin.dashboard.default')->name('index');
    Route::view('default', 'admin.dashboard.default')->name('dashboard.index');
});

Route::view('default-layout', 'multiple.default-layout')->name('default-layout');
Route::view('compact-layout', 'multiple.compact-layout')->name('compact-layout');
Route::view('modern-layout', 'multiple.modern-layout')->name('modern-layout');
