<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Navigator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->hasRole('Super Admin')) {
            return redirect()->route('admin.index');
        }

        if (auth()->user()->hasRole('Faculty')) {
            return redirect()->route('faculty.index');
        }

        if (auth()->user()->hasRole('Student')) {
            return redirect()->route('student.index');
        }
    }
}
