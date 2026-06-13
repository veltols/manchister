@extends('layouts.app')

@section('title', 'My Tickets')
@section('subtitle', 'Support requests and issues')

@section('content')
    <div class="space-y-6">

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">IT Tickets</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $tickets->total() }} total tickets</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- <a href="{{ route('emp.requests.index') }}"
                    class="px-4 py-2 rounded-lg font-medium text-sm text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-hand-sparkles mr-1"></i> HR Requests
                </a>
                <a href="{{ route('emp.ss.index') }}"
                    class="px-4 py-2 rounded-lg font-medium text-sm text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fa-solid fa-headset mr-1"></i> Admin Services
                </a> --}}
                <button onclick="openModal('newTicketModal')"
                    class="ml-2 inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>New Ticket</span>
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="premium-card p-2 flex flex-col sm:flex-row justify-between items-center gap-4">
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
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 4 ? 'bg-slate-700 text-white shadow-lg' : 'text-slate-500 hover:text-slate-700' }}">
                    Cancelled
                </a>
                @php
                    $myEmpId = Auth::user()->employee->employee_id ?? 0;
                    $isLM = \App\Models\Department::where('line_manager_id', $myEmpId)->exists();
                    $isGM = Auth::user()->is_gm;
                @endphp
                @if($isLM || $isGM)
                <a href="{{ route('emp.tickets.index', ['stt' => 5]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 5 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Pending Approvals
                </a>
                @endif
            </div>
            <form action="{{ route('emp.tickets.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                @if(request('stt'))
                    <input type="hidden" name="stt" value="{{ request('stt') }}">
                @endif
                <select name="priority_id" class="premium-input px-4 py-2 text-sm w-full max-w-[150px]" onchange="this.form.submit()">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->priority_id }}" {{ request('priority_id') == $priority->priority_id ? 'selected' : '' }}>
                            {{ $priority->priority_name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" placeholder="Search Reference No..." value="{{ request('search') }}" class="premium-input px-4 py-2 text-sm max-w-[250px] w-full" />
                <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>
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
                    <a href="{{ route('emp.tickets.index', ['stt' => 3]) }}"
                        class="flex-none px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all border flex items-center gap-2.5 {{ !request('month') ? 'bg-gradient-brand text-white shadow-lg border-transparent' : 'bg-white text-slate-500 border-slate-100 hover:border-indigo-200 hover:text-indigo-600' }}">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                        <span>All Time</span>
                    </a>

                    @foreach($resolvedMonths as $month)
                        <a href="{{ route('emp.tickets.index', ['stt' => 3, 'month' => $month->month_value]) }}"
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

            @if(true)
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
                                    <span class="inline-block px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider mb-2">
                                        {{ $ticket->category->category_name ?? 'N/A' }}
                                    </span>
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
                                            {{ $ticket->ticket_end_date ? \Carbon\Carbon::parse($ticket->ticket_end_date)->format('M d, Y') : '-' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('emp.tickets.show', $ticket->ticket_id) }}"
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
                                <p class="text-slate-500 text-sm">You don't have any resolved tickets at the moment.</p>
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
                                <th class="text-left">Added By</th>
                                    <th class="text-left">Assigned To</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tickets-container">
                            @forelse($tickets as $ticket)
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
                                        @php
                                            $priorityColor = $ticket->priority->priority_color ?? 'slate-500';
                                            $priorityName = $ticket->priority->priority_name ?? 'Normal';
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md"
                                            style="background: #{{ $priorityColor }}">
                                            {{ $priorityName }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusColor = $ticket->status->status_color ?? 'slate-500';
                                            $statusName = $ticket->status->status_name ?? 'Open';
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-md"
                                            style="background: #{{ $statusColor }}">
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

            // Initialize AJAX Pagination
            window.ajaxPagination = new AjaxPagination({
                endpoint: "{{ route('emp.tickets.data', ['stt' => $stt, 'search' => request('search'), 'priority_id' => request('priority_id')]) }}", // Pass current filter
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
                                        <p class="text-slate-500 text-sm">You don't have any resolved tickets at the moment.</p>
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
                                            <button onclick="openModal('newTicketModal')"
                                                class="text-brand-dark hover:text-brand-light font-bold text-sm">
                                                Create your first ticket
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                        return;
                    }

                    let html = '';
                    tickets.forEach(ticket => {
                        const showUrl = `{{ route('emp.tickets.show', ':id') }}`.replace(':id', ticket.ticket_id);
                        const priorityColor = ticket.priority ? ticket.priority.priority_color : 'ccc';
                        const priorityName = ticket.priority ? ticket.priority.priority_name : 'Normal';
                        const categoryName = ticket.category ? ticket.category.category_name : 'N/A';
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
                                            <span class="inline-block px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider mb-2">
                                                ${categoryName}
                                            </span>
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
                    {{-- <div class="grid grid-cols-2 gap-4"> --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Reported By
                            </label>
                            @php
                                $isAdmin = in_array(Auth::user()->user_type, ['root', 'sys_admin']);
                            @endphp
                            <select name="added_by" class="premium-input w-full px-4 py-3 text-sm" required {{ !$isAdmin ? 'disabled' : '' }}>
                                @if(!$isAdmin)
                                    <option value="{{ Auth::user()->employee->employee_id ?? '' }}" selected>
                                        {{ Auth::user()->employee->first_name ?? '' }} {{ Auth::user()->employee->last_name ?? '' }}
                                    </option>
                                @else
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->employee_id }}" {{ Auth::user()->employee && Auth::user()->employee->employee_id == $emp->employee_id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @if(!$isAdmin)
                                <input type="hidden" name="added_by" value="{{ Auth::user()->employee->employee_id ?? '' }}">
                            @endif
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-user-shield text-indigo-600 mr-2"></i>Assigned To
                            </label>
                            <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm" required>
                                <option value="">Select Assignee...</option>
                              
                                @foreach($groupedEmployees as $dept)
                                    @if($dept->employees->count() > 0)
                                        <optgroup label="{{ $dept->department_name }}">
                                            @foreach($dept->employees as $emp)
                                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div> --}}
                    {{-- </div> --}}

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
                            <i class="fa-solid fa-heading text-indigo-600 mr-2"></i>Subject
                        </label>
                        <input type="text" name="ticket_subject" class="premium-input w-full px-4 py-3 text-sm"
                            placeholder="Brief description" required>
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