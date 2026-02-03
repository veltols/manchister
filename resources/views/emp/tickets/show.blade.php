@extends('layouts.app')

@section('title', 'Ticket Details')
@section('subtitle', 'View and manage support ticket #' . $ticket->ticket_ref)

@section('content')
    <div class="space-y-6" x-data="{ activeTab: 'details' }">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('emp.tickets.index') }}"
                    class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:shadow-md transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Ticket #{{ $ticket->ticket_ref }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Created by {{ $ticket->addedBy->first_name ?? 'System' }} on
                        {{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y H:i') }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="openModal('updateStatusModal')"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl shadow-sm hover:shadow-md hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-indigo-600"></i>
                    <span>Update Status</span>
                </button>
            </div>
        </div>

        <!-- Summary Badges (Legacy Style) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="premium-card p-4 flex items-center justify-between overflow-hidden relative">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">System ID</p>
                    <p class="text-xl font-display font-bold text-slate-800">{{ $ticket->ticket_id }}</p>
                </div>
                <div class="absolute -right-2 -bottom-2 text-slate-100 text-5xl opacity-50">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
            </div>
            <div class="premium-card p-4 flex items-center justify-between overflow-hidden relative">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ticket Ref</p>
                    <p class="text-xl font-display font-bold text-slate-800">{{ $ticket->ticket_ref }}</p>
                </div>
                <div class="absolute -right-2 -bottom-2 text-slate-100 text-5xl opacity-50">
                    <i class="fa-solid fa-id-card"></i>
                </div>
            </div>
            <div class="premium-card p-4 flex items-center justify-between overflow-hidden relative border-l-4" style="border-left-color: #{{ $ticket->priority->priority_color ?? 'slate-400' }}">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Priority</p>
                    <p class="text-xl font-display font-bold text-slate-800">{{ $ticket->priority->priority_name ?? 'Normal' }}</p>
                </div>
                <div class="absolute -right-2 -bottom-2 text-5xl opacity-10" style="color: #{{ $ticket->priority->priority_color ?? 'cccccc' }}">
                    <i class="fa-solid fa-list-ol"></i>
                </div>
            </div>
            <div class="premium-card p-4 flex items-center justify-between overflow-hidden relative border-l-4" style="border-left-color: #{{ $ticket->status->status_color ?? 'slate-400' }}">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                    <p class="text-xl font-display font-bold text-slate-800">{{ $ticket->status->status_name ?? 'Open' }}</p>
                </div>
                <div class="absolute -right-2 -bottom-2 text-5xl opacity-10" style="color: #{{ $ticket->status->status_color ?? 'cccccc' }}">
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Tabs Interface -->
        <div class="premium-card overflow-hidden">
            <div class="flex border-b border-slate-100 bg-slate-50/50">
                <button @click="activeTab = 'details'"
                    :class="activeTab === 'details' ? 'border-b-2 border-indigo-600 bg-white text-indigo-600' : 'text-slate-500 hover:bg-slate-50'"
                    class="px-8 py-4 font-bold text-sm transition-all focus:outline-none flex items-center gap-2">
                    <i class="fa-solid fa-circle-info"></i>
                    Ticket Details
                </button>
                <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'border-b-2 border-indigo-600 bg-white text-indigo-600' : 'text-slate-500 hover:bg-slate-50'"
                    class="px-8 py-4 font-bold text-sm transition-all focus:outline-none flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Logs & History
                </button>
            </div>

            <div class="p-6">
                <!-- Details Tab -->
                <div x-show="activeTab === 'details'" class="space-y-8 animate-in fade-in slide-in-from-bottom-2 duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Core Information</h3>
                                <div class="bg-slate-50 rounded-2xl p-6 space-y-4 border border-slate-100">
                                    <div class="flex justify-between items-center py-2 border-b border-slate-200/50">
                                        <span class="text-sm font-semibold text-slate-500">Category</span>
                                        <span class="px-3 py-1 bg-white rounded-lg text-sm font-bold text-indigo-600 border border-indigo-100 shadow-sm">
                                            {{ $ticket->category->category_name ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-200/50">
                                        <span class="text-sm font-semibold text-slate-500">Subject</span>
                                        <span class="text-sm font-bold text-slate-800">{{ $ticket->ticket_subject }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-200/50">
                                        <span class="text-sm font-semibold text-slate-500">Added By</span>
                                        <span class="text-sm font-bold text-slate-800">{{ $ticket->addedBy->first_name ?? 'System' }} {{ $ticket->addedBy->last_name ?? '' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-sm font-semibold text-slate-500">Department</span>
                                        <span class="text-sm font-bold text-slate-800">IT Department</span> {{-- Fallback or actual dept --}}
                                    </div>
                                </div>
                            </div>

                            @if($ticket->ticket_attachment && $ticket->ticket_attachment != 'no-img.png')
                            <div>
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Attachment</h3>
                                <div class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center gap-4 group hover:border-indigo-300 transition-all">
                                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-2xl group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-file-image"></i>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-slate-800 mb-1">Evidence File</p>
                                        <p class="text-xs text-slate-400">{{ $ticket->ticket_attachment }}</p>
                                    </div>
                                    <a href="{{ asset('uploads/' . $ticket->ticket_attachment) }}" target="_blank"
                                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-colors shadow-md">
                                        View Attachment
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Description</h3>
                            <div class="bg-indigo-50/30 rounded-2xl p-6 border border-indigo-100/50 min-h-[200px]">
                                <p class="text-slate-800 leading-relaxed font-medium">
                                    {{ $ticket->ticket_description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logs Tab -->
                <div x-show="activeTab === 'logs'" class="animate-in fade-in slide-in-from-bottom-2 duration-300">
                    <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                        @forelse($ticket->logs as $log)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <!-- Icon -->
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-100 group-[.is-active]:bg-indigo-600 text-slate-500 group-[.is-active]:text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 transition-colors duration-500">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <!-- Card -->
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl bg-white border border-slate-100 shadow-sm border-l-4 border-l-indigo-500">
                                <div class="flex items-center justify-between space-x-2 mb-1">
                                    <div class="font-bold text-slate-800 text-sm">{{ $log->log_action }}</div>
                                    <time class="font-mono text-[10px] font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-full uppercase">
                                        {{ \Carbon\Carbon::parse($log->log_date)->format('M d, H:i') }}
                                    </time>
                                </div>
                                <div class="text-slate-500 text-xs leading-relaxed">
                                    {{ $log->log_remark }}
                                </div>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-bold text-slate-600">
                                        {{ $log->logger ? strtoupper(substr($log->logger->first_name, 0, 1)) : 'S' }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">By {{ $log->logger->first_name ?? 'System' }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 text-slate-400">
                            <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 opacity-20"></i>
                            <p class="font-medium text-sm">No activity logs yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="updateStatusModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('updateStatusModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Update Ticket Status</h2>
                <button onclick="closeModal('updateStatusModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('emp.tickets.update_status', $ticket->ticket_id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">New Status</label>
                        <select name="status_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($statuses as $st)
                                <option value="{{ $st->status_id }}" {{ $ticket->status_id == $st->status_id ? 'selected' : '' }}>
                                    {{ $st->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Activity Remark</label>
                        <textarea name="log_remark" class="premium-input w-full px-4 py-3 text-sm" rows="4"
                            placeholder="Briefly describe the update or action taken..." required></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('updateStatusModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
