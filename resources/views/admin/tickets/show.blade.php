@extends('layouts.app')

@section('title', 'Ticket Details')
@section('subtitle', 'View and manage ticket #' . $ticket->ticket_ref)

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Summary Badges -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">System ID</p>
                    <p class="text-2xl font-bold text-slate-700">{{ $ticket->ticket_id }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Reference</p>
                    <p class="text-xl font-bold text-slate-700">{{ $ticket->ticket_ref }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
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
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
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
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>

        </div>

        <!-- Tabs Container -->
        <div x-data="{ activeTab: 'details' }" class="premium-card overflow-hidden">
            <div class="flex border-b border-slate-100">
                <button @click="activeTab = 'details'"
                    :class="activeTab === 'details' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    Ticket Details
                </button>
                <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
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
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->ticket_description }}</div>
                </div>

                <!-- Admin Specific Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-slate-100 pt-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Added By</h3>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($ticket->addedBy->first_name ?? 'U', 0, 1) }}
                            </div>
                            <p class="font-medium text-slate-800">
                                {{ $ticket->addedBy->first_name ?? 'Unknown' }} {{ $ticket->addedBy->last_name ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Assigned To</h3>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($ticket->assignedTo->first_name ?? 'U', 0, 1) }}
                            </div>
                            <p class="font-medium text-slate-800">
                                {{ $ticket->assignedTo->first_name ?? 'Unassigned' }} {{ $ticket->assignedTo->last_name ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Added Date</h3>
                        <p class="text-slate-700 mt-2">{{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y h:i A') }}</p>
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

                <!-- Quick Actions Block -->
                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        
                        <!-- Assign Action -->
                        <button onclick="openAssignModal('{{ $ticket->ticket_id }}')" class="premium-button from-amber-500 to-orange-600 text-white shadow-lg">
                            <i class="fa-solid fa-user-plus mr-2"></i>
                            <span>Assign Ticket</span>
                        </button>

                        <!-- Status Actions -->
                         @if($ticket->status_id == 1 && $ticket->assigned_to != 0)
                            <button onclick="openStatusModal('{{ $ticket->ticket_id }}', 2, 'Start Progress')" class="premium-button from-blue-500 to-cyan-600 text-white shadow-lg">
                                <i class="fa-solid fa-play mr-2"></i>Start Progress
                            </button>
                        @endif

                        @if(($ticket->status_id == 1 || $ticket->status_id == 2) && $ticket->assigned_to != 0)
                            <button onclick="openStatusModal('{{ $ticket->ticket_id }}', 3, 'Resolve Ticket')" class="premium-button from-emerald-500 to-green-600 text-white shadow-lg">
                                <i class="fa-solid fa-check mr-2"></i>Resolve Ticket
                            </button>
                        @endif

                        @if($ticket->status_id == 3 || $ticket->status_id == 4)
                            <button onclick="openStatusModal('{{ $ticket->ticket_id }}', 100, 'Reopen Ticket')" class="premium-button from-rose-500 to-red-600 text-white shadow-lg">
                                <i class="fa-solid fa-arrow-rotate-left mr-2"></i>Reopen Ticket
                            </button>
                        @endif
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
                            @forelse($ticket->logs as $log)
                                <tr>
                                    <td class="pl-6 font-medium text-slate-800">{{ $log->log_action }}</td>
                                    <td class="text-slate-600">{{ $log->log_remark }}</td>
                                    <td class="text-slate-500 text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y h:i A') }}</td>
                                    <td class="pr-6">
                                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-semibold">
                                            {{ $log->logger->first_name ?? 'System' }} {{ $log->logger->last_name ?? '' }}
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

    <!-- Modals (Copied and adapted from Index for availability in Show view) -->
    <!-- Assign Modal -->
    <div id="assignModal" class="modal">
        <div class="modal-backdrop" onclick="closeAssignModal()"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-display font-bold text-premium">Assign Ticket</h2>
                <button onclick="closeAssignModal()" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="assignForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Assign To</label>
                        <select name="assigned_to" required class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">Select Employee...</option>
                            @isset($itEmployees)
                                <optgroup label="IT Department Staff">
                                    @foreach($itEmployees as $emp)
                                        <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </optgroup>
                            @endisset
                            @isset($allEmployees)
                                <optgroup label="All Employees">
                                    @foreach($allEmployees as $emp)
                                        <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </optgroup>
                            @endisset
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="ticket_remarks" rows="3" required class="premium-input w-full px-4 py-3 text-sm" placeholder="Reason for assignment..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeAssignModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 premium-button from-amber-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl">Assign Ticket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Status Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-backdrop" onclick="closeStatusModal()"></div>
        <div class="modal-content max-w-lg p-6">
             <div class="flex justify-between items-center mb-6">
                <h2 id="statusModalTitle" class="text-xl font-display font-bold text-premium">Update Status</h2>
                <button onclick="closeStatusModal()" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <input type="hidden" name="status_id" id="modalStatusId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="ticket_remarks" rows="3" required class="premium-input w-full px-4 py-3 text-sm" placeholder="Enter resolution notes or remarks..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeStatusModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @push('scripts')
    <script>
        function openAssignModal(ticketId) {
            document.getElementById('assignModal').classList.add('active');
            document.getElementById('assignForm').action = "/admin/tickets/" + ticketId + "/assign";
        }

        function closeAssignModal() {
             document.getElementById('assignModal').classList.remove('active');
        }

        function openStatusModal(ticketId, statusId, title) {
            document.getElementById('statusModal').classList.add('active');
            document.getElementById('statusForm').action = "/admin/tickets/" + ticketId + "/status";
            document.getElementById('modalStatusId').value = statusId;
            document.getElementById('statusModalTitle').innerText = title;
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('active');
        }
    </script>
    @endpush
@endsection
