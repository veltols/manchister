@extends('layouts.app')

@section('title', 'My Permissions')
@section('subtitle', 'Short time-off requests')

@section('content')
    <div class="space-y-6">

        <!-- Action Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Permission Requests</h2>
                <p class="text-sm text-slate-500 mt-1">Submit and track your short-term leave requests</p>
            </div>
            <div class="flex gap-3">
                @if($isManager)
                    <div class="flex bg-slate-100 p-1 rounded-xl">
                        <button onclick="switchTab('my-requests')" id="tab-my-requests" class="px-4 py-2 text-sm font-bold rounded-lg transition-all {{ $activeTab == 'my-requests' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">My Requests</button>
                        <button onclick="switchTab('approvals')" id="tab-approvals" class="px-4 py-2 text-sm font-bold rounded-lg transition-all {{ $activeTab == 'approvals' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                            Approvals
                            @if(count($awaitingApprovals) > 0)
                                <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white text-[10px] rounded-full">{{ count($awaitingApprovals) }}</span>
                            @endif
                        </button>
                    </div>
                @endif
                <button onclick="openModal('requestPermissionModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>New Request</span>
                </button>
            </div>
        </div>

        <div id="my-requests-section" class="{{ $activeTab == 'my-requests' ? '' : 'hidden' }}">

        <!-- Filters -->
        <div class="premium-card p-4 animate-fade-in">
            <div class="flex flex-col md:flex-row items-end gap-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 flex-1">
                    <div class="relative">
                        <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="search-filter" placeholder="Search Request#..." 
                            class="premium-input w-full pl-11 py-2.5 text-sm">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="status-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->permission_status_id }}">{{ $status->permission_status_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" id="start-date-filter" class="premium-input w-full pl-11 py-2.5 text-sm"
                            placeholder="From Date">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" id="end-date-filter" class="premium-input w-full pl-11 py-2.5 text-sm"
                            placeholder="To Date">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="applyFilters()" class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:bg-slate-900 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-search"></i>
                        <span>Search</span>
                    </button>
                    <button onclick="resetFilters()" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Permissions List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th>Request#</th>
                            <th class="text-left">Date</th>
                            <th class="text-left">Time Range</th>
                            <th class="text-center">Hours</th>
                            <th class="text-left">Remarks</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="permissions-container">
                        @forelse($permissions as $perm)
                            <tr>
                                <td>{{ $perm->permission_id }}</td>
                                <td class="font-bold text-slate-700">
                                    {{ $perm->submission_date ? $perm->submission_date->format('M d, Y') : '-' }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <span class="px-2 py-1 bg-slate-100 rounded-lg">{{ $perm->start_time }}</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                        <span class="px-2 py-1 bg-slate-100 rounded-lg">{{ $perm->end_time }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="font-bold text-indigo-600">{{ $perm->total_hours }}h</span>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-500 truncate" title="{{ $perm->permission_remarks }}">
                                        {{ $perm->permission_remarks }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusColors = [
                                            1 => ['bg' => '#64748b', 'text' => '#ffffff'], // Pending
                                            2 => ['bg' => '#f59e0b', 'text' => '#ffffff'], // Pending Approval
                                            3 => ['bg' => '#10b981', 'text' => '#ffffff'], // Approved
                                            4 => ['bg' => '#ef4444', 'text' => '#ffffff'], // Rejected
                                        ];
                                        $colors = $statusColors[$perm->permission_status_id] ?? ['bg' => '#64748b', 'text' => '#ffffff'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-sm"
                                        style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                        {{ $perm->status->permission_status_name ?? 'Pending' }}
                                    </span>
                                    @if($perm->is_exception)
                                        <span class="block mt-1 text-[9px] font-bold text-amber-600 uppercase tracking-tighter">Exception Granted</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                            <i class="fa-solid fa-clock text-2xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-medium">No permissions found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                    <!-- AJAX Pagination -->
                    <div id="permissions-pagination"></div>

                    @if (false && $permissions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $permissions->links() }}
                </div>
            @endif
        </div>

        @if($isManager)
        <div id="approvals-section" class="{{ $activeTab == 'approvals' ? '' : 'hidden' }} animate-fade-in">
            <div class="premium-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th class="text-left">Date</th>
                                <th class="text-left">Time Range</th>
                                <th class="text-center">Hours</th>
                                <th>Status</th>
                                <th class="text-left">Remarks</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($awaitingApprovals as $p)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                                {{ substr($p->employee->first_name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-700 text-sm">{{ $p->employee->first_name ?? 'Unknown' }} {{ $p->employee->last_name ?? '' }}</div>
                                                <div class="text-[10px] text-slate-400 uppercase tracking-widest">Employee #{{ $p->employee->employee_id ?? '?' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm font-semibold text-slate-600">
                                        {{ $p->start_date ? $p->start_date->format('M d, Y') : '-' }}
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2 text-xs text-slate-600">
                                            <span class="px-2 py-1 bg-slate-100 rounded-lg">{{ $p->start_time }}</span>
                                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                            <span class="px-2 py-1 bg-slate-100 rounded-lg">{{ $p->end_time }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center font-bold text-indigo-600 text-sm">
                                        {{ $p->total_hours }}h
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                1 => ['bg' => '#f1f5f9', 'text' => '#64748b'], // Pending
                                                2 => ['bg' => '#fef3c7', 'text' => '#d97706'], // Pending Approval
                                                3 => ['bg' => '#dcfce7', 'text' => '#10b981'], // Approved
                                                4 => ['bg' => '#fee2e2', 'text' => '#ef4444'], // Rejected
                                            ];
                                            $colors = $statusColors[$p->permission_status_id] ?? ['bg' => '#f1f5f9', 'text' => '#64748b'];
                                        @endphp
                                        <span class="px-2 py-1 rounded-lg text-[10px] font-bold" style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}">
                                            {{ $p->status->permission_status_name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="max-w-xs text-xs text-slate-500">
                                        {{ $p->permission_remarks }}
                                        @if($p->is_exception)
                                            <span class="block mt-1 text-[9px] font-bold text-amber-600 uppercase tracking-tighter">Exception Requested</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('emp.permissions.approve', $p->permission_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Approve">
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </button>
                                            </form>
                                            <button onclick="openRejectModal({{ $p->permission_id }})" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Reject">
                                                <i class="fa-solid fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                                <i class="fa-solid fa-check-double text-2xl"></i>
                                            </div>
                                            <p class="text-slate-400 font-medium">No pending approvals</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Request Permission Modal -->
    <div class="modal" id="requestPermissionModal">
        <div class="modal-backdrop" onclick="closeModal('requestPermissionModal')"></div>
        <div class="modal-content max-w-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">New Permission Request</h2>
                    <p class="text-xs text-slate-500 mt-1">Daily Limit: 3 hrs (Mon-Thu) | 1 hr (Friday)</p>
                </div>
                <button onclick="closeModal('requestPermissionModal')"
                    class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form action="{{ route('emp.permissions.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Date of
                        Permission</label>
                    <input type="date" name="permission_date" class="premium-input w-full" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Start
                            Time</label>
                        <input type="time" name="start_time" class="premium-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">End
                            Time</label>
                        <input type="time" name="end_time" class="premium-input w-full" required>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <input type="checkbox" name="is_exception" id="is_exception" value="1" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <label for="is_exception" class="text-sm font-bold text-slate-700 uppercase tracking-widest text-[10px]">Mark as Exception (Skip 8-hour monthly limit)</label>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Reason
                        / Remarks</label>
                    <textarea name="permission_remarks" rows="3" class="premium-input w-full"
                        placeholder="Briefly explain why you need this permission..." required></textarea>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('requestPermissionModal')"
                        class="px-6 py-3 font-bold text-slate-500 hover:bg-slate-50 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Permission Modal -->
    <div class="modal" id="rejectPermissionModal">
        <div class="modal-backdrop" onclick="closeModal('rejectPermissionModal')"></div>
        <div class="modal-content max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">Reject Request</h2>
                <button onclick="closeModal('rejectPermissionModal')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="rejectForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Rejection Reason</label>
                    <textarea name="reason" rows="3" class="premium-input w-full" placeholder="Why is this request being rejected?" required></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('rejectPermissionModal')" class="px-4 py-2 text-slate-500 hover:bg-slate-100 rounded-lg font-semibold">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-rose-600 text-white font-bold rounded-lg shadow-lg hover:bg-rose-700">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('emp.permissions.data') }}",
            containerSelector: '#permissions-container',
            paginationSelector: '#permissions-pagination',
            getAdditionalParams: () => ({
                search: document.getElementById('search-filter').value,
                status: document.getElementById('status-filter').value,
                start_date: document.getElementById('start-date-filter').value,
                end_date: document.getElementById('end-date-filter').value
            }),
            renderCallback: function(data) {
                const container = document.querySelector('#permissions-container');
                let html = '';
                if (data.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                        <i class="fa-solid fa-clock text-2xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-medium">No permissions found</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(perm => {
                    const statusColor = perm.status ? perm.status.status_color : '64748b';
                    const statusName = perm.status ? (perm.status.status_name || perm.status.permission_status_name) : 'Pending';
                    const submissionDate = perm.submission_date ? new Date(perm.submission_date).toLocaleDateString(undefined, {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    }) : '-';

                    html += `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="font-bold text-slate-500 text-xs">#${perm.permission_id}</td>
                            <td class="font-bold text-slate-700">
                                ${submissionDate}
                            </td>
                            <td>
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="px-2 py-1 bg-slate-100 rounded-lg">${perm.start_time}</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                    <span class="px-2 py-1 bg-slate-100 rounded-lg">${perm.end_time}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="font-bold text-indigo-600">${perm.total_hours}h</span>
                            </td>
                            <td class="max-w-xs">
                                <p class="text-sm text-slate-500 truncate" title="${perm.permission_remarks || ''}">
                                    ${perm.permission_remarks || '---'}
                                </p>
                            </td>
                            <td class="text-center">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-sm"
                                    style="background: #${statusColor};">
                                    ${statusName}
                                </span>
                                ${perm.is_exception ? '<span class="block mt-1 text-[9px] font-bold text-amber-600 uppercase tracking-tighter">Exception Granted</span>' : ''}
                            </td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Initialize pagination metrics on load
        @if($permissions->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $permissions->currentPage() }},
                last_page: {{ $permissions->lastPage() }},
                from: {{ $permissions->firstItem() }},
                to: {{ $permissions->lastItem() }},
                total: {{ $permissions->total() }}
            });
        @endif

        // Manual Filter Application
        function applyFilters() {
            window.ajaxPagination.loadPage(1);
        }

        // Reset All Filters (Performs full page reload to clear state)
        function resetFilters() {
            window.location.href = "{{ route('emp.permissions.index') }}";
        }

        function switchTab(tab) {
            const myRequestsSection = document.getElementById('my-requests-section');
            const approvalsSection = document.getElementById('approvals-section');
            const myRequestsTab = document.getElementById('tab-my-requests');
            const approvalsTab = document.getElementById('tab-approvals');

            if (tab === 'my-requests') {
                myRequestsSection.classList.remove('hidden');
                approvalsSection.classList.add('hidden');
                myRequestsTab.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
                myRequestsTab.classList.remove('text-slate-500');
                approvalsTab.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                approvalsTab.classList.add('text-slate-500');
            } else {
                myRequestsSection.classList.add('hidden');
                approvalsSection.classList.remove('hidden');
                approvalsTab.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
                approvalsTab.classList.remove('text-slate-500');
                myRequestsTab.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                myRequestsTab.classList.add('text-slate-500');
            }
        }

        function openRejectModal(id) {
            const form = document.getElementById('rejectForm');
            form.action = `{{ url('/emp/permissions') }}/${id}/reject`;
            openModal('rejectPermissionModal');
        }
    </script>
@endsection
