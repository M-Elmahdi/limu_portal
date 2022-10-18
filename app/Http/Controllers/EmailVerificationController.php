<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function index(){
        return view('auth.email.notice');
    }

    public function verified(){
        return view('auth.email.verified');
    }

    public function sendEmail(Request $request){
        
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }

    public function verify(EmailVerificationRequest $request){
        
        $request->fulfill();
 
        return redirect()->route('verification.verified');
    }
}
