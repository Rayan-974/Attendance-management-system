<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Attendance Reports') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:text-brand-dark transition">&larr; Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-brand/20">
                <div class="p-6">
                    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
                        
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700">Start Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                        </div>
                        
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700">End Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                        </div>

                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700">Student (Optional)</label>
                            <select name="student_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                <option value="">All Students</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="bg-brand hover:bg-brand-dark text-white font-bold py-2 px-6 rounded-md shadow transition duration-150 ease-in-out">
                                Generate Report
                            </button>
                        </div>
                        
                        <div>
                            <a href="{{ route('admin.reports.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-slate-700 font-bold py-2 px-6 rounded-md shadow transition duration-150 ease-in-out">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Present Count -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
                    <div class="bg-emerald-50 p-4 rounded-xl text-emerald-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Present</p>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $presentCount }}</p>
                    </div>
                </div>

                <!-- Absent Count -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
                    <div class="bg-red-50 p-4 rounded-xl text-red-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Absent</p>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $absentCount }}</p>
                    </div>
                </div>

                <!-- Leave Count -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
                    <div class="bg-amber-50 p-4 rounded-xl text-amber-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Approved Leave</p>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $leaveDaysCount }} <span class="text-sm font-semibold text-slate-400">Days</span></p>
                    </div>
                </div>
            </div>

            <!-- Report Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-brand/20">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-brand-dark mb-4">Report Results ({{ $attendances->count() }} records found)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-brand-light/30">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Check In</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Check Out</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($attendances as $record)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-800">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-800 font-medium">{{ $record->user->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($record->status === 'present')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-brand-light text-brand-dark border border-brand/20">Present</span>
                                            @elseif($record->status === 'absent')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Absent</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">{{ ucfirst($record->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">{{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('h:i A') : '--' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">{{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : '--' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-4 text-center text-sm text-slate-500">No attendance records found for the selected criteria.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
