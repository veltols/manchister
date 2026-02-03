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

    <!-- Filter Bar -->
    <div class="premium-card p-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex gap-2">
                <a href="{{ route('emp.tasks.index', ['view_mode' => 'my_tasks']) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $viewMode == 'my_tasks' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    Assigned to Me
                </a>
                <a href="{{ route('emp.tasks.index', ['view_mode' => 'others_tasks']) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $viewMode == 'others_tasks' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    Assigned by Me
                </a>
            </div>

            <form action="{{ route('emp.tasks.index') }}" method="GET" class="flex items-center gap-3">
                <input type="hidden" name="view_mode" value="{{ $viewMode }}">
                <select name="status_id" onchange="this.form.submit()" class="premium-input py-2 pl-4 pr-10 text-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->status_id }}" {{ $statusId == $status->status_id ? 'selected' : '' }}>
                            {{ $status->status_name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="premium-card overflow-hidden" x-data="{ 
        activeTaskId: null,
        activeTaskProgress: 0,
        openStatusModal(id, sttId) {
            this.activeTaskId = id;
            document.getElementById('edit_status_task_id').value = id;
            document.getElementById('edit_status_id').value = sttId;
            openModal('updateStatusModal');
        },
        openProgressModal(id, prog) {
            this.activeTaskId = id;
            this.activeTaskProgress = prog;
            document.getElementById('edit_progress_task_id').value = id;
            // Alpine binding will handle the rest
            openModal('updateProgressModal');
        }
    }">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-left">Task</th>
                        <th class="text-left">Duration & Timer</th>
                        @if($viewMode == 'my_tasks')
                            <th class="text-left">Assigned By</th>
                        @else
                            <th class="text-left">Assigned To</th>
                        @endif
                        <th class="text-center">Status</th>
                        <th class="text-left">Completion</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td class="text-center">
                            <div class="w-2 h-8 rounded-full mx-auto" 
                                 style="background: #{{ $task->priority->priority_color ?? 'ccc' }};"
                                 title="Priority: {{ $task->priority->priority_name ?? 'Normal' }}">
                            </div>
                        </td>
                        <td>
                            <div class="max-w-xs">
                                <div class="font-bold text-slate-800 truncate" title="{{ $task->task_title }}">{{ $task->task_title }}</div>
                                <div class="text-xs text-slate-400 mt-1">Ref: #{{ $task->task_id }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                    <span class="px-2 py-0.5 rounded bg-slate-100">{{ $task->getCountedTime() }}</span>
                                </div>
                                @php
                                    $timeProg = $task->getTimeProgress();
                                    $timerBg = $timeProg >= 90 ? 'bg-rose-500' : ($timeProg >= 60 ? 'bg-amber-500' : 'bg-emerald-500');
                                @endphp
                                <div class="w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $timerBg }} rounded-full" style="width: {{ $timeProg }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php 
                                $person = ($viewMode == 'my_tasks') ? $task->assignedBy : $task->assignedTo;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    {{ substr($person->first_name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-slate-600">{{ $person->first_name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-white text-[10px] font-black uppercase tracking-wider shadow-sm" 
                                  style="background: #{{ $task->status->status_color ?? '999' }};">
                                {{ $task->status->status_name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full transition-all duration-300" style="width: {{ $task->task_progress }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600">{{ $task->task_progress }}%</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('emp.tasks.show', $task->task_id) }}" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="View Details">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <button @click="openStatusModal({{ $task->task_id }}, {{ $task->status_id }})" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="Update Status">
                                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                </button>
                                <button @click="openProgressModal({{ $task->task_id }}, {{ $task->task_progress }})" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Update Progress">
                                    <i class="fa-solid fa-chart-line text-xs"></i>
                                </button>
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
            {{ $tasks->appends(['view_mode' => $viewMode, 'status_id' => $statusId])->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Create Task Modal -->
<div class="modal" id="addTaskModal">
    <div class="modal-backdrop" onclick="closeModal('addTaskModal')"></div>
    <div class="modal-content max-w-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">Create New Task</h2>
            <button onclick="closeModal('addTaskModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('emp.tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Assign To</label>
                    <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                        <option value="{{ auth()->user()->employee->employee_id }}">Me (Self)</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Priority</label>
                    <select name="priority_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        @foreach($priorities as $p)
                            <option value="{{ $p->priority_id }}">{{ $p->priority_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Task Title</label>
                <input type="text" name="task_title" class="premium-input w-full px-4 py-3 text-sm" required placeholder="What needs to be done?">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="task_description" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Additional details..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Start Schedule</label>
                    <input type="date" name="task_assigned_date" class="premium-input w-full text-sm" value="{{ date('Y-m-d') }}" required>
                    <select name="start_time" class="premium-input w-full text-sm">
                        <option value="">Start Time (Optional)</option>
                        @for($i=6; $i<=22; $i++)
                            @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $h }}:00:00">{{ $h }}:00</option>
                        @endfor
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Due Deadline</label>
                    <input type="date" name="task_due_date" class="premium-input w-full text-sm" required>
                    <select name="end_time" class="premium-input w-full text-sm">
                        <option value="">End Time (Optional)</option>
                        @for($i=6; $i<=22; $i++)
                            @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $h }}:00:00" {{ $i == 14 ? 'selected' : '' }}>{{ $h }}:00</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addTaskModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Create Task</button>
            </div>
        </form>
    </div>
</div>


<!-- Update Status Modal -->
<div id="updateStatusModal" class="modal">
    <div class="modal-backdrop" onclick="closeModal('updateStatusModal')"></div>
    <div class="modal-content w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-display font-bold text-premium">Update Task Status</h2>
            <button onclick="closeModal('updateStatusModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <form x-bind:action="'{{ url('emp/tasks') }}/' + document.getElementById('edit_status_task_id').value + '/status'" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" id="edit_status_task_id">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">New Status</label>
                <select name="status_id" id="edit_status_id" class="premium-input w-full" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Remark / Note</label>
                <textarea name="log_remark" rows="3" class="premium-input w-full" placeholder="Enter reason for update..." required></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="closeModal('updateStatusModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-dark text-white font-semibold hover:bg-brand-light shadow-lg hover:shadow-xl transition-all">Update Status</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Progress Modal -->
<div id="updateProgressModal" class="modal">
    <div class="modal-backdrop" onclick="closeModal('updateProgressModal')"></div>
    <div class="modal-content w-full max-w-lg p-6 text-center">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-display font-bold text-premium">Update Task Progress</h2>
            <button onclick="closeModal('updateProgressModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <form x-bind:action="'{{ url('emp/tasks') }}/' + document.getElementById('edit_progress_task_id').value + '/status'" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" id="edit_progress_task_id">
            <div x-data="{ localProgress: 0 }" x-init="$watch('$parent.activeTaskProgress', value => localProgress = value)">
                <label class="block text-sm font-semibold text-slate-700 mb-2">New Progress: <span class="text-brand-dark font-bold text-lg" x-text="localProgress + '%'"></span></label>
                <input type="range" name="task_progress" min="0" max="100" x-model="localProgress" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-dark">
                <div class="flex justify-between text-xs text-slate-400 mt-2"><span>0%</span><span>100%</span></div>
            </div>
            <div class="text-left">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Remark / Note</label>
                <textarea name="log_remark" rows="3" class="premium-input w-full" placeholder="What was achieved?" required></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="closeModal('updateProgressModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 shadow-lg hover:shadow-xl transition-all">Update Progress</button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
