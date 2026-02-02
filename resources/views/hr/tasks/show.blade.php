@extends('layouts.app')

@section('title', 'Task Details')
@section('subtitle', $task->task_title)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('hr.tasks.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>

            <div class="flex gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                    style="background-color: {{ $task->status->status_color ?? '#f1f5f9' }}33; color: {{ $task->status->status_color ?? '#475569' }}">
                    {{ $task->status->status_name ?? 'Unknown Status' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                <div class="premium-card p-8">
                    <h2 class="text-xl font-display font-bold text-premium mb-4">{{ $task->task_title }}</h2>
                    <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $task->task_description }}</p>
                </div>

                <!-- Logs / History if available -->
                @if($task->logs->count() > 0)
                    <div class="premium-card p-6">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Activity Log</h3>
                        <div class="space-y-4">
                            @foreach($task->logs as $log)
                                <div class="flex gap-3 text-sm">
                                    <div class="text-slate-400 text-xs w-24 shrink-0">{{ $log->log_date }}</div>
                                    <div class="text-slate-600">{{ $log->log_description }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="premium-card p-6 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Details</h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Priority</span>
                            <span class="font-bold"
                                style="color: {{ $task->priority->priority_color ?? '#475569' }}">{{ $task->priority->priority_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Assigned To</span>
                            <span class="font-bold text-slate-700">{{ $task->assignedTo->first_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Assigned By</span>
                            <span class="font-bold text-slate-700">{{ $task->assignedBy->first_name ?? 'System' }}</span>
                        </div>
                        <div class="pt-4 border-t border-slate-200">
                            <div class="flex justify-between mb-2">
                                <span class="text-slate-500">Start Date</span>
                                <span
                                    class="font-bold text-slate-700">{{ $task->task_assigned_date ? $task->task_assigned_date->format('M d, Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Due Date</span>
                                <span
                                    class="font-bold text-red-500">{{ $task->task_due_date ? $task->task_due_date->format('M d, Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
