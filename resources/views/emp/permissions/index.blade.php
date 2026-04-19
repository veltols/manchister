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
            <button onclick="openModal('requestPermissionModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>New Request</span>
            </button>
        </div>

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
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-sm"
                                        style="background: #{{ $perm->status->status_color ?? '64748b' }};">
                                        {{ $perm->status->status_name ?? 'Pending' }}
                                    </span>
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

    </div>

    <!-- Request Permission Modal -->
    <div class="modal" id="requestPermissionModal">
        <div class="modal-backdrop" onclick="closeModal('requestPermissionModal')"></div>
        <div class="modal-content max-w-lg p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-display font-bold text-premium">New Permission Request</h2>
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
    </script>
@endsection
