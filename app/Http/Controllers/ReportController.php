<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $students = User::where('role', 'student')->get();
        
        // Eager load user relationship for efficiency
        $query = Attendance::with('user')->orderBy('date', 'desc');

        // Default to current month if no dates are provided
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        
        $query->whereBetween('date', [$startDate, $endDate]);

        // Filter by specific student if selected
        if ($request->filled('student_id')) {
            $query->where('user_id', $request->student_id);
        }

        $attendances = $query->get();

        return view('admin.reports', compact('students', 'attendances', 'startDate', 'endDate'));
    }
}
