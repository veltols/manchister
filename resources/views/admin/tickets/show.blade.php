@extends('layouts.app')

@section('title', 'Ticket Details')
@section('subtitle', 'View details for ticket #' . $ticket->ticket_ref)

@section('content')
    <div class="space-y-8 animate-fade-in-up" x-data="{ activeTab: 'details' }">

        <!-- Back Button & Tools -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tickets.index') }}"
                class="group flex items-center gap-2 text-slate-500 font-bold hover:text-brand transition-colors">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Back to Tickets</span>
            </a>
            
            <button onclick="openModal('updateStatusModal')"
                class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200 flex items-center gap-2">
                <i class="fa-solid fa-pen text-sm"></i>
                <span>Update Status / Assign</span>
            </button>
        </div>

        <!-- Premium Hero Banner -->
        <div class="rounded-[2.5rem] bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 p-8 md:p-12 text-white shadow-2xl shadow-indigo-900/20 relative overflow-hidden isolate">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full blur-3xl -z-10"></div>
            <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl -z-10"></div>
            <div class="absolute top-1/2 left-0 w-32 h-64 bg-indigo-400/10 rounded-full blur-2xl -z-10"></div>

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
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Description Card -->
                <div class="premium-card p-1">
                    <div class="bg-indigo-50/50 p-8 rounded-[1.25rem]">
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
                    
                    <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-indigo-100 before:via-slate-200 before:to-transparent">
                        @forelse($ticket->logs as $log)
                            <div class="relative flex items-start gap-4 group">
                                <div class="absolute left-0 mt-1 ml-5 w-4 h-0.5 bg-indigo-200 group-hover:bg-brand transition-colors"></div>
                                
                                <div class="relative z-10 flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-indigo-50 text-indigo-600 shadow-sm group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </div>
                                
                                <div class="flex-1 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm group-hover:shadow-md group-hover:border-indigo-100 transition-all">
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

            <!-- Right Column: Context/Help -->
            <div class="space-y-6">
                
                 <!-- Attachments Preview -->
                 @if($ticket->ticket_attachment && $ticket->ticket_attachment != 'no-img.png')
                    <div class="premium-card p-6">
                         <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Files</h4>
                         <a href="{{ asset('uploads/tickets/' . basename($ticket->ticket_attachment)) }}" target="_blank" class="group block">
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 group-hover:border-indigo-200 group-hover:bg-indigo-50/30 transition-all">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-file-image"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-slate-700 truncate group-hover:text-indigo-700 transition-colors">Attachment</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Click to View</p>
                                </div>
                            </div>
                         </a>
                    </div>
                @endif
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

            <form action="{{ route('admin.tickets.update_status', $ticket->ticket_id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">New Status</label>
                        <select name="status_id" class="premium-input w-full px-4 py-3 text-sm" required>
                             @foreach(App\Models\SupportTicketStatus::all() as $st)
                                <option value="{{ $st->status_id }}" {{ $ticket->status_id == $st->status_id ? 'selected' : '' }}>
                                    {{ $st->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Assign To (Optional)</label>
                        <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">-- Keep Current / No Change --</option>
                            @foreach($itEmployees as $emp)
                                <option value="{{ $emp->employee_id }}" {{ $ticket->assigned_to == $emp->employee_id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }} (IT)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Activity Remark</label>
                        <textarea name="ticket_remarks" class="premium-input w-full px-4 py-3 text-sm" rows="4"
                            placeholder="Briefly describe the update or action taken..." required></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('updateStatusModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
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
@endsection
