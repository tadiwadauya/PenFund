<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\UsermanagementController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\InitiativeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PerfomanceController;




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
    return view('/auth/login');
});

Auth::routes();

Route::get('admin/home', [App\Http\Controllers\HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('admin-home', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin-home');
Route::get('/mypurpose', [ContractController::class, 'mypurpose'])->name('purposes.mypurpose');
Route::get('/myobjective', [ContractController::class, 'myobjective'])->name('objectives.myobjective');
Route::get('/myinitiative', [ContractController::class, 'myinitiative'])->name('initiatives.myinitiative');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('departments', DepartmentController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('jobtitles', JobTitleController::class);
    Route::resource('users', UsermanagementController::class);
    Route::resource('jobtitles', JobTitleController::class);
    Route::resource('contracts', ContractController::class);
    Route::resource('periods', PeriodController::class);
    Route::resource('targets', TargetController::class);
    Route::resource('objectives', ObjectiveController::class);
    Route::resource('purposes', PurposeController::class);
    Route::resource('mypurpose', PerfomanceController::class);
    
    Route::resource('initiatives', InitiativeController::class);
    Route::get('/report', [ReportController::class, 'create'])->name('report.create');
Route::post('/report/generate', [ReportController::class, 'generate'])->name('report.generate');


});


