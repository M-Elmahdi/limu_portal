<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Faculty\FacultyHomeController;
use App\Http\Controllers\ResultShowController;
use App\Http\Controllers\student\StudentHomeController;
use App\Http\Controllers\StudentResultController;
use App\Http\Controllers\TestController;
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

//Navigaton Manager
Route::get('/navigator')->middleware('navigator')->name('navigator');
Route::get('/')->middleware('navigator');

Auth::routes();

Auth::routes([
    'register' => false
]);


/** 
 * 
 * Email Verification Routes
 * 
 */
Route::get('/email/verify', [EmailVerificationController::class, 'index'])->middleware('auth')->name('verification.notice');
Route::get('/email/verified', [EmailVerificationController::class, 'verified'])->name('verification.verified');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');


/**
 *
 * Admin Routes
 *
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:Super Admin', 'verified']], function(){
    
    Route::get('home', [AdminHomeController::class, 'index'])->name('admin.home');

});


/**
 * 
 * Faculty Routes
 * 
 */
Route::group(['prefix' => 'faculty', 'middleware' => ['auth', 'role:Faculty', 'verified']], function(){
    
    Route::get('batches', [FacultyHomeController::class, 'showBatches'])->name('faculty.batches');
    Route::get('batch-courses/{id}', [FacultyHomeController::class, 'showBatchCourses'])->name('faculty.index');
    Route::get('exams/{batch_id}/{course_id}', [FacultyHomeController::class, 'showExams'])->name('faculty.exams');
    Route::get('students/results/{exam_id}/{batch_id}/{course_id}', [FacultyHomeController::class, 'showStudentResults'])->name('faculty.main-exam.results');

});


/** 
 * 
 * Student Routes
 * 
 */
Route::group(['prefix' => 'student', 'middleware' => ['auth', 'role:Student', 'verified']], function(){
    Route::get('home', [StudentHomeController::class, 'index'])->name('student.home');
});


Route::get('/test', [TestController::class, 'bla'])->name('test');

