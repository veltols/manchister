@extends('layouts.app')

@section('title', 'Ticket Details')
@section('subtitle', 'View and manage ticket #' . $ticket->ticket_ref)

@section('content')
    <div class="space-y-6">

        <!-- Summary Badges -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">System ID</p>
                    <p class="text-2xl font-bold text-slate-700">{{ $ticket->ticket_id }}</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-brand-dark group-hover:text-white transition-colors">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Reference</p>
                    <p class="text-xl font-bold text-slate-700">{{ $ticket->ticket_ref }}</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-brand-dark group-hover:text-white transition-colors">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Priority</p>
                    <p class="text-xl font-bold" style="color: #{{ $ticket->priority->priority_color ?? '000' }}">
                        {{ $ticket->priority->priority_name ?? 'N/A' }}
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
                    <p class="text-xl font-bold" style="color: #{{ $ticket->status->status_color ?? '000' }}">
                        {{ $ticket->status->status_name ?? 'N/A' }}
                    </p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-brand-dark group-hover:text-white transition-colors">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>

        </div>

        <!-- Tabs Container -->
        <div x-data="{ activeTab: 'details' }" class="premium-card overflow-hidden">
            <div class="flex border-b border-slate-100">
                <button @click="activeTab = 'details'"
                    :class="activeTab === 'details' ? 'border-brand-dark text-brand-dark' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    Ticket Details
                </button>
                <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'border-brand-dark text-brand-dark' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    Logs & History
                </button>
            </div>

            <!-- Details Tab -->
            <div x-show="activeTab === 'details'" class="p-6 space-y-8 animate-fade-in-up">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Subject</h3>
                        <p class="text-lg font-medium text-slate-800">{{ $ticket->ticket_subject }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Category</h3>
                        <p class="text-lg font-medium text-slate-800">{{ $ticket->category->category_name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Description</h3>
                    <div
                        class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-slate-700 leading-relaxed whitespace-pre-wrap">
                        {{ $ticket->ticket_description }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-100 pt-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Added By</h3>
                        <div class="flex items-center gap-3 mt-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($ticket->addedBy->first_name ?? 'U', 0, 1) }}
                            </div>
                            <p class="font-medium text-slate-800">
                                {{ $ticket->addedBy->first_name ?? 'Unknown' }} {{ $ticket->addedBy->last_name ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Added Date</h3>
                        <p class="text-slate-700 mt-2">{{ $ticket->ticket_added_date }}</p>
                    </div>
                </div>

                @if($ticket->ticket_attachment && $ticket->ticket_attachment != 'no-img.png')
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Attachment</h3>
                        <a href="{{ asset($ticket->ticket_attachment) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors font-medium">
                            <i class="fa-solid fa-paperclip"></i>
                            View Attachment
                        </a>
                    </div>
                @endif

                <!-- Quick Actions (Update Status) -->
            @if($ticket->status_id != 3) <!-- If not closed -->
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <button onclick="openModal('updateStatusModal')" class="premium-button">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Update Ticket Status</span>
                    </button>
                </div>
            </div>
            @endif
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
                            @forelse($ticket->logs as $log)
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
                                        <p>No logs found for this ticket.</p>
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
                <h2 class="text-xl font-display font-bold text-premium">Update Ticket Status</h2>
                <button onclick="closeModal('updateStatusModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form action="{{ route('emp.tickets.update_status', $ticket->ticket_id) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Status</label>
                    <select name="status_id" class="premium-input w-full" required>
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}" {{ $ticket->status_id == $status->status_id ? 'selected' : '' }}>
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
                        class="px-5 py-2.5 rounded-xl bg-brand-dark text-white font-semibold hover:bg-brand-light shadow-lg hover:shadow-xl transition-all">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js for Tabs (Load if not already in layout) -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
