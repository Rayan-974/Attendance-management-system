<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <a href="{{ route('admin.reports.index') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white font-bold py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:shadow-brand/30 transition-all duration-300 transform hover:-translate-y-0.5 text-sm">
                View Reports Engine &rarr;
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Pending Leaves Approval -->
            <div class="bg-white/90 overflow-hidden shadow-lg sm:rounded-2xl border border-white/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-brand-dark mb-4">Pending Leave Requests</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-brand-light/30">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Student</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dates</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pendingLeaves as $leave)
                                    <tr class="hover:bg-brand-light/20 transition-colors duration-200">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-800 font-medium">{{ $leave->user->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 max-w-xs truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                            <form action="{{ route('admin.leave.status', $leave) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-green-600 hover:text-green-900 font-bold mr-3">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.leave.status', $leave) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-4 text-center text-sm text-slate-500">No pending leave requests.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tasks Awaiting Review -->
            <div class="bg-white/90 overflow-hidden shadow-lg sm:rounded-2xl border border-white/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-brand-dark">Tasks Awaiting Review</h3>
                        <a href="{{ route('admin.task.create') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white font-bold py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:shadow-brand/30 transition-all duration-300 transform hover:-translate-y-0.5 text-sm">
                            + Assign New Task
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-brand-light/30">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Task Title</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Student</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Submitted Response</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tasksAwaitingReview as $task)
                                    <tr class="hover:bg-brand-light/20 transition-colors duration-200">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-800 font-medium">{{ $task->title }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">{{ $task->assignee->name }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600 max-w-xs">
                                            <div class="truncate mb-1" title="{{ $task->student_response }}">{{ $task->student_response }}</div>
                                            @if($task->submission_file_path)
                                                <a href="{{ asset('storage/' . $task->submission_file_path) }}" target="_blank" class="text-brand hover:text-brand-dark text-xs font-semibold inline-flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    Attachment
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                            <form action="{{ route('admin.task.status', $task) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-green-600 hover:text-green-900 font-bold mr-3">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.task.status', $task) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-4 text-center text-sm text-slate-500">No tasks currently awaiting review.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Student Management List -->
            <div class="bg-white/90 overflow-hidden shadow-lg sm:rounded-2xl border border-white/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-brand-dark mb-4">Student Attendance Overview</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-brand-light/30">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Monthly Grade</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Present</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Absent</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    <tr class="hover:bg-brand-light/20 transition-colors duration-200">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-slate-800">{{ $student->name }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600">{{ $student->email }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            @php
                                                $gradeColors = [
                                                    'A' => 'bg-green-100 text-green-800 border-green-200',
                                                    'B' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                    'C' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'D' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                    'F' => 'bg-red-100 text-red-800 border-red-200',
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 rounded-full font-bold border {{ $gradeColors[$student->grade] ?? 'bg-gray-100' }}">{{ $student->grade }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="px-3 py-1 bg-brand-light text-brand-dark rounded-full font-bold border border-brand/20">{{ $student->present_count }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full font-bold border border-red-200">{{ $student->absent_count }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('admin.student.attendance', $student) }}" class="text-brand hover:text-brand-dark font-semibold transition">Manage Attendance &rarr;</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
