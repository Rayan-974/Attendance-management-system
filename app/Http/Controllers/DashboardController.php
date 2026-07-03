<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isHR()) return redirect()->route('hr.dashboard');
        if ($user->isTeacher()) return redirect()->route('teacher.dashboard');
        return redirect()->route('student.dashboard');
    }
}
