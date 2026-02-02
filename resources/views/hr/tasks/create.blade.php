@extends('layouts.app')

@section('title', 'New Task')
@section('subtitle', 'Assign a new task')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('hr.tasks.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
        </div>

        <div class="premium-card p-10">
            <h2 class="text-2xl font-display font-bold text-premium mb-6 font-display">Task Form</h2>

            <form action="{{ route('hr.tasks.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Task
                            Title</label>
                        <input type="text" name="task_title" class="premium-input w-full"
                            placeholder="e.g. Prepare Monthly Report" required>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="task_description" rows="4" class="premium-input w-full"
                            placeholder="Task details and instructions..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Assigned
                            To</label>
                        <div class="relative">
                            <select name="assigned_to" class="premium-input w-full appearance-none" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Priority</label>
                        <div class="relative">
                            <select name="priority_id" class="premium-input w-full appearance-none" required>
                                <option value="">Select Priority</option>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority->priority_id }}">{{ $priority->priority_name }}</option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Start
                            Date</label>
                        <input type="date" name="task_start_date" class="premium-input w-full" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Due
                            Date</label>
                        <input type="date" name="task_due_date" class="premium-input w-full" required>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="premium-button w-full py-3">
                        <i class="fa-solid fa-check mr-2"></i> Assign Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
