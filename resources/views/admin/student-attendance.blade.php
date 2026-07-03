<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Manage Attendance: ') }} <span class="text-brand">{{ $student->name }}</span>
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:text-brand-dark transition">&larr; Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Add/Override Attendance Form -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-brand/20">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-brand-dark mb-4">Add / Override Record</h3>
                            
                            <form action="{{ route('admin.student.attendance.store', $student) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Date</label>
                                    <input type="date" name="date" required value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">Status</label>
                                    <select name="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                        <option value="half_day">Half Day</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Check In</label>
                                        <input type="time" name="check_in" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Check Out</label>
                                        <input type="time" name="check_out" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 bg-gray-50 p-2 rounded border border-gray-200">Note: Saving a record on an existing date will seamlessly overwrite the old record.</p>
                                <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white font-bold py-2 px-4 rounded-md shadow transition duration-150 ease-in-out">
                                    Save Record
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Attendance History Table -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-brand/20">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-brand-dark mb-4">Attendance History</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-brand-light/30">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Check In/Out</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($attendances as $record)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-800 font-medium">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if($record->status === 'present')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-brand-light text-brand-dark border border-brand/20">Present</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">{{ ucfirst($record->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">
                                                    {{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('h:i A') : '--' }} / 
                                                    {{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : '--' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                                    <form action="{{ route('admin.attendance.delete', $record) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-4 py-4 text-center text-sm text-slate-500">No attendance records found for this student.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
