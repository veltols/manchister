@extends('layouts.app')

@section('title', 'My Tickets')
@section('subtitle', 'Support requests and issues')

@section('content')
    <div class="space-y-6">

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Support Tickets</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $tickets->total() }} total tickets</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('emp.requests.index') }}" class="px-4 py-2 rounded-lg font-medium text-sm text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-hand-sparkles mr-1"></i> HR Requests
                </a>
                <a href="{{ route('emp.ss.index') }}" class="px-4 py-2 rounded-lg font-medium text-sm text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-headset mr-1"></i> Support Services
                </a>
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
                <a href="{{ route('emp.tickets.index') }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 0 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    All Tickets
                </a>
                <a href="{{ route('emp.tickets.index', ['stt' => 1]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 1 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Open
                </a>
                <a href="{{ route('emp.tickets.index', ['stt' => 2]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 2 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    In Progress
                </a>
                <a href="{{ route('emp.tickets.index', ['stt' => 3]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 3 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Resolved
                </a>
                <a href="{{ route('emp.tickets.index', ['stt' => 4]) }}"
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
                            <th class="text-left">Category</th>
                            <th class="text-left">Last Updated</th>
                            <th class="text-left">Updated By</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody id="tickets-container">
                        @forelse($tickets as $ticket)
                            <!-- Initial server-side render -->
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">{{ $ticket->ticket_ref }}</span>
                                </td>
                                <td class="max-w-xs">
                                    <span class="font-semibold text-slate-800 block truncate"
                                        title="{{ $ticket->ticket_subject }}">{{ $ticket->ticket_subject }}</span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-medium">
                                        <i class="fa-solid fa-tag text-xs"></i>
                                        {{ $ticket->category->category_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-600">
                                        {{ $ticket->last_updated_date ? \Carbon\Carbon::parse($ticket->last_updated_date)->diffForHumans() : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                                            {{ $ticket->latestLog && $ticket->latestLog->logger ? strtoupper(substr($ticket->latestLog->logger->first_name, 0, 1) . substr($ticket->latestLog->logger->last_name, 0, 1)) : '-' }}
                                        </div>
                                        <span class="text-sm text-slate-600 font-medium">
                                            {{ $ticket->latestLog && $ticket->latestLog->logger ? $ticket->latestLog->logger->first_name . ' ' . $ticket->latestLog->logger->last_name : '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $priorityColor = $ticket->priority->priority_color ?? 'slate-500';
                                        $priorityName = $ticket->priority->priority_name ?? 'Normal';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #{{ $priorityColor }}">
                                        {{ $priorityName }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColor = $ticket->status->status_color ?? 'slate-500';
                                        $statusName = $ticket->status->status_name ?? 'Open';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #{{ $statusColor }}">
                                        {{ $statusName }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('emp.tickets.show', $ticket->ticket_id) }}"
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
                                        <button onclick="openModal('newTicketModal')"
                                            class="text-brand-dark hover:text-brand-light font-bold text-sm">
                                            Create your first ticket
                                        </button>
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

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        // Initialize Attachment Preview
        window.initAttachmentPreview({
            inputSelector: '#ticket_attachment',
            containerSelector: '#ticket-attachment-preview'
        });

        // Initialize AJAX Pagination
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('emp.tickets.data', ['stt' => $stt]) }}", // Pass current filter
            containerSelector: '#tickets-container',
            paginationSelector: '#tickets-pagination',
            perPage: 10,
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
                                    <button onclick="openModal('newTicketModal')"
                                        class="text-brand-dark hover:text-brand-light font-bold text-sm">
                                        Create your first ticket
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                let html = '';
                tickets.forEach(ticket => {
                    const showUrl = `{{ route('emp.tickets.show', ':id') }}`.replace(':id', ticket.ticket_id);
                    
                    // Logger info
                    let loggerInitial = '-';
                    let loggerName = '-';
                    if (ticket.latest_log && ticket.latest_log.logger) {
                        loggerInitial = (ticket.latest_log.logger.first_name.charAt(0) + ticket.latest_log.logger.last_name.charAt(0)).toUpperCase();
                        loggerName = ticket.latest_log.logger.first_name + ' ' + ticket.latest_log.logger.last_name;
                    }

                    // Date parsing
                    const updatedDate = ticket.last_updated_date ? new Date(ticket.last_updated_date).toLocaleDateString() : '-';

                    html += `
                        <tr>
                            <td>
                                <span class="font-mono text-sm font-semibold text-slate-600">${ticket.ticket_ref}</span>
                            </td>
                            <td class="max-w-xs">
                                <span class="font-semibold text-slate-800 block truncate"
                                    title="${ticket.ticket_subject}">${ticket.ticket_subject}</span>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-medium">
                                    <i class="fa-solid fa-tag text-xs"></i>
                                    ${ticket.category ? ticket.category.category_name : 'N/A'}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">
                                    ${updatedDate}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                                        ${loggerInitial}
                                    </div>
                                    <span class="text-sm text-slate-600 font-medium">
                                        ${loggerName}
                                    </span>
                                </div>
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
                                        title="View Details">
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

        // Use server-side rendered data for initial page load
        @if($tickets->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $tickets->currentPage() }},
                last_page: {{ $tickets->lastPage() }},
                from: {{ $tickets->firstItem() ?? 0 }},
                to: {{ $tickets->lastItem() ?? 0 }},
                total: {{ $tickets->total() }}
            });
        @endif
    </script>
    @endpush

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

            <form action="{{ route('emp.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Added By
                            </label>
                            <select name="added_by" class="premium-input w-full px-4 py-3 text-sm" required>
                                @foreach($deptEmployees as $emp)
                                    <option value="{{ $emp->employee_id }}" {{ Auth::user()->employee && Auth::user()->employee->employee_id == $emp->employee_id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                    </div>

                    <div class="grid grid-cols-2 gap-4">
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
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-heading text-indigo-600 mr-2"></i>Subject
                            </label>
                            <input type="text" name="ticket_subject" class="premium-input w-full px-4 py-3 text-sm"
                                placeholder="Brief description" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-align-left text-indigo-600 mr-2"></i>Description
                        </label>
                        <textarea name="ticket_description" class="premium-input w-full px-4 py-3 text-sm" rows="4"
                            placeholder="Provide details about your issue..." required></textarea>
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
                        class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
