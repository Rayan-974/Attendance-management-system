<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Assign New Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-brand/20">
                <div class="p-6">
                    <form action="{{ route('admin.task.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Task Title</label>
                            <input type="text" name="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <!-- Assign To -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Assign To Student</label>
                                <select name="assigned_to" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                                    <option value="" disabled selected>Select a student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Due Date -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Due Date</label>
                                <input type="date" name="due_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand">
                            </div>
                        </div>

                        <!-- CKEditor Rich Text Description -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Detailed Description</label>
                            <textarea name="description" id="editor" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-brand focus:ring-brand"></textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.dashboard') }}" class="mr-4 text-slate-500 hover:text-slate-800">Cancel</a>
                            <button type="submit" class="bg-brand hover:bg-brand-dark text-white font-bold py-2 px-6 rounded-md shadow transition duration-150 ease-in-out">
                                Assign Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
</x-app-layout>
