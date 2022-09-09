<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class FacultyHomeController extends Controller
{
    public function index(){
        
        dd(
            Student::with('marks')->where('std_transfer_to', 4)->get()
        );
        return view('faculty.home');
    }
}
