<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard;
use App\Http\Livewire\BranchComponent;
use App\Http\Livewire\BusinessUnitComponent;
use App\Http\Livewire\RoleComponent;
use App\Http\Livewire\UserComponent;
use App\Http\Livewire\ItemCategoryComponent;
use App\Http\Livewire\ItemComponent;
use App\Http\Livewire\InvoiceTypeComponent;
use App\Http\Controllers\ExpenseInvoiceController;
use App\Http\Controllers\IncomeInvoiceController;
use App\Http\Controllers\ReturnInvoiceController;
use App\Http\Livewire\EstimateBudgetComponent;
use App\Http\Livewire\ProjectComponent;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\ReportController;
use App\Http\Livewire\ReportComponent;
use App\Http\Controllers\UserController;

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
    Route::get('/dashboard',[Dashboard::class, 'index'])->name('dashboard');

    Route::get('/user',UserComponent::class)->name('user.index');
    Route::get('/role',RoleComponent::class)->name('role.index');
    Route::get('/itemcategory',ItemCategoryComponent::class)->name('itemcategory.index');
    Route::get('/item',ItemComponent::class)->name('item.index');
    Route::get('/invoicetype',InvoiceTypeComponent::class)->name('invoicetype.index');
    Route::get('/business-unit',BusinessUnitComponent::class)->name('business-unit.index');
    Route::get('/branch',BranchComponent::class)->name('branch.index');
    Route::get('/project',ProjectComponent::class)->name('project.index');
    Route::get('/budget',EstimateBudgetComponent::class)->name('budget.index');
    Route::get('/reports',ReportComponent::class)->name('report.index');

    //for report
    Route::prefix('report')->group( function(){
        Route::get('expense', [ReportController::class, 'expense'])->name('report.expense');
        Route::get('income', [ReportController::class, 'income'])->name('report.income');
        Route::get('budget', [ReportController::class, 'budget'])->name('report.budget');
    });

    Route::get('expense/{data}', [ReportController::class, 'exportexpense'])->name('exportexpense');
    Route::get('income/{data}', [ReportController::class, 'exportincome'])->name('exportincome');

    //for expense invoice
    Route::resource('expense-invoice', ExpenseInvoiceController::class);
    Route::post('add/exp_note/{id}', [ExpenseInvoiceController::class, 'add_inv_note'])->name('expense-note.add');
    Route::get('/expense-item/history',[ExpenseInvoiceController::class, 'get_item_history'])->name('expense-invoice.item');
    Route::get('/expense/invoice/{id}',[ExpenseInvoiceController::class, 'get_expense_invoice'])->name('expense-invoice.template');
    Route::delete('/expense/item/{id}',[ExpenseInvoiceController::class, 'delete_edit_item']);
    Route::delete('/expense/doc/{id}',[ExpenseInvoiceController::class, 'delete_item_doc']);

    //for income invoice
    Route::resource('income-invoice', IncomeInvoiceController::class);
    Route::post('add/inc_note/{id}', [IncomeInvoiceController::class, 'add_inv_note'])->name('income-note.add');
    Route::get('/income-item/history',[IncomeInvoiceController::class, 'get_item_history'])->name('income-invoice.item');
    Route::get('/income/invoice/{id}',[IncomeInvoiceController::class, 'get_income_invoice'])->name('income-invoice.template');
    Route::delete('/income/item/{id}',[IncomeInvoiceController::class, 'delete_edit_item']);
    Route::delete('/income/doc/{id}',[IncomeInvoiceController::class, 'delete_item_doc']);

    Route::resource('return-invoice', ReturnInvoiceController::class);
    Route::post('invoice/get_items', [CommonController::class, 'get_items'])->name('get.items');

    Route::post('branch/get_projects', [CommonController::class, 'get_projects'])->name('get.projects');
    Route::post('business-unit/get_branches', [CommonController::class, 'get_branches'])->name('get.branches');

    //Temp fix route for user
    Route::prefix('user')->group( function(){

        Route::post('/change-password', [UserController::class, 'updatePassword'])->name('update-password');
        Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('profile/update', [UserController::class, 'updateprofile'])->name('profile.update');
    });

});
