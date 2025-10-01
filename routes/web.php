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
use App\Http\Controllers\EvaluationSectionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\StrengthLearningController;




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



Route::prefix('mypurpose')->group(function () {
    Route::get('/', [UserPurposeController::class, 'index'])->name('mypurpose.index');
    Route::get('/create', [UserPurposeController::class, 'create'])->name('mypurpose.create');
    Route::post('/', [UserPurposeController::class, 'store'])->name('mypurpose.store');
    Route::get('/{purpose}', [UserPurposeController::class, 'show'])->name('mypurpose.show');
    Route::get('/{purpose}/edit', [UserPurposeController::class, 'edit'])->name('mypurpose.edit');
    Route::put('/{purpose}', [UserPurposeController::class, 'update'])->name('mypurpose.update');
    Route::delete('/{purpose}', [UserPurposeController::class, 'destroy'])->name('mypurpose.destroy');
});


Route::middleware(['auth'])->group(function () {


    Route::get('/manager/users', [ManagerDashboardController::class, 'index'])->name('manager.users.index');
     Route::get('/manager/users/{user}/{periodId}', [ManagerDashboardController::class, 'show'])
    ->name('manager.user.show');



    //view users to be approver appraisal with inline edit 

    Route::get('/manager/appraisal/{user}/{period}', [ManagerDashboardController::class, 'apshow'])
    ->name('manager.appraisal.show');


    Route::get('/manager/reviewers/{user}/{period}', [ManagerDashboardController::class, 'reviewershow'])
    ->name('manager.reviewer.show');
  

//perfomance summaries
Route::get('/performance-summaries', [PerformanceApraisalController::class, 'performanceSummaries'])
    ->name('manager.performance_summaries.index');



    // Approved Users
    Route::get('/manager/departmentaltarget', [ManagerDashboardController::class, 'target'])->name('manager.departmentaltarget.targets');
    Route::get('/manager/departmentaltarget/{user}/{periodId}', [ManagerDashboardController::class, 'targets'])
    ->name('manager.user.target');



    
    // New approval routes
    Route::post('/manager/users/{user}/approve/{period}', [ManagerDashboardController::class, 'approve'])->name('manager.users.approve');
    Route::post('/manager/users/{user}/reject/{period}', [ManagerDashboardController::class, 'reject'])->name('manager.users.reject');


// New athourize routes
Route::post('/manager/appraisals/{user}/approve/{period}', [ManagerDashboardController::class, 'authorisation'])->name('manager.appraisals.approve');
Route::post('/manager/reviewers/{user}/{period}/review', [ManagerDashboardController::class, 'reviewed'])
    ->name('manager.reviewers.review');
Route::post('/manager/appraisals/{user}/reject/{period}', [ManagerDashboardController::class, 'apreject'])->name('manager.appraisals.reject');
Route::post('/manager/reviewers/{user}/reject/{period}', [ManagerDashboardController::class, 'reviewreject'])->name('manager.reviewers.reject');


//reviewing routes
Route::post('/manager/reviewers/{user}/approve/{period}', [ManagerDashboardController::class, 'review'])->name('manager.reviewers.approve');
Route::post('/manager/reviewers/{user}/reject/{period}', [ManagerDashboardController::class, 'reviewreject'])->name('manager.reviewers.reject');




    Route::get('/manager/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('/manager/dashboardap', [ManagerDashboardController::class, 'appraisal'])->name('manager.dashboardap');
    Route::get('/manager/reviewerdash', [ManagerDashboardController::class, 'reviewer'])->name('manager.reviewerdash');
   
            //view users to be review appraisal with inline edit 
    // Route::get('/manager/reviewers/{user}', [ManagerDashboardController::class, 'reviewershow'])->name('manager.reviewer.show');

    // Route::get('/manager/reviewers/{user}/{period}', [ManagerDashboardController::class, 'reviewershow'])
    // ->name('manager.reviewer.show');




    
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

    //evalutaions sections

    Route::resource('evaluation_sections', EvaluationSectionController::class)
         ->only(['index', 'create', 'store']);

    //task

    Route::resource('tasks', TaskController::class)
    ->only(['index', 'create', 'store']);

//ratings
Route::resource('ratings', RatingController::class)
->only(['index', 'create', 'store']);

//update rating
Route::post('ratings/update-self/{rating}', [RatingController::class, 'updateSelf'])->name('ratings.updateSelf');
Route::post('ratings/save-all', [RatingController::class, 'saveAll'])->name('ratings.saveAll');



Route::patch('ratings/{rating}/update-self', [PerformanceApraisalController::class, 'updateSelf'])->name('ratings.updateSelf');
Route::post('ratings/save-all', [PerformanceApraisalController::class, 'saveAll'])->name('ratings.saveAll');

// routes/web.php

// For manager dashboard only - saving assessor ratings
Route::post('manager/ratings/save-reviewer', [ManagerDashboardController::class, 'saveAssessorRatings'])
    ->name('manager.ratings.saveAssessor');

    Route::post('manager/ratings/save-reviewer', [ManagerDashboardController::class, 'saveReviewerRatings'])
    ->name('manager.ratings.saveReviewer');


//strength
// Self perception
Route::get('/my/strengths-learning', [StrengthLearningController::class, 'index'])->name('strengths.learning.index');
Route::post('/my/strengths', [StrengthLearningController::class, 'storeStrength'])->name('strengths.store');
Route::post('/my/learning-areas', [StrengthLearningController::class, 'storeLearning'])->name('strengths.learning.storeLearningArea');

// Self inline edit & delete
Route::patch('/strengths/{id}/update', [StrengthLearningController::class, 'updateStrength'])->name('strengths.update');
Route::delete('/strengths/{id}/delete', [StrengthLearningController::class, 'destroyStrength'])->name('strengths.destroy');

Route::patch('/learning/{id}/update', [StrengthLearningController::class, 'updateLearning'])->name('learning.update');
Route::delete('/learning/{id}/delete', [StrengthLearningController::class, 'destroyLearning'])->name('learning.destroy');

// Assessor perception
Route::post('/assessor/strengths', [StrengthLearningController::class, 'storeAssessorStrength'])->name('strengths.assessor.store');
Route::post('/assessor/learning-areas', [StrengthLearningController::class, 'storeAssessorLearning'])->name('strengths.learning.assessor.store');

Route::patch('/assessor/strength/{id}', [StrengthLearningController::class, 'updateAssessorStrength'])->name('strengths.assessor.update');
Route::patch('/assessor/learning/{id}', [StrengthLearningController::class, 'updateAssessorLearning'])->name('learning.assessor.update');


// Assessor inline edit & delete
Route::patch('/assessor/strengths/{id}/update', [StrengthLearningController::class, 'updateAssessorStrength'])->name('strengths.assessor.update');
Route::delete('/assessor/strengths/{id}/delete', [StrengthLearningController::class, 'destroyAssessorStrength'])->name('strengths.assessor.destroy');

Route::patch('/assessor/learning/{id}/update', [StrengthLearningController::class, 'updateAssessorLearning'])->name('learning.assessor.update');
Route::delete('/assessor/learning/{id}/delete', [StrengthLearningController::class, 'destroyAssessorLearning'])->name('learning.assessor.destroy');
    //Manager updates
     // Manager Purpose Routes
    Route::get('manager/purposes/{purpose}/edit', [PurposeController::class, 'managerEdit'])->name('manager.purposes.edit');
    Route::patch('manager/purposes/{purpose}', [PurposeController::class, 'managerUpdate'])->name('manager.purposes.update');

    // Manager Objective Routes
    Route::get('manager/objectives/{objective}/edit', [ObjectiveController::class, 'managerEdit'])->name('manager.objectives.edit');
    Route::patch('manager/objectives/{objective}', [ObjectiveController::class, 'managerUpdate'])->name('manager.objectives.update');


    Route::patch('/targets/{id}/assessor-update-inline', [TargetController::class, 'assessorUpdateInline'])
    ->name('targets.assessorUpdateInline');

    Route::patch('/targets/{id}/reviewer-update-inline', [TargetController::class, 'reviewerUpdateInline'])
    ->name('targets.reviewerUpdateInline');
    
     // Manager Actions to support objectives Routes
        // Manager Initiative Routes
    Route::get('manager/initiatives/{initiative}/edit', [InitiativeController::class, 'managerEdit'])->name('manager.initiatives.edit');
    Route::patch('manager/initiatives/{initiative}', [InitiativeController::class, 'managerUpdate'])->name('manager.initiatives.update');

    Route::match(['put', 'patch'], '/initiatives/{id}/update-inline', [InitiativeController::class, 'updateInline'])
    ->name('initiatives.updateInline');

    

    // inline edit
   // Inline update for targets
Route::patch('/targets/{id}/update-inline', [TargetController::class, 'updateInlineTarget'])->name('targets.updateInline');



    Route::get('/user/performance/{period}/appraisal-report', [InitiativeController::class, 'showAppraisalReport'])->name('user.performance.appraisal.report');

// Full resource routes (index, create, store, show, edit, update, destroy)
   

    Route::get('/report', [ReportController::class, 'create'])->name('report.create');
    Route::get('/myreport', [ReportController::class, 'mycreate'])->name('report.mycreate');
    Route::get('/appraisalreport', [ReportController::class, 'apcreate'])->name('appraisalreport.apcreate');

    Route::post('/report/generate', [ReportController::class, 'generate'])->name('report.generate');
    Route::post('/reportappraisal/apgenerate', [ReportController::class, 'apgenerate'])->name('reportappraisal.apgenerate');


});


