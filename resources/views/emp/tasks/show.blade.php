@extends('layouts.app')

@section('title', 'Task Details')
@section('subtitle', 'View and manage task: ' . $task->task_title)

@section('content')
    <div class="space-y-6">

        <!-- Summary Badges -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">System ID</p>
                    <p class="text-2xl font-bold text-slate-700">{{ $task->task_id }}</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-brand-dark group-hover:text-white transition-colors">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Priority</p>
                    <p class="text-xl font-bold" style="color: #{{ $task->priority->priority_color ?? '000' }}">
                        {{ $task->priority->priority_name ?? 'N/A' }}
                    </p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-brand-dark group-hover:text-white transition-colors">
                    <i class="fa-solid fa-list-ol"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status</p>
                    <p class="text-xl font-bold" style="color: #{{ $task->status->status_color ?? '000' }}">
                        {{ $task->status->status_name ?? 'N/A' }}
                    </p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-brand-dark group-hover:text-white transition-colors">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Progress</p>
                    <p class="text-2xl font-bold text-brand-dark">{{ $task->task_progress }}%</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-brand-dark group-hover:bg-brand-dark group-hover:text-white transition-colors">
                    {{ $task->task_progress }}%
                </div>
            </div>

        </div>

        <!-- Tabs Container -->
        <div x-data="{ activeTab: 'details' }" class="premium-card overflow-hidden">
            <div class="flex border-b border-slate-100">
                <button @click="activeTab = 'details'"
                    :class="activeTab === 'details' ? 'border-brand-dark text-brand-dark' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    Task Details
                </button>
                <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'border-brand-dark text-brand-dark' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    Logs & History
                </button>
            </div>

            <!-- Details Tab -->
            <div x-show="activeTab === 'details'" class="p-6 space-y-8 animate-fade-in-up">

                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Title</h3>
                    <p class="text-lg font-medium text-slate-800">{{ $task->task_title }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Description</h3>
                    <div
                        class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-slate-700 leading-relaxed whitespace-pre-wrap">
                        {{ $task->task_description }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-100 pt-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Assigned By</h3>
                        <div class="flex items-center gap-3 mt-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($task->assignedBy->first_name ?? 'U', 0, 1) }}
                            </div>
                            <p class="font-medium text-slate-800">
                                {{ $task->assignedBy->first_name ?? 'Unknown' }} {{ $task->assignedBy->last_name ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Assigned To</h3>
                        <div class="flex items-center gap-3 mt-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($task->assignedTo->first_name ?? 'U', 0, 1) }}
                            </div>
                            <p class="font-medium text-slate-800">
                                {{ $task->assignedTo->first_name ?? 'Unknown' }} {{ $task->assignedTo->last_name ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-100 pt-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Assigned Date</h3>
                        <p class="text-slate-700 mt-2">
                            {{ $task->task_assigned_date ? $task->task_assigned_date->format('Y-m-d H:i') : 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Due Date</h3>
                        <p class="text-slate-700 mt-2 font-bold">
                            {{ $task->task_due_date ? $task->task_due_date->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Progress Bar Info -->
                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Current Progress
                        ({{ $task->task_progress }}%)</h3>
                    <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-brand-dark to-brand-light transition-all duration-500"
                            style="width: {{ $task->task_progress }}%"></div>
                    </div>
                </div>

                <!-- Actions -->
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <button onclick="openModal('updateStatusModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Update Status</span>
                    </button>
                    <button onclick="openModal('updateProgressModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Update Progress</span>
                    </button>
                </div>
            </div>

            </div>

            <!-- Logs Tab -->
            <div x-show="activeTab === 'logs'" class="p-0 animate-fade-in-up" style="display: none;">
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th class="text-left pl-6">Action</th>
                                <th class="text-left">Remark</th>
                                <th class="text-left">Date</th>
                                <th class="text-left pr-6">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($task->logs as $log)
                                <tr>
                                    <td class="pl-6 font-medium text-slate-800">{{ $log->log_action }}</td>
                                    <td class="text-slate-600">{{ $log->log_remark }}</td>
                                    <td class="text-slate-500 text-sm whitespace-nowrap">{{ $log->log_date }}</td>
                                    <td class="pr-6">
                                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-semibold">
                                            {{ $log->logger->first_name ?? 'System' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-slate-400">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                                        <p>No logs found for this task.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- Update Status Modal -->
    <div id="updateStatusModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('updateStatusModal')"></div>
        <div class="modal-content w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-display font-bold text-premium">Update Task Status</h2>
                <button onclick="closeModal('updateStatusModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form action="{{ route('emp.tasks.status', $task->task_id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Status</label>
                    <select name="status_id" class="premium-input w-full" required>
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}" {{ $task->status_id == $status->status_id ? 'selected' : '' }}>
                                {{ $status->status_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Remark / Note</label>
                    <textarea name="log_remark" rows="3" class="premium-input w-full"
                        placeholder="Enter reason for update..." required></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('updateStatusModal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        Update Status
                    </button>
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
                <button onclick="closeModal('updateProgressModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form action="{{ route('emp.tasks.status', $task->task_id) }}" method="POST" class="space-y-6">
                @csrf
                <div x-data="{ progress: {{ $task->task_progress }} }">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Progress: <span
                            class="text-brand-dark font-bold text-lg" x-text="progress + '%'"></span></label>
                    <input type="range" name="task_progress" min="0" max="100" x-model="progress"
                        class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-dark">
                    <div class="flex justify-between text-xs text-slate-400 mt-2">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                </div>

                <div class="text-left">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Remark / Note</label>
                    <textarea name="log_remark" rows="3" class="premium-input w-full" placeholder="What was achieved?"
                        required></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('updateProgressModal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        Update Progress
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js for Tabs & Progress Range -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
