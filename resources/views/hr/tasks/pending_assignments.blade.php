@extends('layouts.app')

@section('title', 'Pending Task Assignments')
@section('subtitle', 'Tasks awaiting your review and assignment')

@section('content')
<div class="space-y-6">

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="premium-card p-5 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-all">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-bold text-slate-700">{{ $tasks->count() }}</p>
            </div>
        </div>
        <div class="premium-card p-5 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Available Staff</p>
                <p class="text-2xl font-bold text-slate-700">{{ $employees->count() }}</p>
            </div>
        </div>
        <div class="premium-card p-5 flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-white transition-all">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Your Role</p>
                <p class="text-sm font-bold text-slate-700">Line Manager</p>
            </div>
        </div>
    </div>

    <!-- Task Table -->
    <div class="premium-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-display font-bold text-premium">Tasks Awaiting Assignment</h2>
                <p class="text-sm text-slate-500 mt-1">Review each task and assign it to the appropriate team member.</p>
            </div>
            <a href="{{ route('hr.tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-brand text-white text-sm font-bold rounded-xl shadow hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to Tasks
            </a>
        </div>

        @if($tasks->isEmpty())
            <div class="p-16 text-center">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6 text-green-400">
                    <i class="fa-solid fa-inbox text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 mb-2">All Clear!</h3>
                <p class="text-slate-400">No tasks are pending your review. You're all caught up.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left pl-6">#</th>
                            <th class="text-left">Task</th>
                            <th class="text-left">Priority</th>
                            <th class="text-left">Created By</th>
                            <th class="text-left">Attachment</th>
                            <th class="text-left">Due Date</th>
                            <th class="text-left pr-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td class="pl-6">
                                <span class="font-mono text-slate-400 text-sm">#{{ $task->task_id }}</span>
                            </td>
                            <td>
                                <div>
                                    <button onclick="openDetailModal({{ $task->task_id }})"
                                        class="font-bold text-slate-800 hover:text-indigo-600 text-left transition-colors">
                                        {{ $task->task_title }}
                                    </button>
                                    @if($task->task_description)
                                    <p class="text-sm text-slate-500 mt-0.5 line-clamp-1">{{ $task->task_description }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($task->priority)
                                <span class="px-3 py-1 rounded-full text-xs font-bold"
                                    style="background: #{{ $task->priority->priority_color }}20; color: #{{ $task->priority->priority_color }}">
                                    {{ $task->priority->priority_name }}
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($task->assignedBy)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($task->assignedBy->first_name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">{{ $task->assignedBy->first_name }} {{ $task->assignedBy->last_name }}</span>
                                </div>
                                @else
                                <span class="text-slate-400 text-sm">—</span>
                                @endif
                            </td>
                            <td>
                                @if($task->task_attachment)
                                    <a href="{{ asset($task->task_attachment) }}" target="_blank"
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-colors">
                                        <i class="fa-solid fa-paperclip"></i> View File
                                    </a>
                                @else
                                    <span class="text-slate-300 text-sm">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm font-medium {{ now()->gt($task->task_due_date) ? 'text-red-600' : 'text-slate-600' }}">
                                    {{ \Carbon\Carbon::parse($task->task_due_date)->format('Y-m-d') }}
                                    @if(now()->gt($task->task_due_date))
                                    <span class="text-xs font-bold text-red-500 ml-1">(Overdue)</span>
                                    @endif
                                </span>
                            </td>
                            <td class="pr-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="openAssignModal({{ $task->task_id }}, '{{ addslashes($task->task_title) }}')"
                                        class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-brand text-white text-sm font-bold rounded-xl shadow hover:scale-105 transition-all duration-200">
                                        <i class="fa-solid fa-user-plus text-xs"></i> Assign
                                    </button>
                                    <button onclick="openRejectModal({{ $task->task_id }}, '{{ addslashes($task->task_title) }}')"
                                        class="inline-flex items-center gap-1 px-3 py-2 bg-red-500 text-white text-sm font-bold rounded-xl shadow hover:scale-105 hover:bg-red-600 transition-all duration-200">
                                        <i class="fa-solid fa-xmark text-xs"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Hidden task data for JS detail modal --}}
<script>
const taskData = {
    @foreach($tasks as $task)
    {{ $task->task_id }}: {
        title: "{{ addslashes($task->task_title) }}",
        description: "{{ addslashes($task->task_description ?? '') }}",
        priority: "{{ $task->priority->priority_name ?? '—' }}",
        priorityColor: "#{{ $task->priority->priority_color ?? '6366f1' }}",
        createdBy: "{{ $task->assignedBy ? $task->assignedBy->first_name . ' ' . $task->assignedBy->last_name : '—' }}",
        dueDate: "{{ \Carbon\Carbon::parse($task->task_due_date)->format('d M Y') }}",
        startDate: "{{ \Carbon\Carbon::parse($task->task_assigned_date)->format('d M Y') }}",
        attachment: "{{ $task->task_attachment ? asset($task->task_attachment) : '' }}",
    },
    @endforeach
};
</script>

