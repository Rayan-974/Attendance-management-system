<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Header -->
            <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center space-x-4">
                    <div class="bg-indigo-50 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Attendance Dashboard</h2>
                        <p class="text-sm text-slate-500 font-medium">Track your daily attendance and work hours</p>
                    </div>
                </div>
                <div>
                    <!-- Tag removed per user request -->
                </div>
            </div>

            <!-- Top Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Time Card -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col justify-center">
                    <p class="text-sm font-semibold text-slate-500 mb-2">Current Time</p>
                    <div class="flex items-baseline space-x-2">
                        <span id="clock-time" class="text-5xl font-extrabold text-slate-800 tracking-tight">--:--:--</span>
                        <span id="clock-ampm" class="text-2xl font-bold text-slate-400">--</span>
                    </div>
                    <p id="clock-date" class="text-indigo-600 font-semibold mt-4 text-sm"></p>
                </div>

                <!-- Action Card -->
                <div class="bg-amber-50 rounded-3xl p-8 shadow-sm border border-amber-100/50 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-amber-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="font-bold text-slate-800">Working hours start at 9:00 AM</span>
                    </div>
                    
                    @if(!$todayAttendance)
                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <p class="text-sm font-semibold text-amber-700">Not Checked In</p>
                                <p class="text-xl font-bold text-slate-800 mt-1">Ready to work?</p>
                            </div>
                            <form action="{{ route('student.attendance.mark') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-0.5">
                                    Check In
                                </button>
                            </form>
                        </div>
                    @elseif($todayAttendance && !$todayAttendance->check_out)
                        @php
                            $checkInTime = \Carbon\Carbon::parse($todayAttendance->check_in);
                            $worked = $checkInTime->diff(\Carbon\Carbon::now());
                        @endphp
                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <p class="text-sm font-semibold text-amber-700">Working Hours</p>
                                <p class="text-2xl font-bold text-amber-700 mt-1">{{ $worked->h }}h {{ $worked->i }}m</p>
                            </div>
                            <form action="{{ route('student.attendance.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-red-200 transition-all transform hover:-translate-y-0.5 flex items-center">
                                    <div class="w-2 h-2 bg-white rounded-sm mr-2"></div> Check Out
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center mt-4 text-xs font-semibold text-slate-500">
                            <div class="w-2 h-2 bg-slate-400 rounded-full mr-2"></div>
                            Checked in at {{ $checkInTime->format('h:i A') }}
                        </div>
                    @else
                        @php
                            $checkInTime = \Carbon\Carbon::parse($todayAttendance->check_in);
                            $checkOutTime = \Carbon\Carbon::parse($todayAttendance->check_out);
                            $worked = $checkInTime->diff($checkOutTime);
                        @endphp
                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <p class="text-sm font-semibold text-emerald-700">Total Hours</p>
                                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $worked->h }}h {{ $worked->i }}m</p>
                            </div>
                            <div class="px-4 py-2 bg-slate-200 text-slate-600 font-bold rounded-xl text-sm">
                                Shift Completed
                            </div>
                        </div>
                        <div class="flex items-center mt-4 text-xs font-semibold text-slate-500">
                            <div class="w-2 h-2 bg-slate-400 rounded-full mr-2"></div>
                            Checked out at {{ $checkOutTime->format('h:i A') }}
                        </div>
                    @endif
                </div>

                <!-- Status Card -->
                <div class="{{ $todayAttendance ? 'bg-emerald-50 border-emerald-100/50' : 'bg-slate-50 border-slate-200' }} rounded-3xl p-8 shadow-sm border flex flex-col justify-center relative overflow-hidden">
                    @if($todayAttendance)
                        <div class="bg-emerald-400/20 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                            <div class="bg-emerald-500 w-8 h-8 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-emerald-700">Today's Status</p>
                        <p class="text-3xl font-extrabold text-emerald-600 mt-1">Present</p>
                        <p class="text-sm text-emerald-600 font-medium mt-4">Have a productive day!</p>
                    @else
                        <div class="bg-slate-200 w-12 h-12 rounded-full flex items-center justify-center mb-4 text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-500">Today's Status</p>
                        <p class="text-3xl font-extrabold text-slate-700 mt-1">Pending</p>
                        <p class="text-sm text-slate-500 font-medium mt-4">Awaiting check-in</p>
                    @endif
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Total Days -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <div class="bg-indigo-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 text-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-3xl font-extrabold text-indigo-600">{{ $totalDays }}</p>
                    <p class="text-sm font-bold text-slate-600 mt-1">Total Days</p>
                    <p class="text-xs text-slate-400 font-medium">This Month</p>
                </div>
                <!-- Present -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <div class="bg-emerald-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-3xl font-extrabold text-emerald-500">{{ $presentDays }}</p>
                    <p class="text-sm font-bold text-slate-600 mt-1">Present</p>
                    <p class="text-xs text-slate-400 font-medium">This Month</p>
                </div>
                <!-- Absent -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <div class="bg-red-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-3xl font-extrabold text-red-500">{{ $absentDays }}</p>
                    <p class="text-sm font-bold text-slate-600 mt-1">Absent</p>
                    <p class="text-xs text-slate-400 font-medium">This Month</p>
                </div>
                <!-- Attendance Rate -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                    <div class="bg-purple-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 text-purple-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <p class="text-3xl font-extrabold text-purple-600">{{ $attendanceRate }}%</p>
                    <p class="text-sm font-bold text-slate-600 mt-1">Attendance Rate</p>
                    <p class="text-xs text-slate-400 font-medium">This Month</p>
                </div>
            </div>

            <!-- Calendar and Timeline skeleton -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Calendar -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 min-h-[300px]">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-50 p-2 rounded-lg text-indigo-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">Attendance Calendar</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="p-1 border border-slate-200 rounded-md text-slate-400 hover:bg-slate-50"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                            <span class="font-bold text-slate-800">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                            <button class="p-1 border border-slate-200 rounded-md text-slate-400 hover:bg-slate-50"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                            <button class="ml-2 px-3 py-1 bg-indigo-50 text-indigo-600 text-sm font-semibold rounded-md border border-indigo-100 hover:bg-indigo-100">Today</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-slate-400 mb-4">
                        <div>SUN</div><div>MON</div><div>TUE</div><div>WED</div><div>THU</div><div>FRI</div><div>SAT</div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center text-sm font-bold text-slate-700">
                        @php
                            $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
                            $firstDayOfWeek = \Carbon\Carbon::now()->startOfMonth()->dayOfWeek;
                        @endphp
                        @for($i = 0; $i < $firstDayOfWeek; $i++)
                            <div class="p-2"></div>
                        @endfor
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $dateStr = \Carbon\Carbon::now()->startOfMonth()->addDays($day - 1)->format('Y-m-d');
                                $isToday = $dateStr == \Carbon\Carbon::today()->format('Y-m-d');
                                $hasAttendance = clone $attendances;
                                $hasAttendance = $hasAttendance->where('date', \Carbon\Carbon::parse($dateStr)->startOfDay())->isNotEmpty();
                            @endphp
                            <div class="p-2 rounded-full w-8 h-8 mx-auto flex items-center justify-center 
                                {{ $isToday ? 'bg-indigo-600 text-white shadow-md' : ($hasAttendance ? 'bg-emerald-100 text-emerald-700' : 'hover:bg-slate-100 cursor-pointer') }}">
                                {{ $day }}
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 min-h-[300px]">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="bg-indigo-50 p-2 rounded-lg text-indigo-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg">Attendance Timeline</h3>
                    </div>
                    
                    <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
                        @forelse($attendances->take(3) as $record)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-emerald-500 text-emerald-50 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl border border-slate-200 shadow-sm bg-white hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between space-x-2 mb-2">
                                    <div class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($record->date)->format('l, M j, Y') }}</div>
                                    <time class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md">Present</time>
                                </div>
                                <div class="text-slate-500 text-sm flex flex-col space-y-1 mt-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-slate-700">Check In:</span>
                                        <span class="text-indigo-600 font-bold">{{ \Carbon\Carbon::parse($record->check_in)->format('h:i A') }}</span>
                                    </div>
                                    @if($record->check_out)
                                    <div class="flex items-center justify-between border-t border-slate-200 pt-1 mt-1">
                                        <span class="font-semibold text-slate-700">Check Out:</span>
                                        <span class="text-red-500 font-bold">{{ \Carbon\Carbon::parse($record->check_out)->format('h:i A') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-slate-500 text-center w-full relative z-10 bg-white p-2">No timeline data available.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Restored Functionality: Tasks and Leaves -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Submit Leave Form -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <div class="bg-indigo-50 p-2 rounded-lg text-indigo-500 mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        Request Leave
                    </h3>
                    <form action="{{ route('student.leave.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" required class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">End Date</label>
                                <input type="date" name="end_date" required class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Reason</label>
                            <textarea name="reason" rows="2" required class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all mt-4">
                            Submit Request
                        </button>
                    </form>
                </div>

                <!-- Recent Leaves Table -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <div class="bg-indigo-50 p-2 rounded-lg text-indigo-500 mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Recent Leaves
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Dates</th>
                                    <th class="px-2 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($leaves as $leave)
                                    <tr>
                                        <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-slate-700">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            @if($leave->status === 'approved')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-emerald-100 text-emerald-700">Approved</span>
                                            @elseif($leave->status === 'rejected')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-red-100 text-red-700">Rejected</span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg bg-amber-100 text-amber-700">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-2 py-4 text-sm text-slate-500 italic">No leaves requested.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Assigned Tasks Section -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                    <div class="bg-indigo-50 p-2 rounded-lg text-indigo-500 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    Assigned Tasks
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($tasks as $task)
                        <div class="border border-slate-100 rounded-2xl p-6 bg-slate-50/50">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="text-md font-bold text-slate-800">{{ $task->title }}</h4>
                                <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $task->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>
                            <div class="text-sm text-slate-600 mb-4 prose max-w-none">
                                {!! $task->description !!}
                            </div>
                            <div class="flex items-center text-xs font-bold text-slate-500 mb-5">
                                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                            </div>
                            
                            @if($task->status !== 'completed')
                                <div class="bg-white p-5 rounded-xl border border-slate-200">
                                    <form action="{{ route('student.task.response', $task) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Your Response</label>
                                        <textarea name="student_response" rows="3" required class="block w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mb-4"></textarea>
                                        
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Upload File (Optional)</label>
                                        <input type="file" name="submission_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 mb-4 transition-colors">
                                        
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-xl shadow-sm transition-all text-sm w-full">
                                            Submit Task
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-white p-5 rounded-xl border border-slate-200">
                                    <span class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Your Submitted Response</span>
                                    <p class="text-sm font-medium text-slate-700 mb-3">{{ $task->student_response }}</p>
                                    @if($task->submission_file_path)
                                        <div class="pt-3 border-t border-slate-100">
                                            <a href="{{ asset('storage/' . $task->submission_file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-sm font-bold inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                View Attached File
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 py-8 text-center text-slate-500 italic">No tasks currently assigned.</div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>

    <!-- Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let seconds = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12; 
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            
            const timeEl = document.getElementById('clock-time');
            if (timeEl) {
                timeEl.textContent = hours + ':' + minutes + ':' + seconds;
                document.getElementById('clock-ampm').textContent = ampm;
                
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('clock-date').textContent = now.toLocaleDateString('en-US', options);
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</x-app-layout>
