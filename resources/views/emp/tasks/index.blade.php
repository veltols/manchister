@extends('layouts.app')

@section('title', 'My Tasks')
@section('subtitle', 'Task management and tracking')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">My Tasks</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $tasks->total() }} total tasks</p>
        </div>
        <button onclick="openModal('addTaskModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Create Task</span>
        </button>
    </div>

    <!-- Tasks Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-center">Priority</th>
                        <th class="text-left">Task</th>
                        <th class="text-left">Assigned By</th>
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
                            <div>
                                <div class="font-semibold text-slate-800">{{ $task->task_title }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-xs mt-1">{{ Str::limit($task->task_description, 50) }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white text-xs font-semibold shadow-md">
                                    {{ substr($task->assignedBy->first_name ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-sm text-slate-600">{{ $task->assignedBy->first_name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-mono text-slate-600">
                                    {{ $task->task_due_date ? $task->task_due_date->format('M d, Y') : '-' }}
                                </span>
                                @if($task->task_due_date && $task->task_due_date->isPast() && $task->status_id != 100)
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-500 text-white text-xs font-bold">!</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" 
                                  style="background: linear-gradient(135deg, #{{ $task->status->status_color ?? '999' }}, #{{ $task->status->status_color ?? '999' }}dd);">
                                {{ $task->status->status_name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $percentage = 0;
                                if($task->task_assigned_date && $task->task_due_date){
                                    $totalDays = $task->task_assigned_date->diffInDays($task->task_due_date);
                                    $daysPassed = $task->task_assigned_date->diffInDays(now());
                                    $percentage = $totalDays > 0 ? min(100, ($daysPassed / $totalDays) * 100) : 0;
                                }
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600 w-10">{{ round($percentage) }}%</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center">
                                <form action="{{ route('emp.tasks.status', $task->task_id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status_id" onchange="this.form.submit()" class="premium-input text-xs px-3 py-2">
                                        <option value="">Move to...</option>
                                        @foreach($statuses as $st)
                                            <option value="{{ $st->status_id }}">{{ $st->status_name }}</option>
                                        @endforeach
                                    </select>
                                </form>
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
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

<!-- Create Task Modal -->
<div class="modal" id="addTaskModal">
    <div class="modal-backdrop" onclick="closeModal('addTaskModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-slate-800">Create New Task</h2>
            <button onclick="closeModal('addTaskModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('emp.tasks.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-heading text-indigo-600 mr-2"></i>Task Title
                    </label>
                    <input type="text" name="task_title" class="premium-input w-full px-4 py-3 text-sm" required>
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
                    <textarea name="task_description" rows="3" class="premium-input w-full px-4 py-3 text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Assign To (Optional)
                    </label>
                    <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                        <option value="{{ Auth::id() }}">Me (Self)</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addTaskModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Create Task</button>
            </div>
        </form>
    </div>
</div>

@endsection
