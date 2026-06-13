@extends('layouts.app')

@section('title', 'Ticket Details')
@section('subtitle', 'View details for ticket #' . $ticket->ticket_ref)

@section('content')
    <div class="space-y-12 animate-fade-in-up" x-data="{ activeTab: 'details' }">

        <!-- Back Button & Tools -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tickets.index') }}"
                class="group flex items-center gap-2 text-slate-500 font-bold hover:text-brand transition-colors">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Back to Tickets</span>
            </a>
            
            @php
                $statusOpen = \App\Models\SupportTicketStatus::OPEN;
                $statusInProgress = \App\Models\SupportTicketStatus::IN_PROGRESS;
                $statusResolved = \App\Models\SupportTicketStatus::RESOLVED;
                $statusCancelled = \App\Models\SupportTicketStatus::CANCELLED;
            @endphp

            @if(in_array($ticket->approval_status, ['pending_lm', 'pending_gm']))
                <button disabled
                    class="px-5 py-3 bg-slate-100 border border-slate-200 text-slate-400 font-bold rounded-xl shadow-sm cursor-not-allowed flex items-center gap-2">
                    <i class="fa-solid fa-lock text-sm"></i>
                    <span>Locked (Awaiting Approval)</span>
                </button>
            @elseif($ticket->approval_status == 'rejected')
                <!-- Approval is rejected, do not show Edit Ticket / Status button -->
            @elseif($ticket->status_id == $statusResolved)
                <div class="flex items-center gap-3">
                    <button onclick="openModal('editDetailsModal')"
                        class="px-5 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl shadow-sm hover:border-indigo-300 hover:text-indigo-700 hover:scale-105 transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-sm text-indigo-500"></i>
                        <span>Edit Details</span>
                    </button>
                    <button onclick="reopenTicket()"
                        class="px-6 py-3 bg-brand text-white font-bold rounded-xl shadow-lg hover:bg-brand-dark hover:scale-105 transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left text-sm"></i>
                        <span>Reopen Ticket</span>
                    </button>
                </div>
            @else
                    <button onclick="openModal('editDetailsModal')"
                        class="px-5 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl shadow-sm hover:border-brand-light hover:text-brand-dark hover:scale-105 transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-sm text-brand"></i>
                        <span>Edit Ticket / Status</span>
                    </button>
            @endif
        </div>

        <!-- Premium Hero Banner -->
        <div class="rounded-[2.5rem] bg-brand p-8 md:p-12 text-white shadow-2xl shadow-brand/20 relative overflow-hidden isolate">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full blur-3xl -z-10"></div>
            <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-white/10 rounded-full blur-3xl -z-10"></div>
            <div class="absolute top-1/2 left-0 w-32 h-64 bg-white/5 rounded-full blur-2xl -z-10"></div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-lg bg-white/10 border border-white/10 text-[10px] font-bold uppercase tracking-widest backdrop-blur-md">
                            {{ $ticket->category->category_name ?? 'Support Ticket' }}
                        </span>
                        <span class="text-white/40 text-xs">•</span>
                        <span class="text-xs font-mono font-medium text-white/60">
                            {{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y H:i') }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-display font-black tracking-tight text-white leading-tight">
                        {{ $ticket->ticket_ref }}
                    </h1>
                    
                    <div class="flex items-center gap-6 pt-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Created By</p>
                                <p class="text-sm font-bold">{{ $ticket->addedBy->first_name ?? 'System' }} {{ $ticket->addedBy->last_name ?? '' }}</p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div class="flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-layer-group text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Priority</p>
                                <p class="text-sm font-bold flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" style="background-color: #{{ $ticket->priority->priority_color ?? 'ccc' }}"></span>
                                    {{ $ticket->priority->priority_name ?? 'Normal' }}
                                </p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div class="flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-user-shield text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Assigned To</p>
                                <p class="text-sm font-bold">
                                    {{ $ticket->assignedTo ? $ticket->assignedTo->first_name . ' ' . $ticket->assignedTo->last_name : 'Unassigned' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-4">
                    <div class="px-6 py-3 rounded-2xl bg-white text-slate-900 shadow-xl flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full animate-pulse" style="background-color: #{{ $ticket->status->status_color ?? '000' }}"></div>
                        <span class="font-bold text-lg" style="color: #{{ $ticket->status->status_color ?? '000' }}">
                            {{ $ticket->status->status_name ?? 'Open' }}
                        </span>
                    </div>
                    @if($ticket->ticket_attachment && $ticket->ticket_attachment != 'no-img.png')
                        <a href="{{ asset('uploads/tickets/' . basename($ticket->ticket_attachment)) }}" target="_blank" 
                           class="flex items-center gap-2 text-xs font-bold text-white/70 hover:text-white transition-colors bg-white/10 px-4 py-2 rounded-xl hover:bg-white/20">
                            <i class="fa-solid fa-paperclip"></i>
                            View Attachment
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Description Card -->
                <div class="premium-card p-1">
                    <div class="bg-brand/5 p-8 rounded-[1.25rem]">
                        <h3 class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">
                            <i class="fa-solid fa-align-left text-brand"></i>
                            Subject & Description
                        </h3>
                        <div class="mb-4">
                             <h2 class="text-xl font-bold text-slate-800">{{ $ticket->ticket_subject }}</h2>
                        </div>
                        <div class="prose prose-slate max-w-none prose-p:font-medium prose-p:text-slate-600">
                            {!! nl2br(e($ticket->ticket_description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Timeline / Activity Logs -->
                <div class="premium-card p-8">
                    <h3 class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-widest mb-8">
                        <i class="fa-solid fa-clock-rotate-left text-brand"></i>
                        Activity Timeline
                    </h3>
                    
                    <div class="relative space-y-10 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-brand-light/30 before:via-slate-200 before:to-transparent">
                        @forelse($ticket->logs as $log)
                            <div class="relative flex items-start gap-4 group">
                                <div class="absolute left-0 mt-1 ml-5 w-4 h-0.5 bg-brand-light/50 group-hover:bg-brand transition-colors"></div>
                                
                                <div class="relative z-10 flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-brand/10 text-brand shadow-sm group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </div>
                                
                                <div class="flex-1 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm group-hover:shadow-md group-hover:border-brand-light transition-all">
                                    <div class="flex flex-wrap justify-between gap-2 mb-2">
                                        <span class="font-bold text-slate-800">{{ $log->log_action }}</span>
                                        <span class="text-xs font-mono text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                            {{ \Carbon\Carbon::parse($log->log_date)->format('M d, H:i A') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 mb-3">{{ $log->log_remark }}</p>
                                    <div class="flex items-center gap-2 pt-2 border-t border-slate-50">
                                        <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-500">
                                            {{ $log->logger ? substr($log->logger->first_name, 0, 1) : 'S' }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                            {{ $log->logger->first_name ?? 'System' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="pl-12 py-4">
                                <p class="text-slate-400 italic font-medium">No activity recorded yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                
                <!-- Approval Request / Status Card -->
                <div class="premium-card p-6 space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fa-solid fa-signature text-indigo-500"></i>
                        <span>Ticket Approval</span>
                    </h4>

                    @if(!$ticket->approval_status)
                        <form action="{{ route('admin.tickets.request_approval', $ticket->ticket_id) }}" method="POST" class="space-y-4">
                            @csrf
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Optionally request approval from the General Manager or the Line Manager of the creator's department before assigning.
                            </p>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700">Approval Target</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer text-xs font-semibold">
                                        <input type="radio" name="target" value="lm" checked class="text-brand focus:ring-brand">
                                        <span>Line Manager</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer text-xs font-semibold">
                                        <input type="radio" name="target" value="gm" class="text-brand focus:ring-brand">
                                        <span>GM</span>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700">Request Notes</label>
                                <textarea name="remarks" rows="2" class="premium-input w-full p-3 text-xs" placeholder="Add optional remarks for the approver..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-gradient-brand text-white font-bold rounded-xl text-xs shadow-md shadow-brand/10 hover:shadow-brand/25 transition-all">
                                <i class="fa-solid fa-paper-plane mr-1.5"></i>Send Request
                            </button>
                        </form>
                    @elseif(in_array($ticket->approval_status, ['pending_lm', 'pending_gm']))
                        <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-100 text-amber-800 space-y-2.5">
                            <div class="flex items-center gap-2 font-bold text-xs">
                                <i class="fa-solid fa-spinner animate-spin"></i>
                                <span>Awaiting Approval</span>
                            </div>
                            <p class="text-xs text-amber-700/90 leading-relaxed">
                                Sent to <strong>{{ $ticket->approvalApprover->full_name ?? 'Manager' }}</strong> ({{ $ticket->approval_status === 'pending_lm' ? 'Line Manager' : 'GM' }}) on 
                                <span>{{ \Carbon\Carbon::parse($ticket->approval_sent_date)->format('M d, Y H:i') }}</span>.
                            </p>
                        </div>
                    @elseif($ticket->approval_status === 'approved')
                        <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100 text-emerald-800 space-y-2.5">
                            <div class="flex items-center gap-2 font-bold text-xs">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Approved</span>
                            </div>
                            <p class="text-xs text-emerald-700/90 leading-relaxed">
                                Approved by <strong>{{ $ticket->approvalApprover->full_name ?? 'Manager' }}</strong> on 
                                <span>{{ \Carbon\Carbon::parse($ticket->approval_action_date)->format('M d, Y H:i') }}</span>.
                            </p>
                            @if($ticket->approval_remarks)
                                <div class="text-[11px] bg-white p-2.5 rounded-lg border border-emerald-100 italic text-slate-600">
                                    "{{ $ticket->approval_remarks }}"
                                </div>
                            @endif
                        </div>
                    @elseif($ticket->approval_status === 'rejected')
                        <div class="p-4 rounded-xl bg-rose-50/50 border border-rose-100 text-rose-800 space-y-2.5">
                            <div class="flex items-center gap-2 font-bold text-xs">
                                <i class="fa-solid fa-circle-xmark"></i>
                                <span>Rejected & Closed</span>
                            </div>
                            <p class="text-xs text-rose-700/90 leading-relaxed">
                                Rejected by <strong>{{ $ticket->approvalApprover->full_name ?? 'Manager' }}</strong> on 
                                <span>{{ \Carbon\Carbon::parse($ticket->approval_action_date)->format('M d, Y H:i') }}</span>.
                            </p>
                            @if($ticket->approval_remarks)
                                <div class="text-[11px] bg-white p-2.5 rounded-lg border border-rose-100 italic text-slate-600">
                                    "{{ $ticket->approval_remarks }}"
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                
                 <!-- Attachments Preview -->
                 @if($ticket->ticket_attachment && $ticket->ticket_attachment != 'no-img.png')
                    <div class="premium-card p-6">
                         <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Files</h4>
                         <a href="{{ asset('uploads/tickets/' . basename($ticket->ticket_attachment)) }}" target="_blank" class="group block">
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 group-hover:border-brand-light group-hover:bg-brand/5 transition-all">
                                <div class="w-10 h-10 rounded-lg bg-brand/10 text-brand flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-file-image"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-slate-700 truncate group-hover:text-brand-dark transition-colors">Attachment</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Click to View</p>
                                </div>
                            </div>
                         </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Ticket Details Modal -->
    <div id="editDetailsModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('editDetailsModal')"></div>
        <div class="modal-content max-w-2xl p-6 max-h-[90vh] overflow-y-auto custom-scrollbar">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Edit Ticket Details</h2>
                    <p class="text-slate-500 text-sm mt-1">Update subject, priority or reporter</p>
                </div>
                <button onclick="closeModal('editDetailsModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.tickets.update_details', $ticket->ticket_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-8">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-tag text-indigo-500 mr-1.5"></i>Category
                            </label>
                            <select name="category_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->category_id }}" {{ $ticket->category_id == $cat->category_id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-flag text-indigo-500 mr-1.5"></i>Priority
                            </label>
                            <select name="priority_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri->priority_id }}" {{ $ticket->priority_id == $pri->priority_id ? 'selected' : '' }}>
                                        {{ $pri->priority_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-heading text-indigo-500 mr-1.5"></i>Subject
                        </label>
                        <input type="text" name="ticket_subject"
                            class="premium-input w-full px-4 py-3 text-sm"
                            value="{{ old('ticket_subject', $ticket->ticket_subject) }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-align-left text-indigo-500 mr-1.5"></i>Description
                        </label>
                        <textarea name="ticket_description" class="premium-input w-full px-4 py-3 text-sm" rows="3" required>{{ old('ticket_description', $ticket->ticket_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-paperclip text-indigo-500 mr-1.5"></i>Attachment (Optional)
                        </label>
                        <input type="file" name="ticket_attachment" id="ticket_attachment"
                            class="premium-input w-full px-4 py-3 text-sm">
                        <div id="ticket-attachment-preview"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-user text-indigo-500 mr-1.5"></i>Reported By
                        </label>
                        <select name="added_by" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($groupedEmployees as $dept)
                            @if($dept->employees->count() > 0)
                                <optgroup label="{{ $dept->department_name }}">
                                    @foreach($dept->employees as $emp)
                                        <option value="{{ $emp->employee_id }}"
                                            {{ $ticket->added_by == $emp->employee_id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-spinner text-indigo-500 mr-1.5"></i>Status
                            </label>
                            <select name="status_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                <option value="{{ $statusOpen }}" {{ $ticket->status_id == $statusOpen ? 'selected' : '' }}>Open</option>
                                <option value="{{ $statusInProgress }}" {{ $ticket->status_id == $statusInProgress ? 'selected' : '' }}>In Progress</option>
                                <option value="{{ $statusResolved }}" {{ $ticket->status_id == $statusResolved ? 'selected' : '' }}>Resolved</option>
                                @if($ticket->added_by != Auth::user()->user_id)
                                <option value="{{ $statusCancelled }}" {{ $ticket->status_id == $statusCancelled ? 'selected' : '' }}>Cancelled</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-user-shield text-indigo-500 mr-1.5"></i>Assign To (Optional)
                            </label>
                           <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                                    <option value="">Unassigned</option>
                                        
                                @foreach($groupedEmployees as $dept)
                                    @if($dept->employees->count() > 0)
                                        <optgroup label="{{ $dept->department_name }}">
                                            @foreach($dept->employees as $emp)
                                                <option value="{{ $emp->employee_id }}" {{ $ticket->assigned_to == $emp->employee_id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                                </select>
                        </div>
                    </div>

                </div>

                <div class="mt-6 pt-6 border-t border-slate-100">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-comment-dots text-indigo-500 mr-1.5"></i>Activity Remark
                    </label>
                    <textarea name="ticket_remarks" class="premium-input w-full px-4 py-3 text-sm" rows="3"
                        placeholder="Add an optional remark about these changes..."></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200 pb-2">
                    <button type="button" onclick="closeModal('editDetailsModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Save Changes
                    </button>
                </div>
                <div class="h-6"></div>
            </form>
        </div>
    </div>

    <!-- Reopen Modal (Hidden Form) -->
    <form id="reopenForm" action="{{ route('admin.tickets.update_status', $ticket->ticket_id) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="status_id" value="{{ $statusOpen }}">
        <input type="hidden" name="ticket_remarks" value="Ticket reopened by Admin.">
    </form>
    @push('scripts')
        <script src="{{ asset('libs/mammoth/mammoth.browser.min.js') }}"></script>
        <script src="{{ asset('js/attachment-preview.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof window.initAttachmentPreview === 'function') {
                    window.initAttachmentPreview({
                        inputSelector: '#ticket_attachment',
                        containerSelector: '#ticket-attachment-preview'
                    });
                }
            });

            function reopenTicket() {
                Swal.fire({
                    title: 'Reopen Ticket?',
                    text: 'Are you sure you want to reopen this ticket? This will move it back to Open status.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#004F68',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Reopen it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl border-0 shadow-2xl',
                        confirmButton: 'px-6 py-3 rounded-xl font-bold',
                        cancelButton: 'px-6 py-3 rounded-xl font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('reopenForm').submit();
                    }
                });
            }
        </script>
    @endpush
@endsection
