<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Faculty\FacultyHomeController;
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

/**
 *
 * Admin Routes
 *
 */
Route::group(['prefix' => 'admin', 'middleware' => ['role:Super Admin']], function(){
    
    Route::get('users', [AdminHomeController::class, 'index'])->name('admin.index');

});


/**
 * 
 * Student Routes
 * 
 */
Route::group(['prefix' => 'student', 'middleware' => ['role:Student']], function(){
    
    Route::get('users', function(){
        return 'Student Dash';
    })->name('student.index');

});


/**
 * 
 * Faculty Routes
 * 
 */
Route::group(['prefix' => 'faculty', 'middleware' => ['role:Faculty']], function(){
    
    Route::get('users', [FacultyHomeController::class, 'index'])->name('faculty.index');

});