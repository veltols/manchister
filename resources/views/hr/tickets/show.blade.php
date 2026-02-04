@extends('layouts.app')

@section('title', 'Ticket Details')
@section('subtitle', 'View and manage support ticket.')

@section('content')
    <div class="flex flex-col h-full space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('hr.tickets.index') }}"
                class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Tickets
            </a>
        </div>

        <!-- Ticket Summary Badges -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- System ID -->
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">System ID</p>
                    <p class="text-lg font-bold text-slate-700">{{ $ticket->ticket_id }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
            </div>

            <!-- Reference -->
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Reference</p>
                    <p class="text-lg font-bold text-slate-700">{{ $ticket->ticket_ref }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center">
                    <i class="fa-solid fa-barcode"></i>
                </div>
            </div>

            <!-- Priority -->
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Priority</p>
                    <p class="text-lg font-bold" style="color: #{{ $ticket->priority->priority_color ?? '64748b' }}">
                        {{ $ticket->priority->priority_name ?? 'Normal' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                    style="background-color: #{{ $ticket->priority->priority_color ?? 'e2e8f0' }}20; color: #{{ $ticket->priority->priority_color ?? '64748b' }}">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status</p>
                    <p class="text-lg font-bold" style="color: #{{ $ticket->status->status_color ?? '64748b' }}">
                        {{ $ticket->status->status_name ?? 'Pending' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                    style="background-color: #{{ $ticket->status->status_color ?? 'e2e8f0' }}20; color: #{{ $ticket->status->status_color ?? '64748b' }}">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Action Bar if Ticket is Open -->
        @if($ticket->status_id != 3)
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-700">Update Status</h3>
                    <p class="text-xs text-slate-500">Change the progress of this ticket.</p>
                </div>
                <div class="flex gap-2">
                    @if($ticket->status_id == 1)
                        <button onclick="openStatusModal(2)"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold text-sm shadow-lg shadow-indigo-100 transition-all">
                            <i class="fa-solid fa-spinner mr-2"></i> Mark as In Progress
                        </button>
                    @elseif($ticket->status_id == 2)
                        <button onclick="openStatusModal(3)"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-sm shadow-lg shadow-green-100 transition-all">
                            <i class="fa-solid fa-check mr-2"></i> Mark as Done
                        </button>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-emerald-800">Ticket Resolved</h3>
                    <p class="text-xs text-emerald-600">This ticket has been closed.</p>
                </div>
                <button onclick="openStatusModal(100)"
                    class="px-6 py-2 bg-white border border-emerald-200 text-emerald-600 rounded-lg hover:bg-emerald-100 font-bold text-sm transition-all">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Reopen Ticket
                </button>
            </div>
        @endif

        <!-- Tabs Header -->
        <div x-data="{ tab: 'details' }" class="space-y-4">
            
            <div class="premium-card p-2 w-fit animate-fade-in max-w-full overflow-x-auto scrollbar-hide">
                <div class="flex flex-nowrap gap-2">
                    <button @click="tab = 'details'; switchTab('details')" 
                        :class="tab === 'details' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                        <i class="fa-solid fa-circle-info mr-2"></i>Ticket Details
                    </button>
                    <button @click="tab = 'logs'; switchTab('logs')" 
                        :class="tab === 'logs' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                        <i class="fa-solid fa-history mr-2"></i>Logs ({{ $ticket->logs->count() }})
                    </button>
                </div>
            </div>

        <!-- Tab Content -->
        <div class="bg-white rounded-b-[20px] shadow-sm border border-t-0 border-slate-200 p-6 -mt-6 min-h-[400px]">

            <!-- Details Tab -->
            <div id="tab-details" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Info</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-500">Category</span>
                                <span
                                    class="text-sm font-medium text-slate-700">{{ $ticket->category->category_name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-500">Requested By</span>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold">
                                        {{ substr($ticket->addedBy->first_name ?? 'U', 0, 1) }}
                                    </div>
                                    <span
                                        class="text-sm font-medium text-slate-700">{{ $ticket->addedBy->first_name ?? 'Unknown' }}
                                        {{ $ticket->addedBy->last_name ?? '' }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-500">Assigned To</span>
                                <div class="flex items-center gap-2">
                                    @if($ticket->assignedTo)
                                        <div
                                            class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">
                                            {{ substr($ticket->assignedTo->first_name ?? 'A', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">{{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</span>
                                    @else
                                        <span class="text-sm font-medium text-slate-400 italic">Unassigned</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-500">Date Opened</span>
                                <span
                                    class="text-sm font-medium text-slate-700">{{ $ticket->ticket_added_date ? \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y h:i A') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-slate-500">Subject</span>
                                <span class="text-sm font-medium text-slate-700">{{ $ticket->ticket_subject }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Description</h3>
                        <div
                            class="bg-slate-50 rounded-xl p-4 text-sm text-slate-600 leading-relaxed border border-slate-100">
                            {{ $ticket->ticket_description }}
                        </div>
                    </div>
                </div>

                @if($ticket->ticket_attachment && $ticket->ticket_attachment != 'no-img.png')
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Attachment</h3>
                        <a href="{{ asset('uploads/' . $ticket->ticket_attachment) }}" target="_blank"
                            class="inline-flex items-center gap-3 px-4 py-3 bg-indigo-50 border border-indigo-100 rounded-xl text-indigo-700 hover:bg-indigo-100 transition-colors">
                            <i class="fa-solid fa-paperclip"></i>
                            <span class="font-medium text-sm">View Attachment</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Logs Tab -->
            <div id="tab-logs" class="hidden space-y-4">
                @forelse($ticket->logs as $log)
                    <div class="flex gap-4 group">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-circle-dot text-[10px]"></i>
                            </div>
                            @if(!$loop->last)
                                <div class="w-px h-full bg-slate-100"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">{{ str_replace('_', ' ', $log->log_action) }}</span>
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 border border-slate-100 px-2 py-0.5 rounded-full italic">{{ $log->log_date ? \Carbon\Carbon::parse($log->log_date)->diffForHumans() : '-' }}</span>
                            </div>
                            <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm">
                                <p class="text-sm text-slate-600 mb-2">{{ $log->log_remark }}</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-bold text-slate-500 uppercase">
                                        {{ substr($log->logger->first_name ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">By {{ $log->logger->first_name ?? 'System' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <i class="fa-solid fa-history text-4xl mb-3 opacity-50"></i>
                        <p>No activity recorded yet.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

    <!-- Status Update Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-backdrop" onclick="closeModal('statusModal')"></div>
        <div class="modal-content max-w-md">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xl font-display font-bold text-premium" id="modalTitle">Update Ticket Status</h2>
                <button onclick="closeModal('statusModal')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('hr.tickets.status.update', $ticket->ticket_id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="status_id" id="modalStatusId">
                
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Remarks / Update Description</label>
                    <textarea name="ticket_remarks" rows="4" class="premium-input w-full" placeholder="Describe the progress or reason for change..." required></textarea>
                </div>

                <div id="assignmentSection" class="hidden space-y-2">
                    <label class="text-sm font-bold text-slate-700">Assign To (Optional)</label>
                    <select name="assigned_to" class="premium-input w-full">
                        <option value="">-- Keep Unassigned --</option>
                        @foreach(App\Models\Employee::where('is_deleted', 0)->where('department_id', 4)->orderBy('first_name')->get() as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }} (IT)</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('statusModal')" class="flex-1 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-primary text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Update Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('[id^="tab-"]').forEach(content => {
                content.classList.add('hidden');
            });
            const selectedContent = document.getElementById(`tab-${tab}`);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }
        }

        function openStatusModal(statusId) {
            const modal = document.getElementById('statusModal');
            const statusInput = document.getElementById('modalStatusId');
            const title = document.getElementById('modalTitle');
            const assignment = document.getElementById('assignmentSection');
            
            statusInput.value = statusId;
            assignment.classList.add('hidden');

            if (statusId == 2) {
                title.innerText = "Mark as In Progress";
                assignment.classList.remove('hidden');
            } else if (statusId == 3) {
                title.innerText = "Mark as Resolved";
            } else if (statusId == 100) {
                title.innerText = "Reopen Ticket";
            } else {
                title.innerText = "Update Ticket Status";
            }

            openModal('statusModal');
        }
    </script>
@endsection