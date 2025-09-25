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
use App\Http\Controllers\UserPurposeController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\UserPerformanceController;
use App\Http\Controllers\PerformanceApraisalController;
use App\Http\Controllers\TaskController;





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
Route::get('/myobjective', [ContractController::class, 'myobjective'])->name('objectives.myobjective');
Route::get('/myinitiative', [ContractController::class, 'myinitiative'])->name('initiatives.myinitiative');
Route::get('/mypurpose', [ContractController::class, 'mypurpose'])->name('purposes.mypurpose');



//Task
Route::resource('tasks', TaskController::class);


Route::middleware(['auth'])->group(function () {
    Route::resource('purposes/mypurpose', UserPurposeController::class);

});
Route::middleware(['auth'])->group(function () {


    Route::get('/manager/users', [ManagerDashboardController::class, 'index'])->name('manager.users.index');
    Route::get('/manager/users/{user}', [ManagerDashboardController::class, 'show'])->name('manager.users.show');

    //view users to be approver appraisal with inline edit 
    Route::get('/manager/appraisals/{user}', [ManagerDashboardController::class, 'apshow'])->name('manager.appraisal.show');


        //view users to be review appraisal with inline edit 
    Route::get('/manager/reviewers/{user}', [ManagerDashboardController::class, 'reviewershow'])->name('manager.reviewer.show');

    
    // New approval routes
    Route::post('/manager/users/{user}/approve/{period}', [ManagerDashboardController::class, 'approve'])->name('manager.users.approve');
    Route::post('/manager/users/{user}/reject/{period}', [ManagerDashboardController::class, 'reject'])->name('manager.users.reject');


// New athourize routes
Route::post('/manager/appraisals/{user}/approve/{period}', [ManagerDashboardController::class, 'authorisation'])->name('manager.appraisals.approve');
Route::post('/manager/appraisals/{user}/reject/{period}', [ManagerDashboardController::class, 'apreject'])->name('manager.appraisals.reject');


//reviewing routes
Route::post('/manager/reviewers/{user}/approve/{period}', [ManagerDashboardController::class, 'review'])->name('manager.reviewers.approve');
Route::post('/manager/reviewers/{user}/reject/{period}', [ManagerDashboardController::class, 'reviewreject'])->name('manager.reviewers.reject');




    Route::get('/manager/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('/manager/dashboardap', [ManagerDashboardController::class, 'appraisal'])->name('manager.dashboardap');

    Route::get('/manager/reviewerdash', [ManagerDashboardController::class, 'reviewer'])->name('manager.reviewerdash');
    
    Route::post('/report/generate', [ReportController::class, 'generate'])->name('report.generate');
    Route::post('/appraisalreport/apgenerate', [ReportController::class, 'apgenerate'])->name('appraisalreport.apgenerate');


    Route::get('/my/performance', [UserPerformanceController::class, 'index'])->name('user.performance.index');
    Route::get('/my/performance/{period}', [UserPerformanceController::class, 'show'])->name('user.performance.show');
    Route::post('/my/performance/submit/{period}', [UserPerformanceController::class, 'submitForApproval'])->name('user.performance.submit');

    Route::get('/my/performanceapraisal', [PerformanceApraisalController::class, 'index'])->name('user.performanceapraisal.index');
    Route::get('/my/performanceapraisal/{period}', [PerformanceApraisalController::class, 'show'])->name('user.performanceapraisal.show');
    Route::post('/my/performanceapraisal/submit/{period}', [PerformanceApraisalController::class, 'submitForAuthorisation'])->name('user.performanceapraisal.submit');
    });

   

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
    Route::resource('initiatives', InitiativeController::class);

    //Manager updates
     // Manager Purpose Routes
    Route::get('manager/purposes/{purpose}/edit', [PurposeController::class, 'managerEdit'])->name('manager.purposes.edit');
    Route::patch('manager/purposes/{purpose}', [PurposeController::class, 'managerUpdate'])->name('manager.purposes.update');

    // Manager Objective Routes
    Route::get('manager/objectives/{objective}/edit', [ObjectiveController::class, 'managerEdit'])->name('manager.objectives.edit');
    Route::patch('manager/objectives/{objective}', [ObjectiveController::class, 'managerUpdate'])->name('manager.objectives.update');
    
     // Manager Actions to support objectives Routes
        // Manager Initiative Routes
    Route::get('manager/initiatives/{initiative}/edit', [InitiativeController::class, 'managerEdit'])->name('manager.initiatives.edit');
    Route::patch('manager/initiatives/{initiative}', [InitiativeController::class, 'managerUpdate'])->name('manager.initiatives.update');

    Route::match(['put', 'patch'], '/initiatives/{id}/update-inline', [InitiativeController::class, 'updateInline'])
    ->name('initiatives.updateInline');
    Route::get('/user/performance/{period}/appraisal-report', [InitiativeController::class, 'showAppraisalReport'])->name('user.performance.appraisal.report');

// Full resource routes (index, create, store, show, edit, update, destroy)
   

    Route::get('/report', [ReportController::class, 'create'])->name('report.create');
    Route::get('/myreport', [ReportController::class, 'mycreate'])->name('report.mycreate');
    Route::get('/appraisalreport', [ReportController::class, 'apcreate'])->name('appraisalreport.apcreate');

    Route::post('/report/generate', [ReportController::class, 'generate'])->name('report.generate');
    Route::post('/reportappraisal/apgenerate', [ReportController::class, 'apgenerate'])->name('reportappraisal.apgenerate');


});


