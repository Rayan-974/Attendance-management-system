<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $attendances = $user->attendances()->orderBy('date', 'desc')->take(10)->get();
        $todayAttendance = $user->attendances()->where('date', Carbon::today())->first();
        $leaves = $user->leaves()->orderBy('created_at', 'desc')->take(5)->get();
        $tasks = $user->tasksAssignedToMe()->orderBy('due_date', 'asc')->get();

        return view('student.dashboard', compact('attendances', 'todayAttendance', 'leaves', 'tasks'));
    }

    public function markAttendance(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if already marked (Lock Mechanism)
        if ($user->attendances()->where('date', $today)->exists()) {
            return back()->with('error', 'Attendance already marked for today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => Carbon::now(),
            'status' => 'present',
        ]);

        return back()->with('success', 'Attendance marked successfully!');
    }

    public function submitLeave(Request $request, \App\Services\WhatsAppService $whatsapp)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $leave = Leave::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        $user = Auth::user();
        $phone = $user->phone ?? '+1234567890';
        $whatsapp->sendMessage($phone, "Hello {$user->name}, your leave request from {$leave->start_date} to {$leave->end_date} has been submitted and is pending review.");

        return back()->with('success', 'Leave request submitted successfully.');
    }

    public function submitTaskResponse(Request $request, Task $task)
    {
        if ($task->assigned_to !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'student_response' => 'required|string',
            'submission_file' => 'nullable|file|max:10240',
        ]);

        $updateData = [
            'student_response' => $request->student_response,
            'status' => 'completed',
        ];

        if ($request->hasFile('submission_file')) {
            $path = $request->file('submission_file')->store('task-submissions', 'public');
            $updateData['submission_file_path'] = $path;
        }

        $task->update($updateData);

        return back()->with('success', 'Task response submitted successfully.');
    }
}
