@extends('layouts.app')

@section('title', 'Leaves Management')
@section('subtitle', 'Review and manage leave requests')

@section('content')
    <div class="space-y-6">
        @include('hr.partials.requests_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Leave Requests</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $leaves->total() }} total requests</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="exportCsv()"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export CSV</span>
                </button>
                <button onclick="openModal('addLeaveModal')"
                    class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Leave</span>
                </button>
            </div>
        </div>
        <!-- Filters -->
        <div class="premium-card p-4 animate-fade-in-up">
            <div class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="employee-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="search-filter" placeholder="Ref No..." class="premium-input w-full pl-11 py-2.5 text-sm">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="type-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type->leave_type_id }}">{{ $type->leave_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="status-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Statuses</option>
                            <option value="{{ \App\Models\HrLeave::STATUS_PENDING }}">Pending Manager</option>
                             <option value="{{ \App\Models\HrLeave::STATUS_PENDING_APPROVAL }}">With Manager</option>
                             <option value="{{ \App\Models\HrLeave::STATUS_PENDING_GM }}">Pending GM</option>
                             <option value="{{ \App\Models\HrLeave::STATUS_APPROVED }}">Approved</option>
                             <option value="{{ \App\Models\HrLeave::STATUS_REJECTED }}">Rejected</option>
                             <option value="{{ \App\Models\HrLeave::STATUS_ACTION_REQUIRED }}">Pending Employee</option>
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" id="start-date-filter" class="premium-input w-full pl-11 py-2.5 text-sm" placeholder="From Date">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" id="end-date-filter" class="premium-input w-full pl-11 py-2.5 text-sm" placeholder="To Date">
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button onclick="applyFilters()" class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:bg-slate-900 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-search text-xs"></i>
                        <span>Search</span>
                    </button>
                    <button onclick="resetFilters()" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['pending'] }}</p> 
                    </div>
                </div>
            </div>
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-check text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Approved</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['approved'] }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-times text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Rejected</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['rejected'] }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Total</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaves Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">REF</th>
                            <th class="text-left">Employee</th>
                            <th class="text-left">Type</th>
                            <th class="text-left">Duration</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">View</th>
                        </tr>
                    </thead>
                    <tbody id="leaves-container">
                        @forelse($leaves as $leave)
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">#{{ $leave->leave_id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                            @if($leave->employee)
                                                {{ strtoupper(substr($leave->employee->first_name, 0, 1)) }}{{ strtoupper(substr($leave->employee->last_name, 0, 1)) }}
                                            @else
                                                U
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-semibold text-slate-800">
                                                @if($leave->employee)
                                                    {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                                                @else
                                                    <span class="text-red-500 italic">Unknown Employee
                                                        ({{ $leave->employee_id }})</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                        <i class="fa-solid fa-tag text-xs"></i>
                                        {{ $leave->type->leave_type_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <i class="fa-solid fa-calendar-day text-xs text-slate-400"></i>
                                            <span>{{ $leave->start_date ? $leave->start_date->format('M d, Y') : '-' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-600 mt-1">
                                            <i class="fa-solid fa-calendar-check text-xs text-slate-400"></i>
                                            <span>{{ $leave->end_date ? $leave->end_date->format('M d, Y') : '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusConfig = match ($leave->leave_status_id) {
                                        \App\Models\HrLeave::STATUS_PENDING          => ['bg' => 'from-yellow-400 to-amber-500', 'text' => 'Pending Manager', 'icon' => 'clock'],
                                        \App\Models\HrLeave::STATUS_PENDING_APPROVAL  => ['bg' => 'from-blue-500 to-cyan-600',   'text' => 'With Manager',    'icon' => 'user-check'],
                                        \App\Models\HrLeave::STATUS_PENDING_GM        => ['bg' => 'from-amber-500 to-orange-500','text' => 'Pending GM',      'icon' => 'crown'],
                                        \App\Models\HrLeave::STATUS_APPROVED          => ['bg' => 'from-green-500 to-emerald-600','text' => 'Approved',        'icon' => 'check-double'],
                                        \App\Models\HrLeave::STATUS_REJECTED          => ['bg' => 'from-red-500 to-rose-600',    'text' => 'Rejected',        'icon' => 'times-circle'],
                                        \App\Models\HrLeave::STATUS_ACTION_REQUIRED   => ['bg' => 'from-purple-500 to-indigo-600','text' => 'Pending Employee','icon' => 'user-edit'],
                                        default => ['bg' => 'from-slate-400 to-slate-500', 'text' => 'Unknown', 'icon' => 'question']
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $statusConfig['bg'] }} text-white text-xs font-bold shadow-md whitespace-nowrap">
                                        <i class="fa-solid fa-{{ $statusConfig['icon'] }}"></i>
                                        {{ $statusConfig['text'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">

                                        @if($leave->leave_attachment && $leave->leave_attachment != 'no-img.png')
                                            <a href="{{ asset('uploads/' . $leave->leave_attachment) }}" target="_blank"
                                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                                title="View Attachment">
                                                <i class="fa-solid fa-paperclip text-sm"></i>
                                            </a>
                                        @endif

                                        <button onclick="openViewModal({{ json_encode($leave) }})"
                                             class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-400 to-slate-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                             title="View Details">
                                             <i class="fa-solid fa-eye text-sm"></i>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-calendar-days text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No leave requests found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- AJAX Pagination -->
            <div id="leaves-pagination"></div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('hr.leaves.data') }}",
            containerSelector: '#leaves-container',
            paginationSelector: '#leaves-pagination',
            getAdditionalParams: () => ({
                employee_id: document.getElementById('employee-filter').value,
                search: document.getElementById('search-filter').value,
                type_id: document.getElementById('type-filter').value,
                status_id: document.getElementById('status-filter').value,
                start_date: document.getElementById('start-date-filter').value,
                end_date: document.getElementById('end-date-filter').value
            }),
            renderCallback: function(leaves) {
                const container = document.querySelector('#leaves-container');
                if (leaves.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-calendar-days text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No leave requests found matching filters</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                let html = '';
                leaves.forEach(leave => {
                    let initials = 'U';
                    let fullName = `<span class="text-red-500 italic">Unknown Employee (${leave.employee_id})</span>`;
                    if (leave.employee) {
                        initials = (leave.employee.first_name.charAt(0) + leave.employee.last_name.charAt(0)).toUpperCase();
                        fullName = leave.employee.first_name + ' ' + leave.employee.last_name;
                    }

                    const startDate = leave.start_date ? new Date(leave.start_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-';
                    const endDate = leave.end_date ? new Date(leave.end_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-';

                    let statusConfig = { bg: 'from-slate-400 to-slate-500', text: 'Unknown', icon: 'question' };
                    switch(parseInt(leave.leave_status_id)) {
                        case {{ \App\Models\HrLeave::STATUS_PENDING }}:         statusConfig = { bg: 'from-yellow-400 to-amber-500',  text: 'Pending Manager', icon: 'clock' }; break;
                        case {{ \App\Models\HrLeave::STATUS_PENDING_APPROVAL }}: statusConfig = { bg: 'from-blue-500 to-cyan-600',    text: 'With Manager',    icon: 'user-check' }; break;
                        case {{ \App\Models\HrLeave::STATUS_PENDING_GM }}:      statusConfig = { bg: 'from-amber-500 to-orange-500', text: 'Pending GM',      icon: 'crown' }; break;
                        case {{ \App\Models\HrLeave::STATUS_APPROVED }}:        statusConfig = { bg: 'from-green-500 to-emerald-600',text: 'Approved',        icon: 'check-double' }; break;
                        case {{ \App\Models\HrLeave::STATUS_REJECTED }}:        statusConfig = { bg: 'from-red-500 to-rose-600',     text: 'Rejected',        icon: 'times-circle' }; break;
                        case {{ \App\Models\HrLeave::STATUS_ACTION_REQUIRED }}: statusConfig = { bg: 'from-purple-500 to-indigo-600',text: 'Pending Employee',icon: 'user-edit' }; break;
                    }

                    let actionsHtml = '';
                    if (leave.leave_attachment && leave.leave_attachment !== 'no-img.png') {
                        actionsHtml += `
                            <a href="/uploads/${leave.leave_attachment}" target="_blank"
                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                title="View Attachment">
                                <i class="fa-solid fa-paperclip text-sm"></i>
                            </a>
                        `;
                    }
                    actionsHtml += `
                        <button onclick='openViewModal(${JSON.stringify(leave)})'
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-400 to-slate-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                            title="View Details">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    `;

                    html += `
                        <tr>
                            <td><span class="font-mono text-sm font-semibold text-slate-600">#${leave.leave_id}</span></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                        ${initials}
                                    </div>
                                    <div><span class="font-semibold text-slate-800">${fullName}</span></div>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                    <i class="fa-solid fa-tag text-xs"></i>
                                    ${leave.type ? leave.type.leave_type_name : 'N/A'}
                                </span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <i class="fa-solid fa-calendar-day text-xs text-slate-400"></i>
                                        <span>${startDate}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-600 mt-1">
                                        <i class="fa-solid fa-calendar-check text-xs text-slate-400"></i>
                                        <span>${endDate}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r ${statusConfig.bg} text-white text-xs font-bold shadow-md whitespace-nowrap">
                                    <i class="fa-solid fa-${statusConfig.icon}"></i>
                                    ${statusConfig.text}
                                </span>
                            </td>
                            <td><div class="flex items-center justify-center gap-2">${actionsHtml}</div></td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Initial pagination setup
        @if($leaves->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $leaves->currentPage() }},
                last_page: {{ $leaves->lastPage() }},
                from: {{ $leaves->firstItem() }},
                to: {{ $leaves->lastItem() }},
                total: {{ $leaves->total() }}
            });
        @endif

        function applyFilters() {
            window.ajaxPagination.loadPage(1);
        }

        function exportCsv() {
            const params = new URLSearchParams({
                employee_id: document.getElementById('employee-filter').value,
                search:      document.getElementById('search-filter').value,
                type_id:     document.getElementById('type-filter').value,
                status_id:   document.getElementById('status-filter').value,
                start_date:  document.getElementById('start-date-filter').value,
                end_date:    document.getElementById('end-date-filter').value,
            });
            // Remove empty params
            for (const [key, value] of [...params.entries()]) {
                if (!value) params.delete(key);
            }
            window.location.href = '{{ route("hr.leaves.export") }}?' + params.toString();
        }

        function resetFilters() {
            window.location.href = "{{ route('hr.leaves.index') }}";
        }
    </script>
    @endpush

    <!-- Create Modal -->
    @include('hr.leaves.create')

    <!-- View Details Modal -->
    <div class="modal" id="viewLeaveModal">
        <div class="modal-backdrop" onclick="closeModal('viewLeaveModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Leave Details</h2>
                    <p class="text-slate-500 text-xs mt-1 font-bold" id="view_ref_no">#---</p>
                </div>
                <button onclick="closeModal('viewLeaveModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="space-y-6">
                <div class="info-item">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Employee
                    </label>
                    <p class="font-bold text-slate-800 px-4" id="view_employee">---</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="info-item">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Leave Type
                        </label>
                        <p class="font-bold text-slate-600 px-4" id="view_type">---</p>
                    </div>
                    <div class="info-item">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-signal text-indigo-600 mr-2"></i>Status
                        </label>
                        <div id="view_status_badge" class="px-4">
                            <!-- Badge injected by JS -->
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="info-item">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Start Date
                        </label>
                        <p class="font-bold text-slate-600 px-4" id="view_start_date">---</p>
                    </div>
                    <div class="info-item">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>End Date
                        </label>
                        <p class="font-bold text-slate-600 px-4" id="view_end_date">---</p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl text-center">
                    <p class="text-sm text-blue-800">
                        Total working days: <span class="font-bold text-lg ml-1" id="view_total_days">0</span> days
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Employee Reason
                    </label>
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-600 min-h-[60px]"
                        id="view_reason">
                        ---
                    </div>
                </div>

                <div id="view_hr_remark_box" style="display: none;">
                    <label class="block text-sm font-semibold text-purple-700 mb-2">
                        <i class="fa-solid fa-comment-dots text-purple-600 mr-2"></i>Latest Log Remark
                    </label>
                    <div class="p-4 bg-purple-50 border border-purple-100 rounded-xl text-sm text-purple-900 italic font-medium"
                        id="view_hr_remark">
                        ---
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submitted: <span
                            class="text-slate-600" id="view_submission">---</span></span>
                    <div id="view_attachment_container">
                        <!-- Button injected by JS -->
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-6 border-t border-slate-200">
                <button onclick="closeModal('viewLeaveModal')"
                    class="px-6 py-3 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- Status change modal removed: HR is read-only. Approval is handled by Line Manager → GM flow. --}}

    <script>
        function openViewModal(leave) {
            document.getElementById('view_ref_no').innerText = 'Ref #' + leave.leave_id;
            document.getElementById('view_employee').innerText = leave.employee ? (leave.employee.first_name + ' ' + leave.employee.last_name) : 'Unknown Employee';
            document.getElementById('view_type').innerText = leave.type ? leave.type.leave_type_name : 'Unknown';
            document.getElementById('view_start_date').innerText = new Date(leave.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            document.getElementById('view_end_date').innerText = new Date(leave.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            document.getElementById('view_total_days').innerText = leave.total_days;
            document.getElementById('view_submission').innerText = new Date(leave.submission_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            document.getElementById('view_reason').innerText = leave.leave_remarks || 'No reason provided';

            // Status Badge
            const statusConfig = {
                1: {bg: 'from-yellow-400 to-amber-500',  text: 'Pending Manager', icon: 'clock'},
                2: {bg: 'from-blue-500 to-cyan-600',     text: 'With Manager',    icon: 'user-check'},
                5: {bg: 'from-amber-500 to-orange-500',  text: 'Pending GM',      icon: 'crown'},
                3: {bg: 'from-green-500 to-emerald-600', text: 'Approved',        icon: 'check-double'},
                4: {bg: 'from-red-500 to-rose-600',      text: 'Rejected',        icon: 'times-circle'},
                6: {bg: 'from-purple-500 to-indigo-600', text: 'Pending Employee',icon: 'user-edit'},
                default: {bg: 'from-slate-400 to-slate-500', text: 'Unknown', icon: 'question'}
            };
            const config = statusConfig[leave.leave_status_id] || statusConfig.default;
            document.getElementById('view_status_badge').innerHTML = `
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r ${config.bg} text-white text-xs font-bold shadow-md whitespace-nowrap">
                    <i class="fa-solid fa-${config.icon}"></i>
                    ${config.text}
                </span>
            `;

            // Logs / Remarks
            if (leave.latest_log && leave.latest_log.log_remark && leave.latest_log.log_remark !== '---') {
                document.getElementById('view_hr_remark_box').style.display = 'block';
                document.getElementById('view_hr_remark').innerText = leave.latest_log.log_remark;
            } else {
                document.getElementById('view_hr_remark_box').style.display = 'none';
            }

            // Attachment
            const attachContainer = document.getElementById('view_attachment_container');
            if (leave.leave_attachment && leave.leave_attachment !== 'no-img.png') {
                attachContainer.innerHTML = `
                    <a href="/uploads/${leave.leave_attachment}" target="_blank"
                        class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg font-bold text-xs hover:bg-indigo-100 transition-colors border border-indigo-100 shadow-sm">
                        <i class="fa-solid fa-paperclip"></i>
                        VIEW ATTACHMENT
                    </a>
                `;
            } else {
                attachContainer.innerHTML = '';
            }

            openModal('viewLeaveModal');
        }

        // openStatusModal removed — HR is read-only in the new LM → GM approval flow.
    </script>
@endsection