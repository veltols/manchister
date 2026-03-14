@extends('layouts.app')

@section('title', 'Help Desk Tickets')
@section('subtitle', 'Manage support requests and technical issues')

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
                <a href="{{ route('hr.tickets.index') }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 0 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    All Tickets
                </a>
                <a href="{{ route('hr.tickets.index', ['stt' => 1]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 1 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Open
                </a>
                <a href="{{ route('hr.tickets.index', ['stt' => 2]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 2 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    In Progress
                </a>
                <a href="{{ route('hr.tickets.index', ['stt' => 3]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 3 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Resolved
                </a>
                <a href="{{ route('hr.tickets.index', ['stt' => 4]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 4 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Unassigned
                </a>
            </div>
        </div>

        <!-- Monthly Resolved Filter Slider -->
        @if(isset($resolvedMonths) && count($resolvedMonths) > 0)
            <div class="relative group/slider animate-fade-in-up">
                <!-- Scroll Buttons -->
                <button onclick="scrollSlider('resolved-slider', -200)" 
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-white/80 backdrop-blur-md shadow-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:scale-110 transition-all opacity-0 group-hover/slider:opacity-100 -ml-4">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                
                <button onclick="scrollSlider('resolved-slider', 200)" 
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-white/80 backdrop-blur-md shadow-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:scale-110 transition-all opacity-0 group-hover/slider:opacity-100 -mr-4">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>

                <div id="resolved-slider" class="flex items-center gap-3 overflow-x-auto pb-3 no-scrollbar scroll-smooth">
                    <!-- All Time Tab -->
                    <a href="{{ route('hr.tickets.index', ['stt' => 3]) }}"
                        class="flex-none px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all border flex items-center gap-2.5 {{ !request('month') ? 'bg-gradient-brand text-white shadow-lg border-transparent' : 'bg-white text-slate-500 border-slate-100 hover:border-indigo-200 hover:text-indigo-600' }}">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                        <span>All Time</span>
                    </a>

                    @foreach($resolvedMonths as $month)
                        <a href="{{ route('hr.tickets.index', ['stt' => 3, 'month' => $month->month_value]) }}"
                            class="flex-none px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all border flex items-center gap-3 {{ request('month') == $month->month_value ? 'bg-gradient-brand text-white shadow-lg border-transparent' : 'bg-white text-slate-500 border-slate-100 hover:border-indigo-200 hover:text-indigo-600' }}">
                            
                            <span>{{ $month->month_label }}</span>
                            
                            <!-- Count Badge -->
                            <span class="inline-flex items-center justify-center min-w-[20px] h-[20px] px-1.5 rounded-full text-[10px] font-bold shadow-sm transition-all {{ request('month') == $month->month_value ? 'bg-white text-indigo-600' : ($month->total > 0 ? 'bg-indigo-600 text-white' : 'bg-slate-50 text-slate-400 border border-slate-100') }}">
                                {{ $month->total }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            
            <script>
                function scrollSlider(id, amount) {
                    const slider = document.getElementById(id);
                    slider.scrollLeft += amount;
                }
            </script>

            <style>
                .no-scrollbar::-webkit-scrollbar { display: none; }
                .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>
        @endif

        <!-- Tickets Area -->
        <div class="space-y-4">
            @if($stt == 3 && !request('month'))
                <!-- Initial State for Resolved Tickets: Show Message -->
                <div class="premium-card py-20 flex flex-col items-center justify-center gap-6 animate-fade-in-up">
                    <div class="w-24 h-24 rounded-full bg-indigo-50 flex items-center justify-center relative">
                        <div class="absolute inset-0 rounded-full bg-indigo-100 animate-pulse"></div>
                        <i class="fa-solid fa-calendar-check text-4xl text-indigo-600 relative z-10 transition-transform hover:scale-110"></i>
                    </div>
                    <div class="text-center max-w-md mx-auto px-6">
                        <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Monthly Resolved Archives</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6">
                            Select a specific month from the blocks above to view the resolved tickets for that period. 
                            This helps in organized auditing and performance review.
                        </p>
                        <div class="flex items-center justify-center gap-2 text-indigo-600 font-bold text-xs uppercase tracking-widest">
                            <i class="fa-solid fa-arrow-up animate-bounce"></i>
                            <span>Select a Month Above</span>
                        </div>
                    </div>
                </div>
            @endif

            @if($stt != 3 || request('month'))
                @if($stt == 3)
                    <!-- Grid/Box View for Resolved Tickets -->
                    <div id="tickets-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($tickets as $ticket)
                        <div class="premium-card p-0 overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                            <!-- Card Header -->
                            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <span class="font-mono text-xs font-bold text-slate-500">{{ $ticket->ticket_ref }}</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-white text-[10px] font-bold shadow-sm"
                                    style="background: #{{ $ticket->priority->priority_color ?? 'ccc' }}">
                                    {{ $ticket->priority->priority_name ?? 'Normal' }}
                                 </span>
                            </div>
                            <!-- Card Body -->
                            <div class="p-5">
                                <div class="mb-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="inline-block px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider">
                                            {{ $ticket->category->category_name ?? 'N/A' }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400">By: {{ $ticket->addedBy->first_name ?? 'System' }} {{ $ticket->addedBy->last_name ?? '' }}</span>
                                    </div>
                                    <h3 class="text-slate-800 font-bold leading-snug line-clamp-2 h-10 mb-1" title="{{ $ticket->ticket_subject }}">
                                        {{ $ticket->ticket_subject }}
                                    </h3>
                                    <p class="text-slate-500 text-xs line-clamp-2 mt-2 leading-relaxed">
                                        {{ Str::limit($ticket->ticket_description, 100) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Resolved on</span>
                                        <span class="text-xs text-slate-600 font-medium">
                                            {{ $ticket->last_updated_date ? \Carbon\Carbon::parse($ticket->last_updated_date)->format('M d, Y') : '-' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('hr.tickets.show', $ticket->ticket_id) }}" 
                                       class="w-10 h-10 rounded-xl bg-gradient-brand text-white flex items-center justify-center shadow-lg shadow-brand/20 hover:scale-110 transition-all">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 premium-card flex flex-col items-center justify-center gap-4">
                            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                                <i class="fa-solid fa-check-double text-3xl text-slate-300"></i>
                            </div>
                            <div class="text-center">
                                <h3 class="text-slate-700 font-bold">No Resolved Tickets</h3>
                                <p class="text-slate-500 text-sm">No tickets have been resolved in this category yet.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            @else
                <!-- Table View for Other Statuses -->
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
                        <tbody id="tickets-container">
                            @forelse($tickets as $ticket)
                                            <!-- Initial server-side render -->
                                            <tr>
                                                <td>
                                                    <span
                                                        class="font-mono text-sm font-semibold text-slate-600">{{ $ticket->ticket_ref }}</span>
                                                </td>
                                                <td class="max-w-xs">
                                                    <span class="font-semibold text-slate-800 block truncate"
                                                        title="{{ $ticket->ticket_subject }}">{{ $ticket->ticket_subject }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-medium">
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
                                                        <div
                                                            class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
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
                             <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md"
                                                        style="background: #{{ $priorityColor }}">
                                                        {{ $priorityName }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusColor = $ticket->status->status_color ?? 'slate-500';
                                                        $statusName = $ticket->status->status_name ?? 'Open';
                                                    @endphp
                             <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md"
                                                        style="background: #{{ $statusColor }}">
                                                        {{ $statusName }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="{{ route('hr.tickets.show', $ticket->ticket_id) }}"
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
            @endif
        @endif

            <!-- AJAX Pagination Container -->
            <div id="tickets-pagination"></div>
        </div>

    </div>

    @push('scripts')
        <script src="{{ asset('js/ajax-pagination.js') }}"></script>
        <script src="{{ asset('libs/mammoth/mammoth.browser.min.js') }}"></script>
        <script src="{{ asset('js/attachment-preview.js') }}"></script>
        <script>
            function closeModal(id) {
                document.getElementById(id).classList.remove('active');
            }
            function openModal(id) {
                document.getElementById(id).classList.add('active');
            }

            // Initialize AJAX Pagination
            window.ajaxPagination = new AjaxPagination({
                endpoint: "{{ route('hr.tickets.data', ['stt' => $stt]) }}", // Pass current filter
                containerSelector: '#tickets-container',
                paginationSelector: '#tickets-pagination',
                perPage: 10,
                renderCallback: function (tickets) {
                    const container = document.querySelector('#tickets-container');
                    const isResolved = {{ $stt }} == 3;

                    if (tickets.length === 0) {
                       if (isResolved) {
                            container.innerHTML = `
                                <div class="col-span-full py-20 premium-card flex flex-col items-center justify-center gap-4">
                                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                                        <i class="fa-solid fa-check-double text-3xl text-slate-300"></i>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-slate-700 font-bold">No Resolved Tickets</h3>
                                        <p class="text-slate-500 text-sm">No tickets have been resolved in this category yet.</p>
                                    </div>
                                </div>
                            `;
                        } else {
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
                        }
                        return;
                    }

                    let html = '';
                    tickets.forEach(ticket => {
                        const showUrl = `{{ route('hr.tickets.show', ':id') }}`.replace(':id', ticket.ticket_id);
                        const priorityColor = ticket.priority ? ticket.priority.priority_color : 'ccc';
                        const priorityName = ticket.priority ? ticket.priority.priority_name : 'Normal';
                        const categoryName = ticket.category ? ticket.category.category_name : 'N/A';
                        const addedBy = ticket.added_by ? (ticket.added_by.first_name + ' ' + (ticket.added_by.last_name || '')) : 'System';
                        const statusColor = ticket.status ? ticket.status.status_color : 'ccc';
                        const statusName = ticket.status ? ticket.status.status_name : 'Open';
                        const resolvedDate = ticket.last_updated_date ? new Date(ticket.last_updated_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '-';
                        const description = ticket.ticket_description ? (ticket.ticket_description.length > 100 ? ticket.ticket_description.substring(0, 100) + '...' : ticket.ticket_description) : '';

                        if (isResolved) {
                            html += `
                                <div class="premium-card p-0 overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                        <span class="font-mono text-xs font-bold text-slate-500">${ticket.ticket_ref}</span>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-white text-[10px] font-bold shadow-sm" style="background: #${priorityColor}">
                                            ${priorityName}
                                        </span>
                                    </div>
                                    <div class="p-5">
                                        <div class="mb-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="inline-block px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider">
                                                    ${categoryName}
                                                </span>
                                                <span class="text-[10px] font-bold text-slate-400">By: ${addedBy}</span>
                                            </div>
                                            <h3 class="text-slate-800 font-bold leading-snug line-clamp-2 h-10 mb-1" title="${ticket.ticket_subject}">
                                                ${ticket.ticket_subject}
                                            </h3>
                                            <p class="text-slate-500 text-xs line-clamp-2 mt-2 leading-relaxed h-8">
                                                ${description}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Resolved on</span>
                                                <span class="text-xs text-slate-600 font-medium">${resolvedDate}</span>
                                            </div>
                                            <a href="${showUrl}" class="w-10 h-10 rounded-xl bg-gradient-brand text-white flex items-center justify-center shadow-lg shadow-brand/20 hover:scale-110 transition-all">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            // Logger info
                            let loggerInitial = '-';
                            let loggerName = '-';
                            if (ticket.latest_log && ticket.latest_log.logger) {
                                loggerInitial = (ticket.latest_log.logger.first_name.charAt(0) + (ticket.latest_log.logger.last_name ? ticket.latest_log.logger.last_name.charAt(0) : '')).toUpperCase();
                                loggerName = ticket.latest_log.logger.first_name + ' ' + (ticket.latest_log.logger.last_name || '');
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
                                            ${categoryName}
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
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #${priorityColor}">
                                            ${priorityName}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md" style="background: #${statusColor}">
                                            ${statusName}
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
                        }
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

            // Initialize Attachment Preview
            window.initAttachmentPreview({
                inputSelector: '#ticket_attachment',
                containerSelector: '#ticket-attachment-preview'
            });

            // File Size Validation (Max 8MB)
            const attachmentInput = document.getElementById('ticket_attachment');
            if (attachmentInput) {
                attachmentInput.addEventListener('change', function () {
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
                            // Clear preview if exists
                            const previewContainer = document.getElementById('ticket-attachment-preview');
                            if (previewContainer) previewContainer.innerHTML = '';
                        }
                    }
                });
            }
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

            <form action="{{ route('hr.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Added By (Employee)
                            </label>
                            <select name="added_by" class="premium-input w-full px-4 py-3 text-sm" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
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
                        <input type="file" name="ticket_attachment" id="ticket_attachment"
                            class="premium-input w-full px-4 py-3 text-sm">
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