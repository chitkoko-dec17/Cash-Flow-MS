<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard;

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
// Route::prefix('login')->group(function () {
//     Route::view('/', 'admin.authentication.login')->name('login');
//     Route::view('default', 'admin.authentication.login')->name('login.index');
// });


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

// Route::prefix('dashboard')->group(function () {
//     Route::view('/', 'admin.dashboard.default')->name('index');
//     Route::view('default', 'admin.dashboard.default')->name('dashboard.index');
// });

Route::view('default-layout', 'multiple.default-layout')->name('default-layout');
Route::view('compact-layout', 'multiple.compact-layout')->name('compact-layout');
Route::view('modern-layout', 'multiple.modern-layout')->name('modern-layout');


//final add route for cfms
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');

Route::group(['middleware' => ['auth']], function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Route
    Route::get('/', [Dashboard::class, 'index'])->name('index');
    Route::get('/admin-dashboard',[Dashboard::class, 'index'])->name('dashboard');

    // User Route
    // Route::resource('user', UserController::class);

    // Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('update-password');
    // Route::get('admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    // Route::post('profile/update', [AdminController::class, 'updateprofile'])->name('profile.update');

    //Temp fix route for user
    Route::prefix('user')->group( function(){
        Route::view('list', 'cfms.user.list')->name('list');
        Route::view('edit-user', 'cfms.user.edit')->name('edit-user');
        Route::view('create-user', 'cfms.user.create')->name('create-user');
    });
});
