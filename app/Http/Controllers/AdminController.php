<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Task;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Fetch students calculating present/absent counts FOR THE CURRENT MONTH
        $students = User::where('role', 'student')
            ->withCount([
                'attendances as present_count' => function ($query) use ($currentMonth, $currentYear) {
                    $query->where('status', 'present')
                          ->whereMonth('date', $currentMonth)
                          ->whereYear('date', $currentYear);
                },
                'attendances as absent_count' => function ($query) use ($currentMonth, $currentYear) {
                    $query->where('status', 'absent')
                          ->whereMonth('date', $currentMonth)
                          ->whereYear('date', $currentYear);
                }
            ])
            ->get();

        // Calculate Grades based on Present Count
        foreach ($students as $student) {
            if ($student->present_count >= 26) $student->grade = 'A';
            elseif ($student->present_count >= 20) $student->grade = 'B';
            elseif ($student->present_count >= 15) $student->grade = 'C';
            elseif ($student->present_count >= 10) $student->grade = 'D';
            else $student->grade = 'F';
        }

        $pendingLeaves = Leave::with('user')->where('status', 'pending')->get();
        $tasksAwaitingReview = Task::with('assignee')->where('status', 'completed')->get();

        return view('admin.dashboard', compact('students', 'pendingLeaves', 'tasksAwaitingReview'));
    }

    public function updateLeaveStatus(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leave->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id()
        ]);

        return back()->with('success', 'Leave status updated successfully.');
    }

    public function manageAttendance(User $student)
    {
        $attendances = $student->attendances()->orderBy('date', 'desc')->get();
        return view('admin.student-attendance', compact('student', 'attendances'));
    }

    public function storeAttendance(Request $request, User $student)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,half_day',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
        ]);

        $student->attendances()->updateOrCreate(
            ['date' => $request->date],
            [
                'status' => $request->status,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
            ]
        );

        return back()->with('success', 'Attendance record saved successfully.');
    }

    public function deleteAttendance(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance record deleted.');
    }

    public function createTask()
    {
        $students = User::where('role', 'student')->get();
        return view('admin.task-create', compact('students'));
    }

    public function storeTask(Request $request, \App\Services\WhatsAppService $whatsapp)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
            'due_date' => $request->due_date,
            'status' => 'pending'
        ]);

        $student = User::find($request->assigned_to);
        $phone = $student->phone ?? '+1234567890';
        $whatsapp->sendMessage($phone, "Hello {$student->name}, a new task '{$task->title}' has been assigned to you. Due date: {$task->due_date}.");

        return redirect()->route('admin.dashboard')->with('success', 'Task assigned successfully.');
    }

    public function updateTaskStatus(Request $request, Task $task, \App\Services\WhatsAppService $whatsapp)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $task->update(['status' => $request->status]);

        $student = $task->assignee;
        if ($student) {
            $phone = $student->phone ?? '+1234567890';
            $whatsapp->sendMessage($phone, "Hello {$student->name}, your submission for task '{$task->title}' has been {$request->status}.");
        }

        return back()->with('success', 'Task status updated.');
    }
}
