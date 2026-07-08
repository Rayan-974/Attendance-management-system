<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Header -->
            <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center space-x-4">
                    <div class="bg-indigo-50 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ __('Admin Dashboard') }}</h2>
                        <p class="text-sm text-slate-500 font-medium">Manage students, tasks, and leave requests</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-6 rounded-xl shadow-sm transition-all flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-6 rounded-xl shadow-sm transition-all flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Manage Roles
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        View Reports
                    </a>
                </div>
            </div>

            <!-- Pending Leaves & Tasks (2 columns) -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Pending Leaves -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <div class="bg-amber-50 p-2 rounded-lg text-amber-500 mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Pending Leave Requests
                    </h3>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Student & Dates</th>
                                    <th class="px-2 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Reason</th>
                                    <th class="px-2 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($pendingLeaves as $leave)
                                    <tr>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="font-bold text-slate-700">{{ $leave->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}</div>
                                        </td>
                                        <td class="px-2 py-4 text-sm text-slate-600 max-w-[150px] truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                                        <td class="px-2 py-4 whitespace-nowrap text-right text-sm">
                                            <form action="{{ route('admin.leave.status', $leave) }}" method="POST" class="inline-block">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-bold mr-3">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.leave.status', $leave) }}" method="POST" class="inline-block">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-500 hover:text-red-600 font-bold">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-2 py-8 text-center text-sm text-slate-500 italic">No pending leave requests.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tasks Awaiting Review -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="bg-purple-50 p-2 rounded-lg text-purple-500 mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            Tasks Awaiting Review
                        </h3>
                        <a href="{{ route('admin.task.create') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-bold bg-indigo-50 px-4 py-2 rounded-lg transition-colors">
                            + New Task
                        </a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Task & Student</th>
                                    <th class="px-2 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Response</th>
                                    <th class="px-2 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($tasksAwaitingReview as $task)
                                    <tr>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="font-bold text-slate-700">{{ $task->title }}</div>
                                            <div class="text-xs text-slate-500">{{ $task->assignee->name }}</div>
                                        </td>
                                        <td class="px-2 py-4 text-sm text-slate-600 max-w-[150px]">
                                            <div class="truncate mb-1" title="{{ $task->student_response }}">{{ $task->student_response }}</div>
                                            @if($task->submission_file_path)
                                                <a href="{{ asset('storage/' . $task->submission_file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-xs font-bold inline-flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    Attachment
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap text-right text-sm">
                                            <form action="{{ route('admin.task.status', $task) }}" method="POST" class="inline-block">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-bold mr-3">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.task.status', $task) }}" method="POST" class="inline-block">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-500 hover:text-red-600 font-bold">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-2 py-8 text-center text-sm text-slate-500 italic">No tasks currently awaiting review.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Student Management List -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                    <div class="bg-emerald-50 p-2 rounded-lg text-emerald-500 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    Student Attendance Overview
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr>
                                <th class="px-2 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Student</th>
                                <th class="px-2 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Monthly Grade</th>
                                <th class="px-2 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Present</th>
                                <th class="px-2 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Absent</th>
                                <th class="px-2 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($students as $student)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-2 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-700">{{ $student->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $student->email }}</div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-center">
                                        @php
                                            $gradeColors = [
                                                'A' => 'bg-emerald-100 text-emerald-700',
                                                'B' => 'bg-indigo-100 text-indigo-700',
                                                'C' => 'bg-yellow-100 text-yellow-700',
                                                'D' => 'bg-orange-100 text-orange-700',
                                                'F' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $gradeColors[$student->grade] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $student->grade }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold">{{ $student->present_count }}</span>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 bg-red-50 text-red-500 rounded-lg text-xs font-bold">{{ $student->absent_count }}</span>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('admin.student.attendance', $student) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-bold bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                                            Manage &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
