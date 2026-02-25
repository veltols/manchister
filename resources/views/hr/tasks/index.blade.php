@extends('layouts.app')

@section('title', 'Tasks Management')
@section('subtitle', 'Track assignments, deadlines, and progress.')

@section('content')

    {{-- ═══════════════════════════════════════════════════════════
    ASANA-STYLE TASKS PAGE
    Full-width list + right-slide detail drawer
    ═══════════════════════════════════════════════════════════ --}}

    <div class="asana-page">

        {{-- ── Top Bar ──────────────────────────────────────────── --}}
        <div class="asana-topbar">
            <div class="flex items-center gap-3 flex-1">
                {{-- View Tabs --}}
                <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
                    <a href="{{ route('hr.tasks.index', ['view_mode' => 'assigned_by']) }}"
                        class="tab-btn {{ $viewMode == 'assigned_by' ? 'tab-active' : '' }}">
                        Assigned by Me
                    </a>
                    <a href="{{ route('hr.tasks.index', ['view_mode' => 'assigned_to']) }}"
                        class="tab-btn {{ $viewMode == 'assigned_to' ? 'tab-active' : '' }}">
                        Assigned to Me
                    </a>
                    @if($isLineManager)
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'pending']) }}"
                            class="tab-btn {{ $viewMode == 'pending' ? 'tab-active' : '' }} flex items-center gap-1.5">
                            Pending Approval
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="badge-count bg-[#004F68]">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    @endif
                    @if(isset($submittedCount) && $submittedCount > 0 || $viewMode === 'submitted')
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'submitted']) }}"
                            class="tab-btn {{ $viewMode == 'submitted' ? 'tab-active' : '' }} flex items-center gap-1.5">
                            Submitted
                            @if(isset($submittedCount) && $submittedCount > 0)
                                <span class="badge-count bg-amber-500">{{ $submittedCount }}</span>
                            @endif
                        </a>
                    @endif
                    <a href="{{ route('hr.tasks.index', ['view_mode' => 'rejected']) }}"
                        class="tab-btn {{ $viewMode == 'rejected' ? 'tab-active' : '' }} flex items-center gap-1.5">
                        Rejected
                        @if(isset($rejectedCount) && $rejectedCount > 0)
                            <span class="badge-count bg-red-500">{{ $rejectedCount }}</span>
                        @endif
                    </a>
                    @if($isLineManager)
                        <a href="{{ route('hr.tasks.index', ['view_mode' => 'rejected_by_me']) }}"
                            class="tab-btn {{ $viewMode == 'rejected_by_me' ? 'tab-active' : '' }} flex items-center gap-1.5">
                            Rejected by Me
                            @if(isset($rejectedByMeCount) && $rejectedByMeCount > 0)
                                <span class="badge-count bg-red-500">{{ $rejectedByMeCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>

                {{-- Status Filter --}}
                <form action="{{ route('hr.tasks.index') }}" method="GET" class="flex items-center">
                    <input type="hidden" name="view_mode" value="{{ $viewMode }}">
                    <div class="relative">
                        <i
                            class="fa-solid fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <select name="status_id" onchange="this.form.submit()"
                            class="pl-8 pr-8 py-2 text-sm bg-white border border-slate-200 rounded-xl text-slate-600 font-medium cursor-pointer focus:outline-none focus:ring-2 focus:ring-brand/30 appearance-none">
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

            {{-- Add Task Button --}}
            <button onclick="openCreateTaskModal()"
                class="flex items-center gap-2 px-4 py-2.5 bg-[#004F68] hover:bg-[#00384a] text-white text-sm font-bold rounded-xl shadow-sm transition-all hover:scale-105 active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                Add Task
            </button>
        </div>

        {{-- ── Task List ─────────────────────────────────────────── --}}
        <div class="asana-list-area" id="tasks-list-area">

            {{-- Column Headers --}}
            <div class="asana-list-header">
                <div class="col-name">Task Name</div>
                <div class="col-assignee">Assignee</div>
                <div class="col-due">Due Date</div>
                <div class="col-priority">Priority</div>
                <div class="col-status">Status</div>
                <div class="col-progress">Progress</div>
            </div>

            {{-- Tasks --}}
            <div id="tasks-container">
                @forelse($tasks as $task)
                    @php
                        $person = ($viewMode == 'assigned_to') ? $task->assignedBy : $task->assignedTo;
                        $dueDate = $task->task_due_date ? \Carbon\Carbon::parse($task->task_due_date) : null;
                        $isOverdue = $dueDate && $dueDate->isPast() && !in_array(strtolower($task->status->status_name ?? ''), ['done', 'completed', 'closed']);
                    @endphp
                    <div onclick="loadTask({{ $task->task_id }})" id="task-item-{{ $task->task_id }}"
                        class="asana-task-row group" data-task-id="{{ $task->task_id }}">

                        {{-- Checkbox + Title --}}
                        <div class="col-name">
                            <div class="flex items-center gap-3">
                                <button onclick="event.stopPropagation()"
                                    class="task-check flex-shrink-0 w-5 h-5 rounded-full border-2 border-slate-300 hover:border-[#004F68] transition-colors flex items-center justify-center group/cb">
                                    <i
                                        class="fa-solid fa-check text-[9px] text-transparent group-hover/cb:text-[#004F68] transition-colors"></i>
                                </button>
                                <span
                                    class="task-title-text font-medium text-slate-800 group-hover:text-[#004F68] transition-colors line-clamp-1">
                                    {{ $task->task_title }}
                                </span>
                                @if($viewMode === 'rejected')
                                    <span
                                        class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5 bg-red-100 text-red-600 rounded">Rejected</span>
                                @endif
                            </div>
                        </div>

                        {{-- Assignee --}}
                        <div class="col-assignee">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                    {{ strtoupper(substr($person->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <span
                                    class="text-sm text-slate-600 truncate max-w-[100px]">{{ $person->first_name ?? '—' }}</span>
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div class="col-due">
                            @if($dueDate)
                                <span
                                    class="text-sm font-medium {{ $isOverdue ? 'text-red-500' : 'text-slate-500' }} flex items-center gap-1">
                                    @if($isOverdue)<i class="fa-solid fa-circle-exclamation text-xs"></i>@endif
                                    {{ $dueDate->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-slate-300 text-sm">—</span>
                            @endif
                        </div>

                        {{-- Priority --}}
                        <div class="col-priority">
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-1 rounded-lg"
                                style="background: #{{ $task->priority->priority_color ?? 'ccc' }}18; color: #{{ $task->priority->priority_color ?? '999' }}">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                    style="background: #{{ $task->priority->priority_color ?? 'ccc' }}"></span>
                                {{ $task->priority->priority_name ?? '—' }}
                            </span>
                        </div>

                        {{-- Status --}}
                        <div class="col-status">
                            <span class="text-[11px] font-bold px-2 py-1 rounded-lg"
                                style="background: #{{ $task->status->status_color ?? 'ccc' }}18; color: #{{ $task->status->status_color ?? '999' }}">
                                {{ $task->status->status_name ?? '—' }}
                            </span>
                        </div>

                        {{-- Progress --}}
                        <div class="col-progress">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#004F68] rounded-full"
                                        style="width: {{ $task->task_progress ?? 0 }}%"></div>
                                </div>
                                <span
                                    class="text-xs text-slate-500 font-medium w-8 text-right">{{ $task->task_progress ?? 0 }}%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Rejected inline note --}}
                    @if($viewMode === 'rejected' && $task->rejection_reason)
                        <div class="asana-task-row-note">
                            <div class="flex items-start gap-2 px-4 py-2 bg-red-50 border-b border-red-100">
                                <i class="fa-solid fa-circle-xmark text-red-400 mt-0.5 text-xs"></i>
                                <span class="text-xs text-red-700">{{ $task->rejection_reason }}</span>
                                <button
                                    onclick="event.stopPropagation(); openResubmitModal({{ $task->task_id }}, '{{ addslashes($task->task_title) }}', '{{ addslashes($task->task_description ?? '') }}')"
                                    class="ml-auto flex-shrink-0 text-[11px] font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2 py-0.5 rounded transition-colors">
                                    <i class="fa-solid fa-rotate-right mr-1"></i>Resubmit
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Subtasks --}}
                    @if($task->subtasks && $task->subtasks->count() > 0)
                        @foreach($task->subtasks as $sub)
                            @php
                                $subPerson = ($viewMode == 'assigned_to') ? $sub->assignedBy : $sub->assignedTo;
                                $subDue = $sub->task_due_date ? \Carbon\Carbon::parse($sub->task_due_date) : null;
                                $subOverdue = $subDue && $subDue->isPast() && !in_array(strtolower($sub->status->status_name ?? ''), ['done', 'completed', 'closed']);
                            @endphp
                            <div onclick="loadTask({{ $sub->task_id }})" id="task-item-{{ $sub->task_id }}"
                                class="asana-task-row asana-subtask-row group" data-task-id="{{ $sub->task_id }}">
                                <div class="col-name">
                                    <div class="flex items-center gap-3 pl-8">
                                        <div class="sub-connector"></div>
                                        <button onclick="event.stopPropagation()"
                                            class="task-check flex-shrink-0 w-4 h-4 rounded-full border-2 border-slate-200 hover:border-[#004F68] transition-colors flex items-center justify-center">
                                            <i
                                                class="fa-solid fa-check text-[8px] text-transparent hover:text-[#004F68] transition-colors"></i>
                                        </button>
                                        <span
                                            class="font-medium text-slate-600 group-hover:text-[#004F68] transition-colors text-sm line-clamp-1">
                                            {{ $sub->task_title }}
                                        </span>
                                        <span
                                            class="text-[9px] font-bold px-1.5 py-0.5 bg-slate-100 text-slate-400 rounded uppercase tracking-wider">Sub</span>
                                    </div>
                                </div>
                                <div class="col-assignee">
                                    <span class="text-xs text-slate-500">{{ $subPerson->first_name ?? '—' }}</span>
                                </div>
                                <div class="col-due">
                                    @if($subDue)
                                        <span
                                            class="text-xs {{ $subOverdue ? 'text-red-500' : 'text-slate-400' }}">{{ $subDue->format('M d, Y') }}</span>
                                    @endif
                                </div>
                                <div class="col-priority">
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded"
                                        style="background: #{{ $sub->priority->priority_color ?? 'ccc' }}18; color: #{{ $sub->priority->priority_color ?? '999' }}">
                                        {{ $sub->priority->priority_name ?? '—' }}
                                    </span>
                                </div>
                                <div class="col-status">
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded"
                                        style="background: #{{ $sub->status->status_color ?? 'ccc' }}18; color: #{{ $sub->status->status_color ?? '999' }}">
                                        {{ $sub->status->status_name ?? '—' }}
                                    </span>
                                </div>
                                <div class="col-progress">
                                    <span class="text-xs text-slate-400">{{ $sub->task_progress ?? 0 }}%</span>
                                </div>
                            </div>
                        @endforeach
                    @endif

                @empty
                    <div class="py-20 text-center">
                        <div
                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="fa-solid fa-clipboard-check text-2xl"></i>
                        </div>
                        <p class="text-slate-400 font-medium">No tasks found</p>
                        <p class="text-slate-300 text-sm mt-1">Create a new task to get started</p>
                    </div>
                @endforelse
            </div>

            {{-- AJAX Pagination --}}
            <div id="tasks-pagination" class="px-4 py-3 border-t border-slate-100"></div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
    RIGHT-SIDE DETAIL DRAWER (Asana style)
    ═══════════════════════════════════════════════════════════ --}}

    {{-- Backdrop --}}
    <div id="drawer-backdrop" onclick="closeDrawer()"
        class="fixed inset-0 bg-black/20 backdrop-blur-[2px] z-40 hidden transition-opacity duration-300 opacity-0"></div>

    {{-- Drawer Panel --}}
    <div id="task-drawer"
        class="fixed top-0 right-0 h-full w-full max-w-2xl bg-white shadow-2xl z-50 flex flex-col transform translate-x-full transition-transform duration-300 ease-out">

        {{-- Drawer Top Bar --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 flex-shrink-0 bg-white">
            <div class="flex items-center gap-3">
                {{-- Normal Task Actions --}}
                <div id="task-normal-actions"
                    class="flex items-center gap-3 @if($viewMode === 'submitted' || $viewMode === 'pending') hidden @endif">
                    <button onclick="openModal('updateStatusModal')" id="btn-update-status"
                        class="flex items-center gap-2 px-4 py-2 bg-[#004F68] hover:bg-[#00384a] text-white text-sm font-bold rounded-xl transition-all hover:scale-105 active:scale-95">
                        <i class="fa-solid fa-check text-xs"></i>
                        Update Status
                    </button>
                    <button onclick="openSubtaskModal(activeTaskId)"
                        class="flex items-center gap-2 px-3 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold rounded-xl transition-all">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Subtask
                    </button>
                    <button onclick="markComplete()" id="btn-mark-complete"
                        class="flex items-center gap-2 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 hover:text-emerald-800 text-sm font-semibold rounded-xl transition-all hover:scale-105 active:scale-95">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        Mark Complete
                    </button>
                </div>

                {{-- Approval Actions (Visible to Line Manager) --}}
                <div id="task-approval-actions" class="hidden flex items-center gap-3">
                    <button onclick="openAssignModal()"
                        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all hover:scale-105 active:scale-95">
                        <i class="fa-solid fa-check-double text-xs"></i>
                        Approve & Assign
                    </button>
                    <button onclick="openRejectModal()"
                        class="flex items-center gap-2 px-4 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 text-sm font-bold rounded-xl transition-all">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        Reject
                    </button>
                </div>

                {{-- Status for Submitter --}}
                <div id="task-submitted-status" class="@if($viewMode !== 'submitted') hidden @endif">
                    <span
                        class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-2 rounded-xl border border-amber-200">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Awaiting line manager approval
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span id="drawer-task-id" class="text-xs font-mono text-slate-400"></span>
                <button onclick="closeDrawer()"
                    class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- Drawer Content (scrollable) --}}
        <div class="flex-1 overflow-y-auto">

            {{-- Task Title + Badges --}}
            <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2 mb-3">
                    <span id="drawer-priority" class="text-[11px] font-bold px-2.5 py-1 rounded-lg"></span>
                    <span id="drawer-status" class="text-[11px] font-bold px-2.5 py-1 rounded-lg"></span>
                </div>
                <h1 id="drawer-title" class="text-2xl font-bold text-slate-900 leading-snug mb-4"></h1>

                {{-- Meta Fields Row --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="drawer-meta-card">
                        <span class="drawer-meta-label">Assigned By</span>
                        <span id="drawer-assigned-by" class="drawer-meta-value"></span>
                    </div>
                    <div class="drawer-meta-card">
                        <span class="drawer-meta-label">Assigned To</span>
                        <span id="drawer-assigned-to" class="drawer-meta-value"></span>
                    </div>
                    <div class="drawer-meta-card">
                        <span class="drawer-meta-label">Due Date</span>
                        <span id="drawer-due-date" class="drawer-meta-value"></span>
                    </div>
                    <div class="drawer-meta-card">
                        <span class="drawer-meta-label">Progress</span>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div id="drawer-progress-bar"
                                    class="h-full bg-[#004F68] rounded-full transition-all duration-500" style="width:0%">
                                </div>
                            </div>
                            <span id="drawer-progress-text" class="text-xs font-bold text-slate-700">0%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Description</h3>
                <div id="drawer-desc" class="text-slate-700 text-sm leading-relaxed prose prose-slate max-w-none"></div>
            </div>

            {{-- Attachment --}}
            <div id="drawer-attachment-wrap" class="px-6 py-5 border-b border-slate-100 hidden">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                    <i class="fa-solid fa-paperclip mr-1"></i> Attachment
                </h3>
                <a id="drawer-attachment-link" href="#" target="_blank" class="group block">
                    <div
                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 group-hover:border-[#004F68]/20 group-hover:bg-[#004F68]/5 transition-all">
                        <div id="drawer-attachment-icon-box"
                            class="w-10 h-10 rounded-lg bg-white shadow-sm text-[#004F68] flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                            <i id="drawer-attachment-icon" class="fa-solid fa-file"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p id="drawer-attachment-name"
                                class="text-sm font-bold text-slate-700 truncate group-hover:text-[#004F68]">File</p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Click to View /
                                Download</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- ── Comments + Activity Tabs ────────────────────────────── --}}
            <div class="px-6 py-5 border-t border-slate-100">

                {{-- Tab Headers --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
                        <button onclick="switchTab('comments')" id="tab-comments"
                            class="drawer-tab tab-active px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                            <i class="fa-solid fa-comment-dots mr-1"></i> Comments
                        </button>
                        <button onclick="switchTab('activity')" id="tab-activity"
                            class="drawer-tab px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                            <i class="fa-solid fa-clock-rotate-left mr-1"></i> All Activity
                        </button>
                    </div>
                    <span id="comments-count"
                        class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-lg">0</span>
                </div>

                {{-- Comments Panel --}}
                <div id="panel-comments">
                    <div id="drawer-comments" class="space-y-4 mb-5 max-h-72 overflow-y-auto">
                        <p class="text-sm text-slate-400 italic text-center py-4">No comments yet. Be the first!</p>
                    </div>

                    {{-- Compose Box --}}
                    <div class="flex gap-3 items-start">
                        {{-- Current user avatar --}}
                        <div
                            class="w-8 h-8 rounded-full bg-[#004F68] text-white flex items-center justify-center text-xs font-bold flex-shrink-0 mt-1">
                            {{ strtoupper(substr(Auth::user()->employee->first_name ?? Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div
                                class="border border-slate-200 rounded-xl overflow-hidden focus-within:border-[#004F68]/40 focus-within:ring-2 focus-within:ring-[#004F68]/10 transition-all">
                                <textarea id="comment-input" placeholder="Write a comment…" rows="2"
                                    class="w-full px-4 pt-3 pb-2 text-sm text-slate-700 placeholder-slate-300 resize-none outline-none border-0 bg-white"
                                    oninput="autoResize(this)"></textarea>
                                {{-- Toolbar --}}
                                <div class="flex items-center justify-between px-3 pb-2 bg-white">
                                    <div class="flex items-center gap-2 text-slate-400">
                                        <button type="button" title="Emoji"
                                            class="w-7 h-7 rounded-lg hover:bg-slate-100 flex items-center justify-center transition-colors text-sm">
                                            <i class="fa-regular fa-face-smile"></i>
                                        </button>
                                        <button type="button" title="Mention"
                                            class="w-7 h-7 rounded-lg hover:bg-slate-100 flex items-center justify-center transition-colors text-sm font-bold">
                                            @
                                        </button>
                                        <button type="button" title="Bold"
                                            class="w-7 h-7 rounded-lg hover:bg-slate-100 flex items-center justify-center transition-colors text-sm font-bold">
                                            B
                                        </button>
                                    </div>
                                    <button onclick="submitComment()" id="btn-comment-submit"
                                        class="flex items-center gap-1.5 px-4 py-1.5 bg-[#004F68] hover:bg-[#00384a] text-white text-xs font-bold rounded-lg transition-all hover:scale-105 active:scale-95 disabled:opacity-50">
                                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                                        Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Activity Panel (hidden by default) --}}
                <div id="panel-activity" class="hidden">
                    <div id="drawer-logs"
                        class="space-y-4 border-l-2 border-slate-100 ml-2 pl-5 relative max-h-96 overflow-y-auto">
                        <p class="text-sm text-slate-400 italic">Select a task to view activity.</p>
                    </div>
                </div>
            </div>

            {{-- ── Collaborators Footer ──────────────────────────────── --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center gap-3 flex-shrink-0">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Collaborators</span>
                <div id="drawer-collaborators" class="flex items-center gap-1">
                    {{-- JS fills this in --}}
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════
    MODALS
    ═══════════════════════════════════════════════════════════ --}}

    {{-- Create Task Modal --}}
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
                            Task will be sent to your <strong class="text-amber-600">line manager</strong> for review &amp;
                            assignment.
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
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Assign
                            To</label>
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
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Start
                            Schedule</label>
                        <input type="date" name="task_assigned_date" class="premium-input w-full text-sm"
                            value="{{ date('Y-m-d') }}" required>
                        <select name="start_time" class="premium-input w-full text-sm">
                            <option value="">Start Time (Optional)</option>
                            @for($i = 6; $i <= 22; $i++)
                                @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $h }}:00:00">{{ $h }}:00</option>
                            @endfor
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Due
                            Deadline</label>
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
                        <i class="fa-solid fa-paperclip text-indigo-500 mr-1"></i> Attachment <span
                            class="text-slate-300">(Optional)</span>
                    </label>
                    <input type="file" name="task_attachment" id="task_attachment"
                        class="premium-input w-full px-4 py-3 text-sm">
                    <div id="task-attachment-preview"></div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('newTaskModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 bg-[#004F68] hover:bg-[#00384a] text-white font-bold rounded-xl shadow-lg transition-all hover:scale-105">Create
                        Task</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Update Status Modal --}}
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
                        <span id="update-progress-value"
                            class="text-xs font-bold text-[#004F68] bg-[#004F68]/10 px-2 py-0.5 rounded-lg">0%</span>
                    </div>
                    <input type="range" name="task_progress" id="update-task-progress" min="0" max="100" step="1"
                        class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-[#004F68]"
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
                        class="px-6 py-3 bg-[#004F68] hover:bg-[#00384a] text-white font-bold rounded-xl transition-all hover:scale-105">Update
                        Status</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Styles ───────────────────────────────────────────────────── --}}
    <style>
        .asana-page {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            min-height: calc(100vh - 170px);
        }

        /* Top Bar */
        .asana-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 12px 20px;
            border-bottom: 1px solid #f1f5f9;
            background: white;
            flex-shrink: 0;
            flex-wrap: wrap;
        }

        /* Tabs */
        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .tab-btn:hover {
            background: white;
            color: #334155;
        }

        .tab-active {
            background: white !important;
            color: #004F68 !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        .badge-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            font-size: 9px;
            font-weight: 800;
            color: white;
        }

        /* List Area */
        .asana-list-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* List Header */
        .asana-list-header {
            display: grid;
            grid-template-columns: 1fr 150px 130px 110px 110px 110px;
            padding: 8px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            gap: 8px;
            flex-shrink: 0;
        }

        .asana-list-header>div {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
        }

        /* Task Row */
        .asana-task-row {
            display: grid;
            grid-template-columns: 1fr 150px 130px 110px 110px 110px;
            align-items: center;
            padding: 10px 20px;
            border-bottom: 1px solid #f8fafc;
            gap: 8px;
            cursor: pointer;
            transition: background 0.15s;
            position: relative;
        }

        .asana-task-row:hover {
            background: #f8fafc;
        }

        .asana-task-row.active-row {
            background: #f0f9ff;
            border-left: 3px solid #004F68;
        }

        .asana-subtask-row {
            background: #fafafa;
        }

        .asana-subtask-row:hover {
            background: #f0f4f8;
        }

        .sub-connector {
            position: absolute;
            left: 24px;
            width: 16px;
            height: 1px;
            background: #e2e8f0;
        }

        /* Columns */
        .col-name {
            min-width: 0;
        }

        .col-assignee,
        .col-due,
        .col-priority,
        .col-status,
        .col-progress {
            min-width: 0;
        }

        /* Drawer Meta Cards */
        .drawer-meta-card {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 10px 12px;
        }

        .drawer-meta-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .drawer-meta-value {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Overflow scroll */
        #tasks-container {
            overflow-y: auto;
            flex: 1;
        }

        .asana-list-area {
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .asana-list-header {
                display: none;
            }

            .asana-task-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .col-assignee,
            .col-due,
            .col-priority,
            .col-status,
            .col-progress {
                display: none;
            }

            #task-drawer {
                max-width: 100%;
            }
        }

        /* Drawer tabs */
        .drawer-tab {
            color: #64748b;
            cursor: pointer;
            background: transparent;
            border: none;
        }

        .drawer-tab:hover {
            color: #334155;
            background: white;
        }

        .drawer-tab.tab-active {
            background: white;
            color: #004F68;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }
    </style>

    @push('scripts')
        <script src="{{ asset('js/ajax-pagination.js') }}"></script>
        <script>
            let activeTaskId = null;

            // ── Drawer Controls ──────────────────────────────────────────
            function openDrawer() {
                const drawer = document.getElementById('task-drawer');
                const backdrop = document.getElementById('drawer-backdrop');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    drawer.classList.remove('translate-x-full');
                }, 10);
                document.body.style.overflow = 'hidden';
            }

            function closeDrawer() {
                const drawer = document.getElementById('task-drawer');
                const backdrop = document.getElementById('drawer-backdrop');
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
                document.body.style.overflow = '';
                document.querySelectorAll('.asana-task-row').forEach(r => r.classList.remove('active-row'));
                activeTaskId = null;
            }

            // ── Load Task ────────────────────────────────────────────────
            async function loadTask(id) {
                activeTaskId = id;
                document.getElementById('update-task-id').value = id;

                // Highlight row
                document.querySelectorAll('.asana-task-row').forEach(r => r.classList.remove('active-row'));
                const row = document.getElementById(`task-item-${id}`);
                if (row) row.classList.add('active-row');

                openDrawer();

                // Show loading state
                document.getElementById('drawer-title').innerText = 'Loading…';
                document.getElementById('drawer-desc').innerHTML = '<p class="text-slate-300 animate-pulse">Fetching task details…</p>';
                document.getElementById('drawer-logs').innerHTML = '';

                try {
                    const resp = await fetch(`{{ url('hr/tasks') }}/${id}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const result = await resp.json();

                    if (!result.success) return;
                    const task = result.data;

                    // ID
                    document.getElementById('drawer-task-id').innerText = `TASK-${task.task_id}`;

                    // Title
                    document.getElementById('drawer-title').innerText = task.task_title;

                    // Description
                    document.getElementById('drawer-desc').innerHTML = task.task_description || '<em class="text-slate-400">No description provided.</em>';

                    // Priority badge
                    const pEl = document.getElementById('drawer-priority');
                    pEl.innerText = task.priority?.priority_name ?? '—';
                    pEl.style.background = `#${task.priority?.priority_color ?? 'ccc'}18`;
                    pEl.style.color = `#${task.priority?.priority_color ?? '999'}`;

                    // Status badge
                    const sEl = document.getElementById('drawer-status');
                    sEl.innerText = task.status?.status_name ?? '—';
                    sEl.style.background = `#${task.status?.status_color ?? 'ccc'}18`;
                    sEl.style.color = `#${task.status?.status_color ?? '999'}`;

                    // Reset Mark Complete button based on current status
                    const btnMc = document.getElementById('btn-mark-complete');
                    if (btnMc) {
                        const isDone = task.status_id == 4 || task.task_progress >= 100;
                        if (isDone) {
                            btnMc.innerHTML = '<i class="fa-solid fa-circle-check text-xs"></i> Completed!';
                            btnMc.className = 'flex items-center gap-2 px-3 py-2 bg-emerald-500 text-white text-sm font-semibold rounded-xl cursor-default';
                            btnMc.disabled = true;
                        } else {
                            btnMc.innerHTML = '<i class="fa-solid fa-circle-check text-xs"></i> Mark Complete';
                            btnMc.className = 'flex items-center gap-2 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 hover:text-emerald-800 text-sm font-semibold rounded-xl transition-all hover:scale-105 active:scale-95';
                            btnMc.disabled = false;
                        }
                    }

                    // Meta
                    document.getElementById('drawer-assigned-by').innerText = task.assigned_by ? `${task.assigned_by.first_name} ${task.assigned_by.last_name}` : '—';
                    document.getElementById('drawer-assigned-to').innerText = task.assigned_to ? `${task.assigned_to.first_name} ${task.assigned_to.last_name}` : '—';
                    document.getElementById('drawer-due-date').innerText = task.task_due_date ? new Date(task.task_due_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '—';

                    // Progress
                    const prog = task.task_progress || 0;
                    document.getElementById('drawer-progress-bar').style.width = `${prog}%`;
                    document.getElementById('drawer-progress-text').innerText = `${prog}%`;

                    // ── Approval Logic Toggles ──────────────────────────
                    const currentEmpId = {{ Auth::user()->employee ? Auth::user()->employee->employee_id : 0 }};
                    const isPending = task.pending_line_manager_id && task.pending_line_manager_id != 0;
                    const canApprove = isPending && task.pending_line_manager_id == currentEmpId;
                    const isSubmitter = isPending && task.assigned_by == currentEmpId;

                    document.getElementById('task-approval-actions').classList.toggle('hidden', !canApprove);
                    document.getElementById('task-submitted-status').classList.toggle('hidden', !isSubmitter);
                    document.getElementById('task-normal-actions').classList.toggle('hidden', isPending);

                    // Status modal defaults
                    document.getElementById('update-status-id').value = task.status_id;
                    const slider = document.getElementById('update-task-progress');
                    if (slider) {
                        slider.value = prog;
                        document.getElementById('update-progress-value').innerText = prog + '%';
                    }

                    // Attachment
                    const attachWrap = document.getElementById('drawer-attachment-wrap');
                    if (task.task_attachment) {
                        attachWrap.classList.remove('hidden');
                        document.getElementById('drawer-attachment-link').href = `{{ url('/') }}/${task.task_attachment}`;
                        const parts = task.task_attachment.split('/');
                        const fname = parts[parts.length - 1].replace(/^\d+_/, '');
                        document.getElementById('drawer-attachment-name').textContent = fname;
                        const ext = fname.split('.').pop().toLowerCase();
                        const icons = { pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word', xls: 'fa-file-excel', xlsx: 'fa-file-excel', jpg: 'fa-file-image', jpeg: 'fa-file-image', png: 'fa-file-image', zip: 'fa-file-archive' };
                        document.getElementById('drawer-attachment-icon').className = `fa-solid ${icons[ext] || 'fa-file'}`;
                    } else {
                        attachWrap.classList.add('hidden');
                    }

                    // Logs
                    renderLogs(task.logs);

                    // Comments
                    renderComments(task.comments || []);

                    // Collaborators
                    renderCollaborators(task.assigned_by, task.assigned_to);

                } catch (e) { console.error(e); }
            }

            function renderLogs(logs) {
                const container = document.getElementById('drawer-logs');
                if (!logs || !logs.length) {
                    container.innerHTML = '<p class="text-sm text-slate-400 italic">No activity logs yet.</p>';
                    return;
                }
                container.innerHTML = logs.map(log => {
                    const date = new Date(log.log_date).toLocaleString();
                    return `
                                                                                                    <div class="relative">
                                                                                                        <div class="absolute -left-[21px] top-1.5 w-3 h-3 rounded-full bg-[#004F68]/20 border-2 border-white ring-1 ring-[#004F68]/20"></div>
                                                                                                        <div class="space-y-1">
                                                                                                            <div class="flex justify-between items-center text-xs">
                                                                                                                <span class="font-bold text-slate-700">${log.log_action}</span>
                                                                                                                <span class="text-slate-400 font-mono text-[10px]">${date}</span>
                                                                                                            </div>
                                                                                                            <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed">${log.log_remark}</p>
                                                                                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">By: ${log.logger ? log.logger.first_name : 'System'}</div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                `;
                }).join('');
            }

            // ── Comments ──────────────────────────────────────────────
            function renderComments(comments) {
                const container = document.getElementById('drawer-comments');
                const countEl = document.getElementById('comments-count');
                if (countEl) countEl.innerText = comments.length;

                if (!comments || !comments.length) {
                    container.innerHTML = '<p class="text-sm text-slate-400 italic text-center py-4">No comments yet. Be the first!</p>';
                    return;
                }

                container.innerHTML = comments.map(c => {
                    const name = c.commenter ? `${c.commenter.first_name} ${c.commenter.last_name}` : 'Unknown';
                    const initials = name.charAt(0).toUpperCase();
                    const date = new Date(c.created_at).toLocaleString('en-GB', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
                    return `
                                                                                                <div class="flex gap-3 items-start group/comment">
                                                                                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">${initials}</div>
                                                                                                    <div class="flex-1 min-w-0">
                                                                                                        <div class="flex items-baseline gap-2 mb-1">
                                                                                                            <span class="text-xs font-bold text-slate-700">${name}</span>
                                                                                                            <span class="text-[10px] text-slate-400">${date}</span>
                                                                                                        </div>
                                                                                                        <div class="text-sm text-slate-700 bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 leading-relaxed">${c.comment_body}</div>
                                                                                                    </div>
                                                                                                </div>`;
                }).join('');

                // Scroll to bottom of comments
                container.scrollTop = container.scrollHeight;
            }

            function renderCollaborators(assignedBy, assignedTo) {
                const el = document.getElementById('drawer-collaborators');
                if (!el) return;
                const people = [];
                if (assignedBy) people.push(assignedBy);
                if (assignedTo && assignedTo.employee_id !== assignedBy?.employee_id) people.push(assignedTo);

                el.innerHTML = people.map(p => {
                    const name = `${p.first_name} ${p.last_name}`;
                    const init = name.charAt(0).toUpperCase();
                    return `<div class="w-7 h-7 rounded-full bg-[#004F68]/20 text-[#004F68] flex items-center justify-center text-[10px] font-bold ring-2 ring-white" title="${name}">${init}</div>`;
                }).join('');
            }

            // ── Tab Switch ────────────────────────────────────────────
            function switchTab(tab) {
                document.getElementById('panel-comments').classList.toggle('hidden', tab !== 'comments');
                document.getElementById('panel-activity').classList.toggle('hidden', tab !== 'activity');
                document.getElementById('tab-comments').classList.toggle('tab-active', tab === 'comments');
                document.getElementById('tab-activity').classList.toggle('tab-active', tab === 'activity');
                // Set correct bg/text for tabs
                [['tab-comments', 'comments'], ['tab-activity', 'activity']].forEach(([id, t]) => {
                    const el = document.getElementById(id);
                    if (tab === t) {
                        el.style.background = 'white';
                        el.style.color = '#004F68';
                        el.style.boxShadow = '0 1px 4px rgba(0,0,0,0.08)';
                    } else {
                        el.style.background = '';
                        el.style.color = '#64748b';
                        el.style.boxShadow = '';
                    }
                });
            }

            // ── Submit Comment ────────────────────────────────────────
            async function submitComment() {
                const input = document.getElementById('comment-input');
                const body = input.value.trim();
                if (!body || !activeTaskId) return;

                const btn = document.getElementById('btn-comment-submit');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-[10px]"></i> Sending…';

                try {
                    const fd = new FormData();
                    fd.append('comment_body', body);
                    const res = await fetch(`{{ url('hr/tasks') }}/${activeTaskId}/comment`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: fd
                    });
                    const result = await res.json();
                    if (result.success) {
                        input.value = '';
                        input.style.height = '';
                        // Append new comment
                        const container = document.getElementById('drawer-comments');
                        const c = result.comment;
                        const name = c.commenter ? `${c.commenter.first_name} ${c.commenter.last_name}` : 'You';
                        const init = name.charAt(0).toUpperCase();
                        const date = new Date(c.created_at).toLocaleString('en-GB', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });

                        // Remove the 'no comments' placeholder if present
                        const placeholder = container.querySelector('p.italic');
                        if (placeholder) placeholder.remove();

                        const div = document.createElement('div');
                        div.className = 'flex gap-3 items-start';
                        div.innerHTML = `
                                                                                                    <div class="w-7 h-7 rounded-full bg-[#004F68] text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">${init}</div>
                                                                                                    <div class="flex-1 min-w-0">
                                                                                                        <div class="flex items-baseline gap-2 mb-1">
                                                                                                            <span class="text-xs font-bold text-slate-700">${name}</span>
                                                                                                            <span class="text-[10px] text-slate-400">${date}</span>
                                                                                                        </div>
                                                                                                        <div class="text-sm text-slate-700 bg-blue-50 border border-blue-100 rounded-xl px-4 py-2.5 leading-relaxed">${c.comment_body}</div>
                                                                                                    </div>`;
                        container.appendChild(div);
                        container.scrollTop = container.scrollHeight;

                        // Update count
                        const countEl = document.getElementById('comments-count');
                        if (countEl) countEl.innerText = parseInt(countEl.innerText || 0) + 1;
                    }
                } catch (e) { console.error(e); }

                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane text-[10px]"></i> Comment';
            }

            // ── Ctrl+Enter to submit ──────────────────────────────────
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && (e.ctrlKey || e.metaKey) && document.getElementById('comment-input') === document.activeElement) {
                    submitComment();
                }
            });

            function autoResize(el) {
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 160) + 'px';
            }

            // ── Mark as Complete ──────────────────────────────────────
            async function markComplete() {
                if (!activeTaskId) return;

                const btn = document.getElementById('btn-mark-complete');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Completing…';

                try {
                    const fd = new FormData();
                    fd.append('task_id', activeTaskId);
                    fd.append('status_id', 4);           // Done / Completed
                    fd.append('task_progress', 100);
                    fd.append('log_remark', 'Task marked as complete.');

                    const res = await fetch("{{ route('hr.tasks.status.update') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });
                    const result = await res.json();

                    if (result.success) {
                        // Update progress bar in drawer
                        document.getElementById('drawer-progress-bar').style.width = '100%';
                        document.getElementById('drawer-progress-text').innerText = '100%';

                        // Update status badge
                        const sEl = document.getElementById('drawer-status');
                        sEl.innerText = 'Done';
                        sEl.style.background = '#22c55e18';
                        sEl.style.color = '#16a34a';

                        // Change button to "Completed" state
                        btn.innerHTML = '<i class="fa-solid fa-circle-check text-xs"></i> Completed!';
                        btn.className = 'flex items-center gap-2 px-3 py-2 bg-emerald-500 text-white text-sm font-semibold rounded-xl cursor-default';
                        btn.disabled = true;

                        // Show a subtle toast
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'success', title: 'Task Completed!', text: 'Progress set to 100% and status updated to Done.', timer: 2000, showConfirmButton: false, confirmButtonColor: '#004F68' });
                        }
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-circle-check text-xs"></i> Mark Complete';
                    }
                } catch (e) {
                    console.error(e);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-circle-check text-xs"></i> Mark Complete';
                }
            }

            // ── Ajax Pagination ──────────────────────────────────────────
            window.addEventListener('DOMContentLoaded', () => {
                window.ajaxPagination = new AjaxPagination({
                    endpoint: "{{ route('hr.tasks.data', ['view_mode' => $viewMode, 'status_id' => $statusId]) }}",
                    containerSelector: '#tasks-container',
                    paginationSelector: '#tasks-pagination',
                    renderCallback: function (tasks) {
                        const container = document.querySelector('#tasks-container');
                        if (!tasks.length) {
                            container.innerHTML = `<div class="py-20 text-center"><div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300"><i class="fa-solid fa-clipboard-check text-2xl"></i></div><p class="text-slate-400 font-medium">No tasks found</p></div>`;
                            return;
                        }
                        const vm = "{{ $viewMode }}";
                        let html = '';
                        tasks.forEach(task => {
                            const person = vm == 'assigned_to' ? task.assigned_by : task.assigned_to;
                            const initials = (person?.first_name ?? 'U').charAt(0).toUpperCase();
                            const dueDate = task.task_due_date ? new Date(task.task_due_date) : null;
                            const isOverdue = dueDate && dueDate < new Date();
                            html += `
                                                                                                        <div onclick="loadTask(${task.task_id})" id="task-item-${task.task_id}"
                                                                                                            class="asana-task-row group ${activeTaskId == task.task_id ? 'active-row' : ''}" data-task-id="${task.task_id}">
                                                                                                            <div class="col-name">
                                                                                                                <div class="flex items-center gap-3">
                                                                                                                    <button onclick="event.stopPropagation()" class="task-check flex-shrink-0 w-5 h-5 rounded-full border-2 border-slate-300 hover:border-[#004F68] transition-colors flex items-center justify-center">
                                                                                                                        <i class="fa-solid fa-check text-[9px] text-transparent hover:text-[#004F68] transition-colors"></i>
                                                                                                                    </button>
                                                                                                                    <span class="font-medium text-slate-800 group-hover:text-[#004F68] transition-colors line-clamp-1">${task.task_title}</span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-assignee">
                                                                                                                <div class="flex items-center gap-2">
                                                                                                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold">${initials}</div>
                                                                                                                    <span class="text-sm text-slate-600 truncate">${person?.first_name ?? '—'}</span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-due">
                                                                                                                ${dueDate ? `<span class="text-sm font-medium ${isOverdue ? 'text-red-500' : 'text-slate-500'}">${dueDate.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}</span>` : '<span class="text-slate-300">—</span>'}
                                                                                                            </div>
                                                                                                            <div class="col-priority">
                                                                                                                <span class="text-[11px] font-bold px-2 py-1 rounded-lg" style="background:#${task.priority?.priority_color ?? 'ccc'}18;color:#${task.priority?.priority_color ?? '999'}">${task.priority?.priority_name ?? '—'}</span>
                                                                                                            </div>
                                                                                                            <div class="col-status">
                                                                                                                <span class="text-[11px] font-bold px-2 py-1 rounded-lg" style="background:#${task.status?.status_color ?? 'ccc'}18;color:#${task.status?.status_color ?? '999'}">${task.status?.status_name ?? '—'}</span>
                                                                                                            </div>
                                                                                                            <div class="col-progress">
                                                                                                                <div class="flex items-center gap-2">
                                                                                                                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full"><div class="h-full bg-[#004F68] rounded-full" style="width:${task.task_progress ?? 0}%"></div></div>
                                                                                                                    <span class="text-xs text-slate-500 w-8 text-right">${task.task_progress ?? 0}%</span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>`;
                        });
                        container.innerHTML = html;
                    }
                });

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

            // ── Status change auto-fill ─────────────────────────────────
            function onStatusChange(select) {
                const name = (select.options[select.selectedIndex].dataset.name || '').toLowerCase();
                const id = parseInt(select.value);
                if (id === 4 || name.includes('done') || name.includes('complet')) {
                    const s = document.getElementById('update-task-progress');
                    if (s) { s.value = 100; document.getElementById('update-progress-value').innerText = '100%'; }
                }
            }

            // ── Save Task ────────────────────────────────────────────────
            async function saveTask(e) {
                e.preventDefault();
                try {
                    const res = await fetch("{{ route('hr.tasks.store') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(e.target)
                    });
                    const result = await res.json();
                    if (result.success) { closeModal('newTaskModal'); window.location.reload(); }
                } catch (err) { console.error(err); }
            }

            // ── Update Status ────────────────────────────────────────────
            async function updateTaskStatus(e) {
                e.preventDefault();
                try {
                    const res = await fetch("{{ route('hr.tasks.status.update') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(e.target)
                    });
                    const result = await res.json();
                    if (result.success) { closeModal('updateStatusModal'); window.location.reload(); }
                } catch (err) { console.error(err); }
            }

            // ── Modal Helpers ────────────────────────────────────────────
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
        <script src="{{ asset('js/attachment-preview.js') }}"></script>
        <script>
            window.addEventListener('load', () => {
                if (window.initAttachmentPreview) {
                    window.initAttachmentPreview({ inputSelector: '#task_attachment', containerSelector: '#task-attachment-preview' });
                }
            });
            const _attachInput = document.getElementById('task_attachment');
            if (_attachInput) {
                _attachInput.addEventListener('change', function () {
                    if (this.files?.[0]?.size > 10 * 1024 * 1024) {
                        Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Max 10MB.', confirmButtonColor: '#004F68' });
                        this.value = '';
                        document.getElementById('task-attachment-preview').innerHTML = '';
                    }
                });
            }
        </script>
    @endpush

    {{-- Resubmit Modal --}}
    @if($viewMode === 'rejected')
        <div class="modal" id="resubmitModal">
            <div class="modal-backdrop" onclick="closeModal('resubmitModal')"></div>
            <div class="modal-content max-w-lg p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-display font-bold text-premium">Edit &amp; Resubmit Task</h2>
                        <p class="text-sm text-amber-600 mt-1"><i class="fa-solid fa-rotate-right mr-1"></i> Correct and send
                            for line manager review</p>
                    </div>
                    <button onclick="closeModal('resubmitModal')"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
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
                        <textarea id="resubmit-desc" rows="4" class="premium-input w-full px-4 py-3 text-sm"
                            placeholder="Describe the task..."></textarea>
                    </div>
                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('resubmitModal')"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit"
                            class="px-6 py-3 bg-[#004F68] text-white font-bold rounded-xl hover:scale-105 transition-all">
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
                        Swal.fire({ icon: 'success', title: 'Resubmitted!', text: result.message, timer: 2000, showConfirmButton: false }).then(() => window.location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: result.message });
                    }
                } catch (err) { Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to resubmit.' }); }
            }
        </script>
    @endif

    {{-- Approve & Assign Modal --}}
        <div class="modal" id="assignModal">
            <div class="modal-backdrop" onclick="closeModal('assignModal')"></div>
            <div class="modal-content max-w-lg p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-display font-bold text-premium">Approve & Assign Task</h2>
                        <p class="text-sm text-slate-500 mt-1">Review the task details and assign it to an employee to activate it.</p>
                    </div>
                    <button onclick="closeModal('assignModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form onsubmit="submitAssign(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Assign To Employee</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            <select id="assign-to-id" class="premium-input w-full pl-8 pr-4 py-3 text-sm appearance-none" required>
                                <option value="">Select Employee...</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('assignModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:scale-105 transition-all shadow-lg shadow-emerald-200">
                            <i class="fa-solid fa-check-double mr-2"></i>Approve & Activate
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Rejection Modal --}}
        <div class="modal" id="rejectModal">
            <div class="modal-backdrop" onclick="closeModal('rejectModal')"></div>
            <div class="modal-content max-w-lg p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-display font-bold text-premium">Reject Task</h2>
                        <p class="text-sm text-rose-600 mt-1">Provide a reason for rejection so the creator can correct it.</p>
                    </div>
                    <button onclick="closeModal('rejectModal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form onsubmit="submitReject(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rejection Reason</label>
                        <textarea id="reject-reason" rows="4" class="premium-input w-full px-4 py-3 text-sm" placeholder="Why is this task being rejected?" required></textarea>
                    </div>
                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('rejectModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-rose-600 text-white font-bold rounded-xl hover:scale-105 transition-all shadow-lg shadow-rose-200">
                            <i class="fa-solid fa-xmark mr-2"></i>Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openAssignModal() {
                if (!activeTaskId) return;
                openModal('assignModal');
            }
            function openRejectModal() {
                if (!activeTaskId) return;
                openModal('rejectModal');
            }

            async function submitAssign(e) {
                e.preventDefault();
                const employeeId = document.getElementById('assign-to-id').value;
                const formData = new FormData();
                formData.append('assigned_to', employeeId);
                try {
                    const res = await fetch(`{{ url('hr/tasks') }}/${activeTaskId}/assign`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const result = await res.json();
                    if (result.success) {
                        closeModal('assignModal');
                        Swal.fire({ icon: 'success', title: 'Task Approved!', text: result.message, timer: 2000, showConfirmButton: false }).then(() => window.location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: result.message });
                    }
                } catch (err) { Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to assign task.' }); }
            }

            async function submitReject(e) {
                e.preventDefault();
                const reason = document.getElementById('reject-reason').value;
                const formData = new FormData();
                formData.append('rejection_reason', reason);
                try {
                    const res = await fetch(`{{ url('hr/tasks') }}/${activeTaskId}/reject`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const result = await res.json();
                    if (result.success) {
                        closeModal('rejectModal');
                        Swal.fire({ icon: 'success', title: 'Task Rejected', text: result.message, timer: 2000, showConfirmButton: false }).then(() => window.location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: result.message });
                    }
                } catch (err) { Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to reject task.' }); }
            }
        </script>
@endsection