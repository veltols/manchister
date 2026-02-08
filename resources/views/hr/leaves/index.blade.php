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
            <button onclick="openModal('addLeaveModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Add Leave</span>
            </button>
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
                        <p class="text-2xl font-bold text-slate-800">{{ $leaves->where('leave_status_id', 0)->count() }}</p>
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
                        <p class="text-2xl font-bold text-slate-800">{{ $leaves->where('leave_status_id', 100)->count() }}
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
                        <p class="text-2xl font-bold text-slate-800">{{ $leaves->where('leave_status_id', 200)->count() }}
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
                        <p class="text-2xl font-bold text-slate-800">{{ $leaves->count() }}</p>
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
                            <th class="text-center">Actions</th>
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
                                            1 => ['bg' => 'from-yellow-400 to-amber-500', 'text' => 'Pending HR', 'icon' => 'clock'],
                                            2 => ['bg' => 'from-blue-500 to-cyan-600', 'text' => 'Pending Manager', 'icon' => 'user-check'],
                                            3 => ['bg' => 'from-green-500 to-emerald-600', 'text' => 'Approved', 'icon' => 'check-double'],
                                            4 => ['bg' => 'from-red-500 to-rose-600', 'text' => 'Rejected', 'icon' => 'times-circle'],
                                            6 => ['bg' => 'from-purple-500 to-indigo-600', 'text' => 'Pending Employee', 'icon' => 'user-edit'],
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
                                        @if($leave->leave_status_id == 1)
                                            <button onclick="openStatusModal({{ $leave->leave_id }}, 100, 'Send for Approval')"
                                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                                title="Send for Approval">
                                                <i class="fa-solid fa-share text-sm"></i>
                                            </button>
                                            <button onclick="openStatusModal({{ $leave->leave_id }}, 200, 'Send Back to User')"
                                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                                title="Send Back to User">
                                                <i class="fa-solid fa-user-pen text-sm"></i>
                                            </button>
                                        @endif
                                        
                                        @if($leave->leave_status_id == 2)
                                             <form action="{{ route('hr.leaves.status', $leave->leave_id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <input type="hidden" name="status_id" value="3">
                                                <button type="submit"
                                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                                    title="Quick Approve">
                                                    <i class="fa-solid fa-check text-sm"></i>
                                                </button>
                                             </form>
                                        @endif

                                        @if($leave->leave_attachment && $leave->leave_attachment != 'no-img.png')
                                            <a href="{{ asset('uploads/' . $leave->leave_attachment) }}" target="_blank"
                                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                                title="View Attachment">
                                                <i class="fa-solid fa-paperclip text-sm"></i>
                                            </a>
                                        @endif

                                        <button
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
                                    <p class="text-slate-500 font-medium">No leave requests found</p>
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
                        case 1: statusConfig = { bg: 'from-yellow-400 to-amber-500', text: 'Pending HR', icon: 'clock' }; break;
                        case 2: statusConfig = { bg: 'from-blue-500 to-cyan-600', text: 'Pending Manager', icon: 'user-check' }; break;
                        case 3: statusConfig = { bg: 'from-green-500 to-emerald-600', text: 'Approved', icon: 'check-double' }; break;
                        case 4: statusConfig = { bg: 'from-red-500 to-rose-600', text: 'Rejected', icon: 'times-circle' }; break;
                        case 6: statusConfig = { bg: 'from-purple-500 to-indigo-600', text: 'Pending Employee', icon: 'user-edit' }; break;
                    }

                    let actionsHtml = '';
                    if (leave.leave_status_id == 1) {
                        actionsHtml += `
                            <button onclick="openStatusModal(${leave.leave_id}, 100, 'Send for Approval')"
                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                title="Send for Approval">
                                <i class="fa-solid fa-share text-sm"></i>
                            </button>
                            <button onclick="openStatusModal(${leave.leave_id}, 200, 'Send Back to User')"
                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                title="Send Back to User">
                                <i class="fa-solid fa-user-pen text-sm"></i>
                            </button>
                        `;
                    }
                    if (leave.leave_status_id == 2) {
                        actionsHtml += `
                            <form action="/hr/leaves/${leave.leave_id}/status" method="POST" class="inline">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="status_id" value="3">
                                <button type="submit"
                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                    title="Quick Approve">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </button>
                            </form>
                        `;
                    }
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
                        <button class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-400 to-slate-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
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
    </script>
    @endpush

    <!-- Create Modal -->
    @include('hr.leaves.create')

    <!-- Status Change Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-backdrop" onclick="closeModal('statusModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-display font-bold text-premium" id="statusModalTitle">Update Leave Status</h2>
                <button onclick="closeModal('statusModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="statusForm" action="" method="POST">
                @csrf
                <input type="hidden" name="status_id" id="modal_status_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-comment-dots text-indigo-600 mr-2"></i>Status Remarks
                        </label>
                        <textarea name="log_remark" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Reason for this status change..." required></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('statusModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-8 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        Confirm Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openStatusModal(id, statusId, title) {
            document.getElementById('statusModalTitle').innerText = title;
            document.getElementById('modal_status_id').value = statusId;
            document.getElementById('statusForm').action = "/hr/leaves/" + id + "/status";
            openModal('statusModal');
        }
    </script>
@endsection