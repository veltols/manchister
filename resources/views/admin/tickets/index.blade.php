@extends('layouts.app')

@section('title', 'Admin Support Tickets')
@section('subtitle', 'Manage all support requests and assignments')

@section('content')
    <div class="space-y-6">

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Support Tickets</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $tickets->total() }} total tickets</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openModal('newTicketModal')"
                    class="ml-2 inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>New Ticket</span>
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="premium-card p-2">
            <div class="flex gap-2">
                <a href="{{ route('admin.tickets.index') }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 0 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    All Tickets
                </a>
                <a href="{{ route('admin.tickets.index', ['stt' => 1]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 1 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Open
                </a>
                <a href="{{ route('admin.tickets.index', ['stt' => 2]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 2 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    In Progress
                </a>
                <a href="{{ route('admin.tickets.index', ['stt' => 3]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 3 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Resolved
                </a>
                <a href="{{ route('admin.tickets.index', ['stt' => 4]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 4 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Unassigned
                </a>
            </div>
        </div>

        <!-- Tickets Table Area -->
        <div class="space-y-4">
            <div class="overflow-x-auto px-1 pb-4">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">REF</th>
                            <th class="text-left">Subject</th>
                            <th class="text-left">Added By</th>
                            <th class="text-left">Assigned To</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tickets-container">
                        @forelse($tickets as $ticket)
                            <!-- Initial server-side render -->
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">{{ $ticket->ticket_ref }}</span>
                                </td>
                                <td class="max-w-xs">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-800 block truncate" title="{{ $ticket->ticket_subject }}">
                                            {{ $ticket->ticket_subject }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            {{ $ticket->category->category_name ?? 'General' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                                            {{ $ticket->addedBy ? substr($ticket->addedBy->first_name, 0, 1) : 'S' }}
                                        </div>
                                        <span class="text-sm text-slate-600 font-medium">
                                            {{ $ticket->addedBy ? $ticket->addedBy->first_name . ' ' . $ticket->addedBy->last_name : 'System' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($ticket->assignedTo)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-600 border border-indigo-100">
                                                {{ substr($ticket->assignedTo->first_name, 0, 1) }}
                                            </div>
                                            <span class="text-sm text-indigo-700 font-medium">
                                                {{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded">Unassigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #{{ $ticket->priority->priority_color ?? 'ccc' }}">
                                        {{ $ticket->priority->priority_name ?? 'Normal' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #{{ $ticket->status->status_color ?? 'ccc' }}">
                                        {{ $ticket->status->status_name ?? 'Open' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}"
                                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                            title="View Details & Manage">
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

            <!-- AJAX Pagination Container -->
            <div id="tickets-pagination"></div>
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
                        <input type="file" name="ticket_attachment" id="ticket_attachment" class="premium-input w-full px-4 py-3 text-sm">
                        <div id="ticket-attachment-preview"></div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        // Initialize Attachment Preview
        window.initAttachmentPreview({
            inputSelector: '#ticket_attachment',
            containerSelector: '#ticket-attachment-preview'
        });

        // File Size Validation (Max 8MB)
        const attachmentInput = document.getElementById('ticket_attachment');
        if (attachmentInput) {
            attachmentInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileSize = this.files[0].size; // in bytes
                    const maxSize = 8 * 1024 * 1024; // 8MB

                    if (fileSize > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'The attachment size must not exceed 8MB.',
                            confirmButtonColor: '#4f46e5'
                        });
                        this.value = ''; // Clear the input
                        // Clear preview if exists (using the preview instance if accessible, or manually)
                        const previewContainer = document.getElementById('ticket-attachment-preview');
                        if (previewContainer) previewContainer.innerHTML = '';
                    }
                }
            });
        }

        // ... existing modal scripts ...

        // Ticket Rendering Helpers
        function getAddedByBadge(addedBy) {
            if (addedBy) {
                const initial = addedBy.first_name ? addedBy.first_name.charAt(0) : 'S';
                const name = addedBy.first_name + ' ' + addedBy.last_name;
                return `
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                            ${initial}
                        </div>
                        <span class="text-sm text-slate-600 font-medium">
                            ${name}
                        </span>
                    </div>
                `;
            }
            return `
                 <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                        S
                    </div>
                    <span class="text-sm text-slate-600 font-medium">
                        System
                    </span>
                 </div>
            `;
        }

        function getAssignedToBadge(assignedTo) {
            if (assignedTo) {
                const initial = assignedTo.first_name ? assignedTo.first_name.charAt(0) : 'U';
                const name = assignedTo.first_name + ' ' + assignedTo.last_name;
                return `
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-600 border border-indigo-100">
                            ${initial}
                        </div>
                        <span class="text-sm text-indigo-700 font-medium">
                            ${name}
                        </span>
                    </div>
                `;
            }
            return `<span class="text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded">Unassigned</span>`;
        }
        
        // Initialize AJAX Pagination
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('admin.tickets.data', ['stt' => $stt]) }}", // Pass current filter
            containerSelector: '#tickets-container',
            paginationSelector: '#tickets-pagination',
            perPage: 15,
            renderCallback: function(tickets) {
                const container = document.querySelector('#tickets-container');
                
                if (tickets.length === 0) {
                    container.innerHTML = `
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
                    `;
                    return;
                }
                
                let html = '';
                tickets.forEach(ticket => {
                    const showUrl = `{{ route('admin.tickets.show', ':id') }}`.replace(':id', ticket.ticket_id);
                    
                    html += `
                        <tr>
                            <td>
                                <span class="font-mono text-sm font-semibold text-slate-600">${ticket.ticket_ref}</span>
                            </td>
                            <td class="max-w-xs">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-800 block truncate" title="${ticket.ticket_subject}">
                                        ${ticket.ticket_subject}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        ${ticket.category ? ticket.category.category_name : 'General'}
                                    </span>
                                </div>
                            </td>
                            <td>
                                ${getAddedByBadge(ticket.added_by)}
                            </td>
                            <td>
                                ${getAssignedToBadge(ticket.assigned_to)}
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #${ticket.priority ? ticket.priority.priority_color : 'ccc'}">
                                    ${ticket.priority ? ticket.priority.priority_name : 'Normal'}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #${ticket.status ? ticket.status.status_color : 'ccc'}">
                                    ${ticket.status ? ticket.status.status_name : 'Open'}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="${showUrl}"
                                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                        title="View Details & Manage">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                container.innerHTML = html;
            }
        });

        // Use server-side rendered data for initial page load if exists
        @if($tickets->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $tickets->currentPage() }},
                last_page: {{ $tickets->lastPage() }},
                from: {{ $tickets->firstItem() ?? 0 }},
                to: {{ $tickets->lastItem() ?? 0 }},
                total: {{ $tickets->total() }}
            });
        @endif

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        // ... existing open/close modals functions ...
    </script>
    @endpush
@endsection
