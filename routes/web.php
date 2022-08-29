<?php

use App\Models\Student;
use App\Models\StudentResult;
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

Route::get('/test', function(){
    dd(Student::with('marks')
        //where('std_id', 143)->
        ->where('std_id', 1578)
    );
});
