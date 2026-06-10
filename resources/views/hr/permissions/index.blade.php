@extends('layouts.app')

@section('title', 'Permissions')
@section('subtitle', 'Short leave requests')

@section('content')
    <div class="space-y-6">
        @include('hr.partials.requests_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Short Leave Permissions</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $permissions->total() }} total requests</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="exportCsv()"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export CSV</span>
                </button>
                <button onclick="openModal('permissionModal')"
                    class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>Request Permission</span>
                </button>
            </div>
        </div>
        <!-- Filters -->
        <div class="premium-card p-4 animate-fade-in-up">
            <div class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
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
                        <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="status-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s->permission_status_id }}">{{ $s->permission_status_name }}</option>
                            @endforeach
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
        <!-- Permissions Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th>Ref No</th> 
                            <th class="text-left">Employee</th>
                            <th class="text-left">Date</th>
                            <th class="text-left">Time</th>
                            <th class="text-center">Status</th>
                            <th class="text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="permissions-container">
                        @forelse($permissions as $p)
                            <tr>
                                <td>
                                    <span class="font-semibold text-slate-800">{{ $p->permission_id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                            {{ strtoupper(substr($p->employee->first_name ?? 'M', 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $p->employee->first_name ?? 'Unknown' }} {{ $p->employee->last_name ?? '' }}</span>
                                    </div>
                                </td>
                                <td><span class="text-sm text-slate-600">{{ $p->start_date }}</span></td>
                                <td>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium font-mono">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                        {{ $p->start_time }} - {{ $p->end_time }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-xs font-bold shadow-md">
                                        <i class="fa-solid fa-clock"></i>
                                        {{ $p->status->permission_status_name ?? 'Pending' }}
                                    </span>
                                    @if($p->is_exception)
                                        <span class="block mt-1 text-[9px] font-bold text-amber-600 uppercase tracking-tighter">Exception Granted</span>
                                    @endif
                                </td>
                                <td><span class="text-sm text-slate-600">{{ $p->permission_remarks }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-clock text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No permission requests found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <!-- AJAX Pagination -->
            <div id="permissions-pagination"></div>
        </div>

    </div>

    <!-- Permission Modal -->
    <div id="permissionModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('permissionModal')"></div>
        <div class="modal-content max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Request Short Leave</h2>
                    <p class="text-xs text-slate-500 mt-1">Daily Limit: 3 hrs (Mon-Thu) | 1 hr (Friday)</p>
                </div>
                <button onclick="closeModal('permissionModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('hr.permissions.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Select Employee
                        </label>
                        <select name="employee_id" class="premium-input w-full px-4 py-3 text-sm select2" required>
                            <option value="">Choose Employee...</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_no }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Date
                        </label>
                        <input type="date" name="start_date" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-clock text-indigo-600 mr-2"></i>From
                            </label>
                            <input type="time" name="start_time" class="premium-input w-full px-4 py-3 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-clock text-indigo-600 mr-2"></i>To
                            </label>
                            <input type="time" name="end_time" class="premium-input w-full px-4 py-3 text-sm" required>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-2">
                        <input type="checkbox" name="is_exception" id="hr_is_exception" value="1" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                        <label for="hr_is_exception" class="text-xs font-bold text-slate-700 uppercase tracking-widest">Mark as Exception (Skip 8-hour limit)</label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Reason
                        </label>
                        <textarea name="permission_remarks" class="premium-input w-full px-4 py-3 text-sm" rows="3"
                            placeholder="Explain your reason..." required></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('permissionModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('hr.permissions.data') }}",
            containerSelector: '#permissions-container',
            paginationSelector: '#permissions-pagination',
            getAdditionalParams: () => ({
                employee_id: document.getElementById('employee-filter').value,
                search: document.getElementById('search-filter').value,
                status_id: document.getElementById('status-filter').value,
                start_date: document.getElementById('start-date-filter').value,
                end_date: document.getElementById('end-date-filter').value
            }),
            renderCallback: function(permissions) {
                const container = document.querySelector('#permissions-container');
                if (permissions.length === 0) {
                    container.innerHTML = `
                         <tr>
                             <td colspan="5" class="text-center py-12">
                                 <div class="flex flex-col items-center gap-3">
                                     <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                         <i class="fa-solid fa-clock text-2xl text-slate-400"></i>
                                     </div>
                                     <p class="text-slate-500 font-medium">No permission requests found matching filters</p>
                                 </div>
                             </td>
                         </tr>
                    `;
                    return;
                }

                let html = '';
                permissions.forEach(p => {
                    const initials = p.employee ? p.employee.first_name.charAt(0).toUpperCase() : 'U';
                    const fullName = p.employee ? `${p.employee.first_name} ${p.employee.last_name || ''}` : 'Unknown';
                    
                    html += `
                        <tr>
                            <td>
                                <span class="font-semibold text-slate-800">${p.permission_id}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                        ${initials}
                                    </div>
                                    <span class="font-semibold text-slate-800">${fullName}</span>
                                </div>
                            </td>
                            <td><span class="text-sm text-slate-600">${p.start_date}</span></td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium font-mono">
                                    <i class="fa-solid fa-clock text-xs"></i>
                                    ${p.start_time} - ${p.end_time}
                                </span>
                            </td>
                            <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-xs font-bold shadow-md">
                                        <i class="fa-solid fa-clock"></i>
                                        ${p.status ? p.status.permission_status_name : 'Pending'}
                                    </span>
                                    ${p.is_exception ? '<span class="block mt-1 text-[9px] font-bold text-amber-600 uppercase tracking-tighter">Exception Granted</span>' : ''}
                            </td>
                            <td><span class="text-sm text-slate-600">${p.permission_remarks || ''}</span></td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Initial pagination setup
        @if($permissions->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $permissions->currentPage() }},
                last_page: {{ $permissions->lastPage() }},
                from: {{ $permissions->firstItem() }},
                to: {{ $permissions->lastItem() }},
                total: {{ $permissions->total() }}
            });
        @endif

        function applyFilters() {
            window.ajaxPagination.loadPage(1);
        }

        function exportCsv() {
            const params = new URLSearchParams({
                employee_id: document.getElementById('employee-filter').value,
                search:      document.getElementById('search-filter').value,
                status_id:   document.getElementById('status-filter').value,
                start_date:  document.getElementById('start-date-filter').value,
                end_date:    document.getElementById('end-date-filter').value,
            });
            for (const [key, value] of [...params.entries()]) {
                if (!value) params.delete(key);
            }
            window.location.href = '{{ route("hr.permissions.export") }}?' + params.toString();
        }

        function resetFilters() {
            window.location.href = "{{ route('hr.permissions.index') }}";
        }
    </script>
    @endpush

@endsection