@extends('layouts.app')

@section('title', 'Tasks Management')
@section('subtitle', $viewMode == 'my_tasks' ? 'Tasks assigned to you' : 'Tasks you assigned to others')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">{{ $viewMode == 'my_tasks' ? 'My Tasks' : 'Others Tasks' }}</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $tasks->total() }} total tasks found</p>
        </div>
        <button onclick="openModal('addTaskModal')" class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Create Task</span>
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="premium-card p-2">
        <div class="flex gap-2">
            <a href="{{ route('emp.tasks.index', ['view_mode' => 'my_tasks']) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $viewMode == 'my_tasks' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                Assigned to Me
            </a>
            <a href="{{ route('emp.tasks.index', ['view_mode' => 'others_tasks']) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $viewMode == 'others_tasks' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                Assigned by Me
            </a>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-center">Priority</th>
                        <th class="text-left">Task</th>
                        @if($viewMode == 'my_tasks')
                            <th class="text-left">Assigned By</th>
                        @else
                            <th class="text-left">Assigned To</th>
                        @endif
                        <th class="text-left">Due Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Progress</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td class="text-center">
                            <div class="w-3 h-3 rounded-full mx-auto shadow-md" 
                                 style="background: #{{ $task->priority->priority_color ?? 'ccc' }};"
                                 title="{{ $task->priority->priority_name ?? 'Normal' }}">
                            </div>
                        </td>
                        <td>
                            <div class="max-w-xs">
                                <div class="font-semibold text-slate-800 truncate" title="{{ $task->task_title }}">{{ $task->task_title }}</div>
                                <div class="text-xs text-slate-500 truncate mt-1">{{ Str::limit($task->task_description, 40) }}</div>
                            </div>
                        </td>
                        <td>
                            @php 
                                $person = ($viewMode == 'my_tasks') ? $task->assignedBy : $task->assignedTo;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white text-xs font-semibold shadow-md">
                                    {{ substr($person->first_name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-sm text-slate-600 truncate">{{ $person->first_name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm font-mono text-slate-600">
                                {{ $task->task_due_date ? $task->task_due_date->format('M d, Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" 
                                  style="background: linear-gradient(135deg, #{{ $task->status->status_color ?? '999' }}, #{{ $task->status->status_color ?? '999' }}dd);">
                                {{ $task->status->status_name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2 min-w-[100px]">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    @php
                                        $progressBg = 'bg-brand-dark';
                                        if($task->task_progress <= 10) $progressBg = 'bg-red-500';
                                        elseif($task->task_progress <= 60) $progressBg = 'bg-amber-500';
                                        else $progressBg = 'bg-green-500';
                                    @endphp
                                    <div class="h-full {{ $progressBg }} rounded-full transition-all duration-300" style="width: {{ $task->task_progress }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600 w-8">{{ $task->task_progress }}%</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('emp.tasks.show', $task->task_id) }}" class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md" title="Details">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-list-check text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No tasks found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 flex justify-center">
            {{ $tasks->appends(['view_mode' => $viewMode])->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Create Task Modal -->
<div class="modal" id="addTaskModal">
    <div class="modal-backdrop" onclick="closeModal('addTaskModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">Create New Task</h2>
            <button onclick="closeModal('addTaskModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('emp.tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa-solid fa-heading text-indigo-600 mr-2"></i>Task Title
                </label>
                <input type="text" name="task_title" class="premium-input w-full px-4 py-3 text-sm" required placeholder="What needs to be done?">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-flag text-indigo-600 mr-2"></i>Priority
                    </label>
                    <select name="priority_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        @foreach($priorities as $p)
                            <option value="{{ $p->priority_id }}">{{ $p->priority_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Due Date
                    </label>
                    <input type="date" name="task_due_date" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa-solid fa-align-left text-indigo-600 mr-2"></i>Description
                </label>
                <textarea name="task_description" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Additional details..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Assign To
                </label>
                <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                    <option value="{{ auth()->user()->employee->employee_id }}">Me (Self)</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addTaskModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Create Task</button>
            </div>
        </form>
    </div>
</div>

@endsection
