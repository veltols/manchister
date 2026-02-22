@extends('layouts.app')

@section('title', 'Tasks Management')
@section('subtitle', 'Track assignments, deadlines, and progress.')

@section('content')
    <div class="tasks-layout">
        <!-- Sidebar: Tasks List -->
        <div class="tasks-sidebar">
            <div class="sidebar-header">
                <div>
                    <h2 class="text-xl font-bold text-premium">Tasks Management</h2>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'assigned_by']) }}" 
                           class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded {{ $viewMode == 'assigned_by' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-400 hover:text-indigo-600' }}">
                            Assigned by Me
                        </a>
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'assigned_to']) }}" 
                           class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded {{ $viewMode == 'assigned_to' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-400 hover:text-indigo-600' }}">
                            Assigned to Me
                        </a>
                        @if(isset($submittedCount) && $submittedCount > 0 || $viewMode === 'submitted')
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'submitted']) }}" 
                           class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded flex items-center gap-1 {{ $viewMode == 'submitted' ? 'bg-orange-100 text-orange-700' : 'text-slate-400 hover:text-orange-600' }}">
                             Submitted
                             @if(isset($submittedCount) && $submittedCount > 0)
                             <span class="bg-orange-400 text-white rounded-full w-4 h-4 flex items-center justify-center" style="font-size:9px;">{{ $submittedCount }}</span>
                             @endif
                        </a>
                        @endif
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'rejected']) }}"
                           class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded flex items-center gap-1 {{ $viewMode == 'rejected' ? 'bg-red-100 text-red-700' : 'text-slate-400 hover:text-red-600' }}">
                             Rejected
                             @if(isset($rejectedCount) && $rejectedCount > 0)
                             <span class="bg-red-400 text-white rounded-full w-4 h-4 flex items-center justify-center" style="font-size:9px;">{{ $rejectedCount }}</span>
                             @endif
                        </a>
                        @if($isLineManager)
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'rejected_by_me']) }}"
                           class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded flex items-center gap-1 {{ $viewMode == 'rejected_by_me' ? 'bg-red-100 text-red-700' : 'text-slate-400 hover:text-red-600' }}">
                             Rejected by Me
                             @if(isset($rejectedByMeCount) && $rejectedByMeCount > 0)
                             <span class="bg-red-400 text-white rounded-full w-4 h-4 flex items-center justify-center" style="font-size:9px;">{{ $rejectedByMeCount }}</span>
                             @endif
                        </a>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="openCreateTaskModal()"
                        class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('hr.tasks.index') }}" method="GET">
                    <input type="hidden" name="view_mode" value="{{ $viewMode }}">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="status_id" onchange="this.form.submit()" class="premium-input w-full pl-11 pr-4 py-2.5 text-sm bg-white cursor-pointer">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->status_id }}" {{ $statusId == $status->status_id ? 'selected' : '' }}>
                                    {{ $status->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div id="tasks-container-wrapper" class="flex-1"
                style="overflow-y: auto !important; height: 100% !important; padding: 1rem; padding-right: 10px !important;">
                <div id="tasks-container" class="space-y-3">
                    @forelse($tasks as $task)
                        <div onclick="loadTask({{ $task->task_id }})" id="task-item-{{ $task->task_id }}"
                            class="task-card p-4 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer hover:shadow-md hover:border-indigo-200 transition-all group relative overflow-hidden">

                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                    style="background: #{{ $task->priority->priority_color }}20; color: #{{ $task->priority->priority_color }}">
                                    {{ $task->priority->priority_name }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">#{{ $task->task_id }}</span>
                            </div>

                            <h3
                                class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors mb-1 line-clamp-2">
                                {{ $task->task_title }}
                            </h3>

                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center gap-2">
                                    @php 
                                        $person = ($viewMode == 'assigned_to') ? $task->assignedBy : $task->assignedTo;
                                    @endphp
                                    <div
                                        class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                        {{ substr($person->first_name ?? 'S', 0, 1) }}
                                    </div>
                                    <span
                                        class="text-xs text-slate-500 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[80px]">
                                        {{ $person->first_name ?? 'Unknown' }}
                                    </span>
                                </div>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold"
                                    style="background: #{{ $task->status->status_color }}20; color: #{{ $task->status->status_color }}">
                                    {{ $task->status->status_name }}
                                </span>
                            </div>

                            @if($viewMode === 'rejected')
                            <div class="mt-2 p-2 bg-red-50 rounded-lg border border-red-100">
                                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-0.5">Rejection Reason</p>
                                <p class="text-xs text-red-700">{{ $task->rejection_reason }}</p>
                            </div>
                            <button onclick="event.stopPropagation(); openResubmitModal({{ $task->task_id }}, '{{ addslashes($task->task_title) }}', '{{ addslashes($task->task_description ?? '') }}')"
                                class="mt-2 w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fa-solid fa-rotate-right"></i> Edit & Resubmit
                            </button>
                            @elseif($viewMode === 'rejected_by_me')
                            <div class="mt-2 p-2 bg-red-50 rounded-lg border border-red-100">
                                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-0.5">You Rejected:</p>
                                <p class="text-xs text-red-700">{{ $task->rejection_reason }}</p>
                                <p class="text-[10px] text-slate-400 mt-1">Submitted by: {{ $task->assignedBy->first_name ?? '—' }}</p>
                            </div>
                            @endif
                            <div
                                class="active-indicator w-1 h-full absolute left-0 top-0 bg-indigo-600 opacity-0 transition-opacity">
                            </div>
                        </div>

                        <!-- Subtasks Loop -->
                        @if($task->subtasks && $task->subtasks->count() > 0)
                            @foreach($task->subtasks as $sub)
                                <div onclick="loadTask({{ $sub->task_id }})" id="task-item-{{ $sub->task_id }}"
                                    class="task-card subtask-card ml-6 p-3 rounded-xl bg-slate-50 border border-slate-100 shadow-sm cursor-pointer hover:shadow-md hover:border-indigo-200 transition-all group relative overflow-hidden mb-2">
                                    <div class="absolute -left-3 top-1/2 w-3 h-[2px] bg-slate-200"></div>
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="flex items-center gap-1">
                                            <i class="fa-solid fa-turn-up rotate-90 text-[10px] text-slate-300"></i>
                                            <span
                                                class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-white border border-slate-200 text-slate-400">Sub</span>
                                        </div>
                                        <span class="text-[9px] text-slate-400 font-mono">#{{ $sub->task_id }}</span>
                                    </div>
                                    <h3
                                        class="font-bold text-slate-700 text-xs group-hover:text-indigo-600 transition-colors mb-1 line-clamp-1">
                                        {{ $sub->task_title }}
                                    </h3>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center gap-2">
                                            @php 
                                                $subPerson = ($viewMode == 'assigned_to') ? $sub->assignedBy : $sub->assignedTo;
                                            @endphp
                                            <div
                                                class="w-5 h-5 rounded-full bg-white flex items-center justify-center text-[9px] font-bold text-slate-400 shadow-sm">
                                                {{ substr($subPerson->first_name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[60px]">
                                                {{ $subPerson->first_name ?? 'Unknown' }}
                                            </span>
                                        </div>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold"
                                            style="background: #{{ $sub->status->status_color }}20; color: #{{ $sub->status->status_color }}">
                                            {{ $sub->status->status_name }}
                                        </span>
                                    </div>
                                    <div
                                        class="active-indicator w-1 h-full absolute left-0 top-0 bg-indigo-600 opacity-0 transition-opacity">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @empty
                        <div class="text-center py-10">
                            <div
                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="fa-solid fa-clipboard-check text-2xl"></i>
                            </div>
                            <p class="text-slate-400 text-sm">No tasks found</p>
                        </div>
                    @endforelse
                </div>

                <!-- AJAX Pagination -->
                <div id="tasks-pagination" class="mt-4"></div>
            </div>
        </div>

        <!-- Main Content: Task Details -->
        <div class="tasks-main">
            <div id="selection-placeholder"
                class="h-full flex flex-col items-center justify-center p-12 text-center animate-fade-in">
                <div
                    class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mb-8 text-indigo-500 shadow-inner">
                    <i class="fa-solid fa-list-check text-5xl"></i>
                </div>
                <h2 class="text-2xl font-display font-bold text-premium mb-4">Select a Task</h2>
                <p class="text-slate-500 max-w-sm">Choose a task from the sidebar to view its details, progress, and
                    activity logs.</p>
            </div>

            <div id="task-content" class="hidden h-full flex flex-col animate-fade-in relative">
                <!-- Header -->
                <div class="p-8 border-b border-slate-100 bg-white">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <span id="detail-id" class="font-mono text-slate-400 text-sm"></span>
                                <span id="detail-priority"
                                    class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"></span>
                                <span id="detail-status"
                                    class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"></span>
                            </div>
                            <h1 id="detail-title" class="text-3xl font-display font-bold text-slate-800 leading-tight"></h1>
                        </div>
                        <div class="flex items-center gap-3">
                        @if($viewMode !== 'submitted')
                            <button onclick="openSubtaskModal(activeTaskId)"
                                class="premium-button from-cyan-500 to-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md flex items-center gap-2 hover:scale-105 transition-all duration-200"
                                title="Add Subtask">
                                <i class="fa-solid fa-plus text-xs"></i> <span>Subtask</span>
                            </button>
                            <button onclick="openModal('updateStatusModal')" id="btn-update-status"
                                class="premium-button from-indigo-600 to-purple-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md flex items-center gap-2 hover:scale-105 transition-all duration-200">
                                <i class="fa-solid fa-pen text-sm"></i> Update Status
                            </button>
                        @else
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-2 rounded-xl border border-amber-200">
                                <i class="fa-solid fa-clock-rotate-left mr-1"></i> Awaiting line manager approval
                            </span>
                        @endif
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned
                                By</span>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span id="detail-assigned-by" class="font-bold text-slate-700 text-sm"></span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned
                                To</span>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-bold">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <span id="detail-assigned-to" class="font-bold text-slate-700 text-sm"></span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned
                                Date</span>
                            <span id="detail-assigned-date" class="font-bold text-slate-700 text-sm"></span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Due
                                Date</span>
                            <span id="detail-due-date" class="font-bold text-slate-700 text-sm"></span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Progress</span>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                     <div id="detail-progress-bar" class="h-full bg-indigo-600 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <span id="detail-progress-text" class="font-bold text-slate-700 text-sm">0%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Split -->
                <div class="flex-1 flex" style="overflow: hidden; min-height: 0;">
                    <div class="flex-1 border-r border-slate-100 bg-white" style="overflow: hidden;">
                        <div
                            style="overflow-y: auto !important; height: 100% !important; padding: 2rem; padding-right: 10px !important;">
                            <h3 class="text-lg font-bold text-premium mb-4">Task Description</h3>
                            <div id="detail-desc" class="prose prose-slate max-w-none text-slate-600 leading-relaxed"></div>
                            <div id="detail-attachment-wrap" class="mt-8 pt-8 border-t border-slate-100 hidden">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">
                                    <i class="fa-solid fa-paperclip mr-2"></i>Attachment
                                </h3>
                                <a id="detail-attachment-link" href="#" target="_blank" class="group block">
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 group-hover:border-indigo-200 group-hover:bg-indigo-50/30 transition-all">
                                        <div id="detail-attachment-icon-box"
                                            class="w-12 h-12 rounded-lg bg-white shadow-sm text-indigo-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                            <i id="detail-attachment-icon" class="fa-solid fa-file"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <p id="detail-attachment-name"
                                                class="text-sm font-bold text-slate-700 truncate group-hover:text-indigo-700 transition-colors">
                                                File Name</p>
                                            <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Click
                                                to View / Download</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Logs -->
                    <div class="w-96 bg-slate-50/50 flex flex-col">
                        <div class="px-6 pt-6 pb-3 flex-shrink-0">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Activity Log</h3>
                        </div>
                        <div class="logs-scroll-panel px-6 pb-6">
                            <div id="logs-timeline" class="space-y-6 border-l-2 border-slate-200 ml-3 pl-6 relative">
                                <!-- Dynamic Logs -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div class="modal" id="newTaskModal">
        <div class="modal-backdrop" onclick="closeModal('newTaskModal')"></div>
        <div class="modal-content max-w-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium" id="task-modal-title">Create New Task</h2>
                    <p class="text-slate-500 text-sm mt-1">
                        @if($isLineManager)
                            Assign a new task to an employee.
                        @else
                            Task will be sent to your <strong class="text-amber-600">line manager</strong> for review &amp; assignment.
                        @endif
                    </p>
                </div>
                <button onclick="closeModal('newTaskModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form onsubmit="saveTask(event)" class="space-y-4" enctype="multipart/form-data" id="create-task-form">
                @csrf
                <input type="hidden" name="parent_task_id" id="task_parent_id">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Assign To</label>
                        <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">Not specified</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Priority</label>
                        <select name="priority_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($priorities as $p)
                                <option value="{{ $p->priority_id }}">{{ $p->priority_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Task Title</label>
                    <input type="text" name="task_title" class="premium-input w-full px-4 py-3 text-sm" required
                        placeholder="What needs to be done?">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                    <textarea name="task_description" rows="3" class="premium-input w-full px-4 py-3 text-sm"
                        placeholder="Additional details..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Start Schedule</label>
                        <input type="date" name="task_assigned_date" class="premium-input w-full text-sm" value="{{ date('Y-m-d') }}" required>
                        <select name="start_time" class="premium-input w-full text-sm">
                            <option value="">Start Time (Optional)</option>
                            @for($i = 6; $i <= 22; $i++)
                                @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $h }}:00:00">{{ $h }}:00</option>
                            @endfor
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Due Deadline</label>
                        <input type="date" name="task_due_date" class="premium-input w-full text-sm" required>
                        <select name="end_time" class="premium-input w-full text-sm">
                            <option value="">End Time (Optional)</option>
                            @for($i = 6; $i <= 22; $i++)
                                @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $h }}:00:00" {{ $i == 14 ? 'selected' : '' }}>{{ $h }}:00</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-paperclip text-indigo-500 mr-1"></i> Attachment <span class="text-slate-300">(Optional)</span>
                    </label>
                    <input type="file" name="task_attachment" id="task_attachment" class="premium-input w-full px-4 py-3 text-sm">
                    <div id="task-attachment-preview"></div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('newTaskModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Create Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal" id="updateStatusModal">
        <div class="modal-backdrop" onclick="closeModal('updateStatusModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-display font-bold text-premium">Update Task Status</h2>
                <button onclick="closeModal('updateStatusModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form onsubmit="updateTaskStatus(event)" class="space-y-4">
                @csrf
                <input type="hidden" id="update-task-id" name="task_id">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New Status</label>
                    <select name="status_id" id="update-status-id" class="premium-input w-full px-4 py-3" required
                        onchange="onStatusChange(this)">
                        @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}" data-name="{{ strtolower($status->status_name) }}">
                                {{ $status->status_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Progress</label>
                        <span id="update-progress-value" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg">0%</span>
                    </div>
                    <input type="range" name="task_progress" id="update-task-progress" min="0" max="100" step="1"
                        class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                        oninput="document.getElementById('update-progress-value').innerText = this.value + '%'">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remark /
                        Note</label>
                    <textarea name="log_remark" rows="3" class="premium-input w-full px-4 py-3"
                        placeholder="Enter reason for update..." required></textarea>
                </div>
                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                    <button type="button" onclick="closeModal('updateStatusModal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Update
                        Status</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .tasks-layout {
            display: grid;
            grid-template-columns: 350px 1fr;
            height: calc(100vh - 161px);
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .tasks-sidebar {
            border-right: 1px solid #f1f5f9;
            background: #fbfcfd;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tasks-list {
            overflow-y: auto;
            flex: 1;
        }

        .task-card.active {
            background-color: white;
            border-color: #e0e7ff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .task-card.active .active-indicator {
            opacity: 1;
        }

        .task-card.active h3 {
            color: #4f46e5;
        }

        /* Activity Log scrollable panel */
        .logs-scroll-panel {
            height: calc(100vh - 161px - 80px - 130px);
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>

@push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        let activeTaskId = null;

        window.addEventListener('DOMContentLoaded', () => {
            window.ajaxPagination = new AjaxPagination({
                endpoint: "{{ route('hr.tasks.data', ['view_mode' => $viewMode, 'status_id' => $statusId]) }}",
                containerSelector: '#tasks-container',
                paginationSelector: '#tasks-pagination',
                renderCallback: function (tasks) {
                    const container = document.querySelector('#tasks-container');
                    if (tasks.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-10">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                    <i class="fa-solid fa-clipboard-check text-2xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm">No tasks found</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    const currentViewMode = "{{ $viewMode }}";
                    tasks.forEach(task => {
                        const person = (currentViewMode == 'assigned_to') ? task.assigned_by : task.assigned_to;
                        const initials = (person ? person.first_name : 'S').charAt(0);
                        const personName = person ? person.first_name : 'Unknown';

                        // Main Task
                        html += `
                            <div onclick="loadTask(${task.task_id})" id="task-item-${task.task_id}"
                                class="task-card p-4 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer hover:shadow-md hover:border-indigo-200 transition-all group relative overflow-hidden ${activeTaskId == task.task_id ? 'active' : ''}">

                                <div class="flex justify-between items-start mb-2">
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                        style="background: #${task.priority.priority_color}20; color: #${task.priority.priority_color}">
                                        ${task.priority.priority_name}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">#${task.task_id}</span>
                                </div>

                                <h3 class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors mb-1 line-clamp-2">
                                    ${task.task_title}
                                </h3>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            ${initials}
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[80px]">${personName}</span>
                                    </div>
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold"
                                        style="background: #${task.status.status_color}20; color: #${task.status.status_color}">
                                        ${task.status.status_name}
                                    </span>
                                </div>

                                <div class="active-indicator w-1 h-full absolute left-0 top-0 bg-indigo-600 ${activeTaskId == task.task_id ? 'opacity-100' : 'opacity-0'} transition-opacity">
                                </div>
                            </div>
                        `;

                        // Subtasks
                        if (task.subtasks && task.subtasks.length > 0) {
                            task.subtasks.forEach(sub => {
                                const subPerson = (currentViewMode == 'assigned_to') ? sub.assigned_by : sub.assigned_to;
                                const subInitials = (subPerson ? subPerson.first_name : '?').charAt(0);
                                const subName = subPerson ? subPerson.first_name : 'Unknown';

                                html += `
                                    <div onclick="loadTask(${sub.task_id})" id="task-item-${sub.task_id}"
                                         class="task-card subtask-card ml-6 p-3 rounded-xl bg-slate-50 border border-slate-100 shadow-sm cursor-pointer hover:shadow-md hover:border-indigo-200 transition-all group relative overflow-hidden mb-2 ${activeTaskId == sub.task_id ? 'active' : ''}">
                                         <div class="absolute -left-3 top-1/2 w-3 h-[2px] bg-slate-200"></div>
                                         <div class="flex justify-between items-start mb-1">
                                              <div class="flex items-center gap-1">
                                                  <i class="fa-solid fa-turn-up rotate-90 text-[10px] text-slate-300"></i>
                                                  <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-white border border-slate-200 text-slate-400">Sub</span>
                                              </div>
                                              <span class="text-[9px] text-slate-400 font-mono">#${sub.task_id}</span>
                                         </div>
                                         <h3 class="font-bold text-slate-700 text-xs group-hover:text-indigo-600 transition-colors mb-1 line-clamp-1">
                                             ${sub.task_title}
                                         </h3>
                                         <div class="flex items-center justify-between mt-2">
                                             <div class="flex items-center gap-2">
                                                 <div class="w-5 h-5 rounded-full bg-white flex items-center justify-center text-[9px] font-bold text-slate-400 shadow-sm">
                                                     ${subInitials}
                                                 </div>
                                                 <span class="text-[10px] text-slate-400 font-medium">${subName}</span>
                                             </div>
                                             <span class="px-1.5 py-0.5 rounded text-[9px] font-bold"
                                                 style="background: #${sub.status.status_color}20; color: #${sub.status.status_color}">
                                                 ${sub.status.status_name}
                                             </span>
                                         </div>
                                          <div class="active-indicator w-1 h-full absolute left-0 top-0 bg-indigo-600 ${activeTaskId == sub.task_id ? 'opacity-100' : 'opacity-0'} transition-opacity"></div>
                                    </div>
                                 `;
                            });
                        }
                    });
                    container.innerHTML = html;
                }
            });

            // Initial pagination setup
            @if($tasks->hasPages())
                window.ajaxPagination.renderPagination({
                    current_page: {{ $tasks->currentPage() }},
                    last_page: {{ $tasks->lastPage() }},
                    from: {{ $tasks->firstItem() }},
                    to: {{ $tasks->lastItem() }},
                    total: {{ $tasks->total() }}
                });
            @endif
        });



        // Auto-set progress to 100% when Done/Completed status is selected
        function onStatusChange(select) {
            const selectedOption = select.options[select.selectedIndex];
            const statusName = (selectedOption.dataset.name || '').toLowerCase();
            const statusId = parseInt(select.value);
            const isDone = statusId === 4 || statusName.includes('done') || statusName.includes('complet');
            
            if (isDone) {
                const progressSlider = document.getElementById('update-task-progress');
                const progressValue = document.getElementById('update-progress-value');
                if (progressSlider && progressValue) {
                    progressSlider.value = 100;
                    progressValue.innerText = '100%';
                }
            }
        }


        async function loadTask(id) {
            activeTaskId = id;
            document.getElementById('update-task-id').value = id;

            // UI Updates
            document.querySelectorAll('.task-card').forEach(c => c.classList.remove('active'));
            document.getElementById(`task-item-${id}`).classList.add('active');

            document.getElementById('selection-placeholder').classList.add('hidden');
            document.getElementById('task-content').classList.remove('hidden');

            try {
                const response = await fetch(`{{ url('hr/tasks') }}/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();

                if (result.success) {
                    const task = result.data;

                    // Update Status Modal progress slider
                    const progressSlider = document.getElementById('update-task-progress');
                    const progressValue = document.getElementById('update-progress-value');
                    if (progressSlider && progressValue) {
                        progressSlider.value = task.task_progress || 0;
                        progressValue.innerText = (task.task_progress || 0) + '%';
                    }

                    // Populate Header
                    document.getElementById('detail-id').innerText = `TASK-${task.task_id}`;
                    document.getElementById('detail-title').innerText = task.task_title;
                    document.getElementById('detail-desc').innerHTML = task.task_description || '<em>No description provided.</em>';

                    // Badges
                    const pEl = document.getElementById('detail-priority');
                    pEl.innerText = task.priority.priority_name;
                    pEl.style.backgroundColor = `#${task.priority.priority_color}20`;
                    pEl.style.color = `#${task.priority.priority_color}`;

                    const sEl = document.getElementById('detail-status');
                    sEl.innerText = task.status.status_name;
                    sEl.style.backgroundColor = `#${task.status.status_color}20`;
                    sEl.style.color = `#${task.status.status_color}`;

                    // Stats
                    document.getElementById('detail-assigned-by').innerText = task.assigned_by ? `${task.assigned_by.first_name} ${task.assigned_by.last_name}` : 'N/A';
                    document.getElementById('detail-assigned-to').innerText = task.assigned_to ? `${task.assigned_to.first_name} ${task.assigned_to.last_name}` : 'N/A';
                    document.getElementById('detail-assigned-date').innerText = new Date(task.task_assigned_date).toLocaleDateString();
                    document.getElementById('detail-due-date').innerText = new Date(task.task_due_date).toLocaleDateString();

                    // Progress
                    const prog = task.task_progress || 0;
                    document.getElementById('detail-progress-bar').style.width = `${prog}%`;
                    document.getElementById('detail-progress-text').innerText = `${prog}%`;

                    // Update select
                    document.getElementById('update-status-id').value = task.status_id;

                    // Attachment
                    const attachWrap = document.getElementById('detail-attachment-wrap');
                    const attachLink = document.getElementById('detail-attachment-link');
                    const attachName = document.getElementById('detail-attachment-name');
                    const attachIcon = document.getElementById('detail-attachment-icon');

                    if (task.task_attachment) {
                        attachWrap.classList.remove('hidden');
                        attachLink.href = `{{ url('/') }}/${task.task_attachment}`;

                        // Parse Filename
                        const parts = task.task_attachment.split('/');
                        const filename = parts[parts.length - 1].replace(/^\d+_/, '');
                        attachName.textContent = filename;

                        // Set Icon
                        const ext = filename.split('.').pop().toLowerCase();
                        const icons = {
                            'pdf': 'fa-file-pdf', 'doc': 'fa-file-word', 'docx': 'fa-file-word',
                            'xls': 'fa-file-excel', 'xlsx': 'fa-file-excel',
                            'ppt': 'fa-file-powerpoint', 'pptx': 'fa-file-powerpoint',
                            'jpg': 'fa-file-image', 'jpeg': 'fa-file-image', 'png': 'fa-file-image', 'gif': 'fa-file-image',
                            'zip': 'fa-file-archive', 'rar': 'fa-file-archive',
                            'txt': 'fa-file-lines', 'csv': 'fa-file-csv'
                        };
                        attachIcon.className = `fa-solid ${icons[ext] || 'fa-file'}`;

                    } else {
                        attachWrap.classList.add('hidden');
                    }

                    // Logs
                    renderLogs(task.logs);


                }
            } catch (e) { console.error('Error loading task:', e); }
        }

        function renderLogs(logs) {
            const container = document.getElementById('logs-timeline');
            container.innerHTML = '';

            if (!logs || logs.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-400 italic">No activity logs found.</p>';
                return;
            }

            logs.forEach(log => {
                const date = new Date(log.log_date).toLocaleString();
                const html = `
                                                                                <div class="relative">
                                                                                    <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-slate-200 border-2 border-white ring-1 ring-slate-100"></div>
                                                                                    <div class="space-y-1">
                                                                                        <div class="flex justify-between items-center text-xs">
                                                                                            <span class="font-bold text-slate-700">${log.log_action}</span>
                                                                                            <span class="text-slate-400 font-mono">${date}</span>
                                                                                        </div>
                                                                                        <p class="text-sm text-slate-600 bg-white p-3 rounded-xl border border-slate-100 shadow-sm leading-relaxed">${log.log_remark}</p>
                                                                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">By: ${log.logger ? log.logger.first_name : 'System'}</div>
                                                                                    </div>
                                                                                </div>
                                                                            `;
                container.innerHTML += html;
            });
        }

        async function saveTask(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch("{{ route('hr.tasks.store') }}", {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('newTaskModal');
                    window.location.reload();
                }
            } catch (err) { console.error(err); }
        }

        async function updateTaskStatus(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch("{{ route('hr.tasks.status.update') }}", {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('updateStatusModal');
                    // Refresh the entire page to update sidebar status and logs
                    window.location.reload();
                }
            } catch (err) { console.error(err); }
        }

        function openCreateTaskModal() {
            document.getElementById('create-task-form').reset();
            document.getElementById('task_parent_id').value = '';
            document.getElementById('task-modal-title').innerText = 'Create New Task';
            openModal('newTaskModal');
        }

        function openSubtaskModal(parentId) {
            if (!parentId) return;
            document.getElementById('create-task-form').reset();
            document.getElementById('task_parent_id').value = parentId;
            document.getElementById('task-modal-title').innerText = 'Create Subtask for #' + parentId;
            openModal('newTaskModal');
        }
    </script>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        // Initialize Attachment Preview for Create Task modal
        window.addEventListener('load', () => {
            if (window.initAttachmentPreview) {
                window.initAttachmentPreview({
                    inputSelector: '#task_attachment',
                    containerSelector: '#task-attachment-preview'
                });
            }
        });

        // File Size Validation (Max 10MB)
        const taskAttachmentInput = document.getElementById('task_attachment');
        if (taskAttachmentInput) {
            taskAttachmentInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const maxSize = 10 * 1024 * 1024;
                    if (this.files[0].size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'Attachment must not exceed 10MB.',
                            confirmButtonColor: '#4f46e5'
                        });
                        this.value = '';
                        document.getElementById('task-attachment-preview').innerHTML = '';
                    }
                }
            });
        }
    </script>
@endpush

{{-- Resubmit Modal (only needed in rejected view) --}}
@if($viewMode === 'rejected')
<div class="modal" id="resubmitModal">
    <div class="modal-backdrop" onclick="closeModal('resubmitModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-display font-bold text-premium">Edit & Resubmit Task</h2>
                <p class="text-sm text-amber-600 mt-1"><i class="fa-solid fa-rotate-right mr-1"></i> Correct and send for line manager review</p>
            </div>
            <button onclick="closeModal('resubmitModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form onsubmit="submitResubmit(event)" class="space-y-4">
            <input type="hidden" id="resubmit-task-id">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Task Title</label>
                <input type="text" id="resubmit-title" class="premium-input w-full px-4 py-3 text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                <textarea id="resubmit-desc" rows="4" class="premium-input w-full px-4 py-3 text-sm" placeholder="Describe the task..."></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('resubmitModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-rotate-right mr-2"></i>Resubmit for Approval
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function openResubmitModal(taskId, title, description) {
        document.getElementById('resubmit-task-id').value = taskId;
        document.getElementById('resubmit-title').value = title;
        document.getElementById('resubmit-desc').value = description;
        openModal('resubmitModal');
    }
    async function submitResubmit(e) {
        e.preventDefault();
        const taskId = document.getElementById('resubmit-task-id').value;
        const formData = new FormData();
        formData.append('task_title', document.getElementById('resubmit-title').value);
        formData.append('task_description', document.getElementById('resubmit-desc').value);
        try {
            const res = await fetch(`{{ url('hr/tasks') }}/${taskId}/resubmit`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await res.json();
            if (result.success) {
                closeModal('resubmitModal');
                Swal.fire({ icon: 'success', title: 'Resubmitted!', text: result.message, timer: 2000, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: result.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to resubmit.' });
        }
    }
</script>
@endif
@endsection