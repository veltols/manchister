@extends('layouts.app')

@section('title', 'Manage Tickets')
@section('subtitle', 'Oversee, assign, and create support tickets')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Support Tickets</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $tickets->total() }} total tickets</p>
            </div>
            <!-- Admin Create Ticket Button -->
            <button onclick="openModal('newTicketModal')" 
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>New Ticket</span>
            </button>
        </div>

        <!-- Filter Tabs -->
        <div class="premium-card p-2">
            <div class="flex gap-2">
                @php $stt = request('stt', 0); @endphp
                <a href="{{ route('admin.tickets.index', ['stt' => 0]) }}" 
                   class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 0 ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    All Tickets
                </a>
                <a href="{{ route('admin.tickets.index', ['stt' => 1]) }}" 
                   class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 1 ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    Open
                </a>
                <a href="{{ route('admin.tickets.index', ['stt' => 2]) }}" 
                   class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 2 ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    In Progress
                </a>
                <a href="{{ route('admin.tickets.index', ['stt' => 3]) }}" 
                   class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 3 ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    Resolved
                </a>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="space-y-4">
            <div class="overflow-x-auto px-1 pb-4">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">REF</th>
                            <th class="text-left">Subject</th>
                            <th class="text-left">Category</th>
                            <th class="text-left">Added By</th>
                            <th class="text-left">Assigned To</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">{{ $ticket->ticket_ref }}</span>
                                </td>
                                <td class="max-w-xs">
                                    <span class="font-semibold text-slate-800 block truncate" title="{{ $ticket->ticket_subject }}">{{ $ticket->ticket_subject }}</span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-medium">
                                        <i class="fa-solid fa-tag text-xs"></i>
                                        {{ $ticket->category->category_name ?? 'General' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">
                                            {{ substr($ticket->addedBy->first_name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-600">{{ $ticket->addedBy->first_name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($ticket->assignedTo)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600">
                                                {{ substr($ticket->assignedTo->first_name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-indigo-700">{{ $ticket->assignedTo->first_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ str_contains($ticket->priority->priority_color ?? '', 'f00') ? 'from-red-500 to-rose-600' : 'from-slate-500 to-slate-600' }} text-white text-xs font-bold shadow-md"
                                          style="@if(!empty($ticket->priority->priority_color) && !str_contains($ticket->priority->priority_color, 'f00')) background: #{{ $ticket->priority->priority_color }}; @endif">
                                        <i class="fa-solid fa-circle"></i>
                                        {{ $ticket->priority->priority_name ?? 'Normal' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $ticket->status_id == 3 ? 'from-green-500 to-emerald-600' : ($ticket->status_id == 2 ? 'from-blue-500 to-cyan-600' : 'from-yellow-500 to-amber-600') }} text-white text-xs font-bold shadow-md">
                                        <i class="fa-solid fa-{{ $ticket->status_id == 3 ? 'check' : ($ticket->status_id == 2 ? 'spinner' : 'clock') }}"></i>
                                        {{ $ticket->status->status_name ?? 'Open' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <!-- Assign -->
                                        <button onclick="openAssignModal('{{ $ticket->ticket_id }}')" 
                                                class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                                title="Assign to IT">
                                            <i class="fa-solid fa-user-plus text-sm"></i>
                                        </button>

                                        <!-- Status Actions -->
                                        @if($ticket->status_id == 1 && $ticket->assigned_to != 0)
                                            <button onclick="openStatusModal('{{ $ticket->ticket_id }}', 2, 'Start Progress')" 
                                                    class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                                    title="Mark In Progress">
                                                <i class="fa-solid fa-play text-sm"></i>
                                            </button>
                                        @endif

                                        @if(($ticket->status_id == 1 || $ticket->status_id == 2) && $ticket->assigned_to != 0)
                                            <button onclick="openStatusModal('{{ $ticket->ticket_id }}', 3, 'Resolve Ticket')" 
                                                    class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                    title="Resolve">
                                                <i class="fa-solid fa-check text-sm"></i>
                                            </button>
                                        @endif
                                        
                                        @if($ticket->status_id == 3 || $ticket->status_id == 4)
                                             <button onclick="openStatusModal('{{ $ticket->ticket_id }}', 100, 'Reopen Ticket')" 
                                                class="w-9 h-9 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                                title="Reopen">
                                            <i class="fa-solid fa-arrow-rotate-left text-sm"></i>
                                        </button>
                                        @endif

                                        <!-- View -->
                                        <a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}" 
                                           class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                           title="View Details">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-ticket text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No tickets found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $tickets->appends(['stt' => request('stt')])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->

    <!-- New Ticket Modal -->
    <div id="newTicketModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('newTicketModal')"></div>
        <div class="modal-content max-w-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Create New Ticket</h2>
                <button onclick="closeModal('newTicketModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                             <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Added By
                        </label>
                        <select name="added_by" class="premium-input w-full px-4 py-3 text-sm" required>
                             <option value="">Select Employee...</option>
                             @foreach($allEmployees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                             @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-heading text-indigo-600 mr-2"></i>Subject
                        </label>
                        <input type="text" name="ticket_subject" class="premium-input w-full px-4 py-3 text-sm"
                            placeholder="Brief description of the issue" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Category
                            </label>
                            <select name="category_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-flag text-indigo-600 mr-2"></i>Priority
                            </label>
                            <select name="priority_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri->priority_id }}">{{ $pri->priority_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-align-left text-indigo-600 mr-2"></i>Description
                        </label>
                        <textarea name="ticket_description" class="premium-input w-full px-4 py-3 text-sm" rows="5"
                            placeholder="Provide detailed information about the issue..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-paperclip text-indigo-600 mr-2"></i>Attachment (Optional)
                        </label>
                        <input type="file" name="ticket_attachment" class="premium-input w-full px-4 py-3 text-sm">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('newTicketModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                            <optgroup label="IT Department Staff">
                                @foreach($itEmployees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="All Employees">
                                @foreach($allEmployees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </optgroup>
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
