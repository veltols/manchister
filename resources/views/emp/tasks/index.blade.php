@extends('layouts.app')

@section('title', 'Tasks Management')
@section('subtitle', $viewMode == 'my_tasks' ? 'Tasks assigned to you' : 'Tasks you assigned to others')

@section('content')
    <div class="tasks-layout">
        <!-- Sidebar: Tasks List -->
        <div class="tasks-sidebar">
            <div class="sidebar-header">
                <div>
                    <h2 class="text-xl font-bold text-premium">
                        {{ $viewMode == 'my_tasks' ? 'My Tasks' : 'Others Tasks' }}
                    </h2>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('emp.tasks.index', ['view_mode' => 'my_tasks']) }}" 
                           class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded {{ $viewMode == 'my_tasks' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-400 hover:text-indigo-600' }}">
                            Assigned to Me
                        </a>
                        <a href="{{ route('emp.tasks.index', ['view_mode' => 'others_tasks']) }}" 
                           class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded {{ $viewMode == 'others_tasks' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-400 hover:text-indigo-600' }}">
                            Assigned by Me
                        </a>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="openModal('newTaskModal')"
                        class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('emp.tasks.index') }}" method="GET">
                    <input type="hidden" name="view_mode" value="{{ $viewMode }}">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="status_id" class="hidden"> <!-- Placeholder for status filter if needed -->
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

            <div class="flex-1" style="overflow-y: auto !important; height: 100% !important; padding: 1rem; padding-right: 10px !important;">
                <div class="space-y-3">
                    @forelse($tasks as $task)
                        <div onclick="loadTask({{ $task->task_id }})" id="task-item-{{ $task->task_id }}"
                            class="task-card p-4 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer hover:shadow-md hover:border-indigo-200 transition-all group relative overflow-hidden">
    
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                    style="background: #{{ $task->priority->priority_color ?? 'ccc' }}20; color: #{{ $task->priority->priority_color ?? 'ccc' }}">
                                    {{ $task->priority->priority_name ?? 'Normal' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">#{{ $task->task_id }}</span>
                            </div>
    
                            <h3 class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors mb-1 line-clamp-2">
                                {{ $task->task_title }}
                            </h3>
    
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center gap-2">
                                    @php 
                                        $person = ($viewMode == 'my_tasks') ? $task->assignedBy : $task->assignedTo;
                                    @endphp
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                        {{ substr($person->first_name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-xs text-slate-500 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[80px]">
                                        {{ $person->first_name ?? 'Unknown' }}
                                    </span>
                                </div>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold"
                                    style="background: #{{ $task->status->status_color ?? 'ccc' }}20; color: #{{ $task->status->status_color ?? 'ccc' }}">
                                    {{ $task->status->status_name ?? 'Unknown' }}
                                </span>
                            </div>
    
                            <div class="active-indicator w-1 h-full absolute left-0 top-0 bg-indigo-600 opacity-0 transition-opacity"></div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="fa-solid fa-clipboard-check text-2xl"></i>
                            </div>
                            <p class="text-slate-400 text-sm">No tasks found</p>
                        </div>
                    @endforelse
                    
                    @if($tasks->hasPages())
                        <div class="pt-4 flex justify-center">
                            {{ $tasks->appends(['view_mode' => $viewMode, 'status_id' => $statusId])->links('pagination::simple-tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content: Task Details -->
        <div class="tasks-main">
            <div id="selection-placeholder"
                class="h-full flex flex-col items-center justify-center p-12 text-center animate-fade-in">
                <div class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mb-8 text-indigo-500 shadow-inner">
                    <i class="fa-solid fa-list-check text-5xl"></i>
                </div>
                <h2 class="text-2xl font-display font-bold text-premium mb-4">Select a Task</h2>
                <p class="text-slate-500 max-w-sm">Choose a task from the sidebar to view its details, progress, and activity logs.</p>
            </div>

            <div id="task-content" class="hidden h-full flex flex-col animate-fade-in relative">
                <!-- Header -->
                <div class="p-8 border-b border-slate-100 bg-white">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <span id="detail-id" class="font-mono text-slate-400 text-sm"></span>
                                <span id="detail-priority" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"></span>
                                <span id="detail-status" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"></span>
                            </div>
                            <h1 id="detail-title" class="text-2xl md:text-3xl font-display font-bold text-slate-800 leading-tight"></h1>
                        </div>
                        <button onclick="openModal('updateStatusModal')" id="btn-update-status"
                            class="premium-button from-indigo-600 to-purple-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md flex items-center gap-2 transition-transform hover:scale-105">
                            <i class="fa-solid fa-pen text-sm"></i> Update Status
                        </button>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned By</span>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span id="detail-assigned-by" class="font-bold text-slate-700 text-sm"></span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned To</span>
                            <div class="flex items-center gap-2">
                                <span id="detail-assigned-to" class="font-bold text-slate-700 text-sm"></span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Due Date</span>
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
                <div class="flex-1 overflow-hidden flex flex-col md:flex-row">
                    <!-- Description -->
                    <div class="flex-1 border-b md:border-b-0 md:border-r border-slate-100 bg-white" style="overflow: hidden;">
                        <div style="overflow-y: auto !important; height: 100% !important; padding: 2rem; padding-right: 10px !important;">
                            <h3 class="text-lg font-bold text-premium mb-4">Task Description</h3>
                            <div id="detail-desc" class="prose prose-slate max-w-none text-slate-600 leading-relaxed"></div>
                        </div>
                    </div>

                    <!-- Logs -->
                    <div class="w-full md:w-96 bg-slate-50/50 p-6 flex flex-col" style="overflow: hidden;">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Activity Log</h3>
                        <div style="overflow-y: auto !important; height: 100% !important; padding-right: 10px !important;">
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
                    <h2 class="text-2xl font-display font-bold text-premium">Create New Task</h2>
                    <p class="text-slate-500 text-sm mt-1">Assign a new task to an employee</p>
                </div>
                <button onclick="closeModal('newTaskModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            
            <form onsubmit="saveTask(event)" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Assign To</label>
                        <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="{{ auth()->user()->employee->employee_id }}">Me (Self)</option>
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
                    <input type="text" name="task_title" class="premium-input w-full px-4 py-3 text-sm" required placeholder="What needs to be done?">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                    <textarea name="task_description" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Additional details..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Start Schedule</label>
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
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Due Deadline</label>
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
                    <button type="button" onclick="closeModal('newTaskModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Create Task</button>
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
                <button onclick="closeModal('updateStatusModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form onsubmit="updateTaskStatus(event)" class="space-y-4">
                @csrf
                <input type="hidden" id="update-task-id" name="task_id">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New Status</label>
                    <select name="status_id" id="update-status-id" class="premium-input w-full px-4 py-3" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div x-data="{ localProgress: 0 }" x-init="localProgress = parseInt(document.getElementById('update-task-progress')?.value || 0)">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Progress: <span class="text-brand-primary font-bold text-lg" x-text="localProgress + '%'"></span></label>
                    <input type="range" name="task_progress" id="update-task-progress" min="0" max="100" x-model="localProgress" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                    <div class="flex justify-between text-xs text-slate-400 mt-2"><span>0%</span><span>100%</span></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remark / Note</label>
                    <textarea name="log_remark" rows="3" class="premium-input w-full px-4 py-3" placeholder="Enter reason for update..." required></textarea>
                </div>
                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                    <button type="button" onclick="closeModal('updateStatusModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Progress Modal -->
    <div class="modal" id="updateProgressModal">
        <div class="modal-backdrop" onclick="closeModal('updateProgressModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-display font-bold text-premium">Update Task Progress</h2>
                <button onclick="closeModal('updateProgressModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form onsubmit="updateTaskStatus(event)" class="space-y-6">
                @csrf
                <input type="hidden" id="update-progress-task-id" name="task_id">
                
                <div x-data="{ localProgress: 0 }" x-init="$watch('$parent.activeTaskProgress', value => localProgress = value)">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New Progress: <span class="text-brand-primary font-bold text-lg" x-text="localProgress + '%'"></span></label>
                    <input type="range" name="task_progress" min="0" max="100" x-model="localProgress" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                    <div class="flex justify-between text-xs text-slate-400 mt-2"><span>0%</span><span>100%</span></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remark / Note</label>
                    <textarea name="log_remark" rows="3" class="premium-input w-full px-4 py-3" placeholder="What was achieved?" required></textarea>
                </div>
                
                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                    <button type="button" onclick="closeModal('updateProgressModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Update Progress</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .tasks-layout {
            display: grid;
            grid-template-columns: 350px 1fr;
            height: calc(100vh - 145px);
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
            align-items: flex-start;
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

        @media (max-width: 768px) {
            .tasks-layout {
                grid-template-columns: 1fr; /* Stack on mobile */
                height: auto;
            }
            .tasks-main {
                display: none; /* Hide main content initially on mobile if needed or overlay */
            }
            /* Need logic to toggle views on mobile, but for now assuming desktop-first like HR */
        }
    </style>

    <script>
        let activeTaskId = null;

        // Progress Slider Logic
        const progressInput = document.getElementById('update-task-progress');
        const progressVal = document.getElementById('progress-val');
        if(progressInput) {
            progressInput.addEventListener('input', (e) => {
                progressVal.innerText = e.target.value + '%';
            });
        }

        async function loadTask(id) {
            activeTaskId = id;
            document.getElementById('update-task-id').value = id;

            // UI Updates
            document.querySelectorAll('.task-card').forEach(c => c.classList.remove('active'));
            const card = document.getElementById(`task-item-${id}`);
            if(card) card.classList.add('active');

            document.getElementById('selection-placeholder').classList.add('hidden');
            document.getElementById('task-content').classList.remove('hidden');

            try {
                const response = await fetch(`{{ url('emp/tasks') }}/${id}`);
                const result = await response.json();

                if (result.success) {
                    const task = result.data;

                    // Populate Header
                    document.getElementById('detail-id').innerText = `TASK-${task.task_id}`;
                    document.getElementById('detail-title').innerText = task.task_title;
                    document.getElementById('detail-desc').innerHTML = task.task_description || '<em>No description provided.</em>';

                    // Badges
                    const pEl = document.getElementById('detail-priority');
                    pEl.innerText = task.priority ? task.priority.priority_name : 'Normal';
                    pEl.style.backgroundColor = task.priority ? `#${task.priority.priority_color}20` : '#eee';
                    pEl.style.color = task.priority ? `#${task.priority.priority_color}` : '#666';

                    const sEl = document.getElementById('detail-status');
                    sEl.innerText = task.status ? task.status.status_name : 'Open';
                    sEl.style.backgroundColor = task.status ? `#${task.status.status_color}20` : '#eee';
                    sEl.style.color = task.status ? `#${task.status.status_color}` : '#666';

                    // Stats
                    const assignedBy = task.assignedBy || task.assigned_by;
                    const assignedTo = task.assignedTo || task.assigned_to;
                    
                    document.getElementById('detail-assigned-by').innerText = assignedBy ? `${assignedBy.first_name} ${assignedBy.last_name}` : 'N/A';
                    document.getElementById('detail-assigned-to').innerText = assignedTo ? `${assignedTo.first_name} ${assignedTo.last_name}` : 'N/A';
                    document.getElementById('detail-due-date').innerText = task.task_due_date ? new Date(task.task_due_date).toLocaleDateString() : 'N/A';
                    
                    // Progress
                    const prog = task.task_progress || 0;
                    document.getElementById('detail-progress-bar').style.width = `${prog}%`;
                    document.getElementById('detail-progress-text').innerText = `${prog}%`;

                    // Update Form Inputs
                    document.getElementById('update-status-id').value = task.status_id;
                    const progressInput = document.getElementById('update-task-progress');
                    if(progressInput) {
                        progressInput.value = prog;
                        // Trigger Alpine.js update by dispatching input event
                        progressInput.dispatchEvent(new Event('input'));
                    }

                    // Logs
                    renderLogs(task.logs);

                    // Button visibility (optional logic)
                    if (task.status_id == 4) { // Completed
                       // document.getElementById('btn-update-status').classList.add('hidden');
                    } else {
                        document.getElementById('btn-update-status').classList.remove('hidden');
                    }
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
                const response = await fetch("{{ route('emp.tasks.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('newTaskModal');
                    window.location.reload();
                } else {
                    alert('Error saving task');
                }
            } catch (err) { console.error(err); }
        }

        async function updateTaskStatus(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const id = document.getElementById('update-task-id').value;

            try {
                const response = await fetch(`{{ url('emp/tasks') }}/${id}/status`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('updateStatusModal');
                    loadTask(id);
                    // Ideally we refresh the list status without reload, but for now just load details
                    // We might want to reload page to update sidebar status color
                     window.location.reload(); 
                } else {
                    alert('Error updating status');
                }
            } catch (err) { console.error(err); }
        }
    </script>
@endsection
