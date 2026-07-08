<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all students
        $students = User::role('student')->get();
        
        // Eager load user relationship for efficiency
        $query = Attendance::with('user')->orderBy('date', 'desc');

        // Default to current month if no dates are provided
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        
        $query->whereBetween('date', [$startDate, $endDate]);

        // Filter by specific student if selected
        $studentId = $request->input('student_id');
        if ($studentId) {
            $query->where('user_id', $studentId);
        }

        $attendances = $query->get();

        // Calculate Summary Counts
        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();

        // Calculate Leave Days in the given date range
        $leavesQuery = Leave::where('status', 'approved')
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($studentId) {
            $leavesQuery->where('user_id', $studentId);
        }

        $approvedLeaves = $leavesQuery->get();
        $leaveDaysCount = 0;

        $startLimit = Carbon::parse($startDate)->startOfDay();
        $endLimit = Carbon::parse($endDate)->startOfDay();

        foreach ($approvedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date)->startOfDay();
            $leaveEnd = Carbon::parse($leave->end_date)->startOfDay();

            // Find overlapping range
            $actualStart = $leaveStart->max($startLimit);
            $actualEnd = $leaveEnd->min($endLimit);

            if ($actualStart->lte($actualEnd)) {
                $leaveDaysCount += $actualStart->diffInDays($actualEnd) + 1; // +1 to include both days
            }
        }

        return view('admin.reports', compact('students', 'attendances', 'startDate', 'endDate', 'presentCount', 'absentCount', 'leaveDaysCount'));
    }
}
