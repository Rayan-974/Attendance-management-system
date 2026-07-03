<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Attendance Marking Card -->
                <div class="bg-white/90 overflow-hidden shadow-lg sm:rounded-2xl border border-white/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-brand-dark mb-4">Daily Attendance</h3>
                        
                        @if($todayAttendance)
                            <div class="flex items-center space-x-3 text-brand-dark bg-brand-light/50 p-4 rounded-md border border-brand/30">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="font-medium">You have already marked your attendance today at {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') }}.</span>
                            </div>
                        @else
                            <form action="{{ route('student.attendance.mark') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg hover:shadow-brand/30 transition-all duration-300 transform hover:-translate-y-0.5">
                                    Mark Present for Today
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Submit Leave Form -->
                <div class="bg-white/90 overflow-hidden shadow-lg sm:rounded-2xl border border-white/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-brand-dark mb-4">Request Leave</h3>
                        <form action="{{ route('student.leave.submit') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Start Date</label>
                                    <input type="date" name="start_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">End Date</label>
                                    <input type="date" name="end_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Reason</label>
                                <textarea name="reason" rows="2" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand"></textarea>
                            </div>
                            <button type="submit" class="bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-900 hover:to-slate-800 text-white font-bold py-2 px-5 rounded-full shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 mt-2">
                                Submit Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tasks Viewer -->
            <div class="bg-white/90 overflow-hidden shadow-lg sm:rounded-2xl border border-white/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-brand-dark mb-4">Assigned Tasks</h3>
                    <div class="space-y-4">
                        @forelse($tasks as $task)
                            <div class="border border-gray-200 rounded-md p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-md font-bold text-slate-800">{{ $task->title }}</h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>
                                <div class="text-sm text-slate-600 mb-4 prose max-w-none">
                                    {!! $task->description !!}
                                </div>
                                <div class="text-xs text-slate-500 mb-4">Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</div>
                                
                                @if($task->status !== 'completed')
                                    <form action="{{ route('student.task.response', $task) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Your Response</label>
                                        <textarea name="student_response" rows="3" required class="block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand mb-3"></textarea>
                                        
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Upload File (Optional)</label>
                                        <input type="file" name="submission_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-light file:text-brand-dark hover:file:bg-brand/20 mb-4">
                                        
                                        <button type="submit" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white font-bold py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:shadow-brand/30 transition-all duration-300 transform hover:-translate-y-0.5 text-sm">
                                            Submit Task
                                        </button>
                                    </form>
                                @else
                                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                        <span class="block text-xs font-bold text-slate-700 mb-1">Your Submitted Response:</span>
                                        <p class="text-sm text-slate-700">{{ $task->student_response }}</p>
                                        @if($task->submission_file_path)
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <a href="{{ asset('storage/' . $task->submission_file_path) }}" target="_blank" class="text-brand hover:text-brand-dark text-sm font-semibold inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    View/Download Submitted File
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No tasks currently assigned.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Data Tables -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Recent Attendance Table -->
                <div class="bg-white/90 overflow-hidden shadow-lg sm:rounded-2xl border border-white/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-brand-dark mb-4">Recent Attendance</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-light/30">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($attendances as $record)
                                        <tr class="hover:bg-brand-light/20 transition-colors duration-200">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-800">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($record->status === 'present')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-brand-light text-brand-dark border border-brand/20">Present</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">{{ ucfirst($record->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="px-4 py-3 text-center text-sm text-slate-500">No records found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Leaves Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-brand/20">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-brand-dark mb-4">Recent Leaves</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-light/30">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dates</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($leaves as $leave)
                                        <tr class="hover:bg-brand-light/20 transition-colors duration-200">
                                            <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-800">
                                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($leave->status === 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Approved</span>
                                                @elseif($leave->status === 'rejected')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Rejected</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="px-4 py-3 text-center text-sm text-slate-500">No leaves requested.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
