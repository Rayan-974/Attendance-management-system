<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight text-slate-800">
                {{ __('Roles & Permissions Management') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:text-brand-dark transition">&larr; Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-100 flex items-center space-x-3 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 flex items-center space-x-3 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Create Role Section -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-3 bg-indigo-50 text-indigo-500 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Create New Role</h3>
                </div>

                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Role Name</label>
                            <input type="text" name="name" class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" required placeholder="e.g., Assistant">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Assign Permissions</label>
                            <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-4 border border-slate-100 rounded-xl bg-slate-50">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center space-x-2 text-sm text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                        <span>{{ ucwords(str_replace('_', ' ', $permission->name)) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-xl transition shadow-sm">
                            Save Role
                        </button>
                    </div>
                </form>
            </div>

            <!-- Roles List -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-3 bg-emerald-50 text-emerald-500 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Existing Roles</h3>
                </div>

                <div class="space-y-6">
                    @foreach($roles as $role)
                        <div class="border border-slate-100 rounded-2xl p-6 bg-slate-50/50">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-lg font-bold text-slate-800 capitalize">{{ $role->name }}</h4>
                                    <p class="text-sm text-slate-500">{{ $role->permissions->count() }} permissions assigned</p>
                                </div>
                                @if(!in_array($role->name, ['admin', 'student', 'teacher', 'hr']))
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg text-sm font-medium transition">
                                            Delete Role
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $role->name }}">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center space-x-2 text-sm text-slate-700 bg-white p-2 rounded-lg border border-slate-100 cursor-pointer hover:bg-slate-50 transition">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300"
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <span>{{ ucwords(str_replace('_', ' ', $permission->name)) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-medium py-1.5 px-4 rounded-lg text-sm transition">
                                        Update Permissions
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