<!-- Task Detail Modal -->
<div class="modal" id="detailModal">
    <div class="modal-backdrop" onclick="closeModal('detailModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-display font-bold text-premium">Task Details</h2>
            <button onclick="closeModal('detailModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="space-y-4">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Task Title</p>
                <p id="d-title" class="text-lg font-bold text-slate-800"></p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Description</p>
                <p id="d-desc" class="text-sm text-slate-600 bg-slate-50 rounded-xl p-3 min-h-[48px]"></p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Priority</p>
                    <span id="d-priority" class="px-2 py-0.5 rounded-full text-xs font-bold"></span>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Due Date</p>
                    <p id="d-due" class="text-sm font-bold text-slate-700"></p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Start Date</p>
                    <p id="d-start" class="text-sm font-bold text-slate-700"></p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Created By</p>
                    <p id="d-created-by" class="text-sm font-bold text-slate-700"></p>
                </div>
            </div>
            <div id="d-attachment-wrap" class="hidden">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Attachment</p>
                <a id="d-attachment" href="#" target="_blank"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-sm font-bold hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-paperclip"></i> View Attachment
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div class="modal" id="assignModal">
    <div class="modal-backdrop" onclick="closeModal('assignModal')"></div>
    <div class="modal-content max-w-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-display font-bold text-premium">Assign Task</h2>
                <p id="assign-task-title" class="text-sm text-slate-500 mt-1 line-clamp-1"></p>
            </div>
            <button onclick="closeModal('assignModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form onsubmit="submitAssignment(event)" class="space-y-4">
            <input type="hidden" id="assign-task-id">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Assign To</label>
                <select id="assign-employee" name="assigned_to" class="premium-input w-full px-4 py-3" required>
                    <option value="">Select Employee...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}
                            @if($emp->designation) — {{ $emp->designation->designation_name }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('assignModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-user-check mr-2"></i>Assign Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-backdrop" onclick="closeModal('rejectModal')"></div>
    <div class="modal-content max-w-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-display font-bold text-red-600">Reject Task</h2>
                <p id="reject-task-title" class="text-sm text-slate-500 mt-1 line-clamp-1"></p>
            </div>
            <button onclick="closeModal('rejectModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form onsubmit="submitRejection(event)" class="space-y-4">
            <input type="hidden" id="reject-task-id">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rejection Reason <span class="text-red-400">*</span></label>
                <textarea id="reject-reason" rows="4" class="premium-input w-full px-4 py-3 text-sm"
                    placeholder="Explain why this task is being rejected..." required></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('rejectModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-red-500 text-white font-bold rounded-xl shadow-lg hover:bg-red-600 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-xmark mr-2"></i>Reject Task
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDetailModal(taskId) {
        const t = taskData[taskId];
        if (!t) return;
        document.getElementById('d-title').innerText = t.title;
        document.getElementById('d-desc').innerText = t.description || 'No description provided.';
        const pEl = document.getElementById('d-priority');
        pEl.innerText = t.priority;
        pEl.style.background = t.priorityColor + '20';
        pEl.style.color = t.priorityColor;
        document.getElementById('d-due').innerText = t.dueDate;
        document.getElementById('d-start').innerText = t.startDate;
        document.getElementById('d-created-by').innerText = t.createdBy;
        const attachWrap = document.getElementById('d-attachment-wrap');
        if (t.attachment) {
            document.getElementById('d-attachment').href = t.attachment;
            attachWrap.classList.remove('hidden');
        } else {
            attachWrap.classList.add('hidden');
        }
        openModal('detailModal');
    }

    function openAssignModal(taskId, taskTitle) {
        document.getElementById('assign-task-id').value = taskId;
        document.getElementById('assign-task-title').innerText = taskTitle;
        document.getElementById('assign-employee').value = '';
        openModal('assignModal');
    }

    function openRejectModal(taskId, taskTitle) {
        document.getElementById('reject-task-id').value = taskId;
        document.getElementById('reject-task-title').innerText = taskTitle;
        document.getElementById('reject-reason').value = '';
        openModal('rejectModal');
    }

    async function submitAssignment(e) {
        e.preventDefault();
        const taskId = document.getElementById('assign-task-id').value;
        const assignedTo = document.getElementById('assign-employee').value;
        if (!assignedTo) return;
        const formData = new FormData();
        formData.append('assigned_to', assignedTo);
        try {
            const res = await fetch(`{{ url('hr/tasks') }}/${taskId}/assign`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await res.json();
            if (result.success) {
                closeModal('assignModal');
                Swal.fire({ icon: 'success', title: 'Task Assigned!', text: result.message, timer: 2000, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: result.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to assign task.' });
        }
    }

    async function submitRejection(e) {
        e.preventDefault();
        const taskId = document.getElementById('reject-task-id').value;
        const reason = document.getElementById('reject-reason').value;
        if (!reason.trim()) return;
        const formData = new FormData();
        formData.append('rejection_reason', reason);
        try {
            const res = await fetch(`{{ url('hr/tasks') }}/${taskId}/reject`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await res.json();
            if (result.success) {
                closeModal('rejectModal');
                Swal.fire({ icon: 'info', title: 'Task Rejected', text: result.message, timer: 2500, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: result.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to reject task.' });
        }
    }
</script>
@endpush
@endsection
