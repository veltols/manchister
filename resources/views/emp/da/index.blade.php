@extends('layouts.app')

@section('title', 'Disciplinary Actions')
@section('subtitle', 'History of recorded actions and warnings')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">My Disciplinary Records</h2>
                <p class="text-sm text-slate-500 mt-1">Review any formal actions or warnings issued regarding your conduct
                </p>
            </div>
        </div>
        <!-- Filters -->
        <div class="premium-card p-4 animate-fade-in-up delay-100">
            <div class="flex flex-col md:flex-row items-end gap-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 flex-1">
                    <div class="relative">
                        <i class="fa-solid fa-triangle-exclamation absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="warning-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Warning Levels</option>
                            @foreach($warnings as $warning)
                                <option value="{{ $warning->da_warning_id }}">{{ $warning->da_warning_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="status-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->da_status_id }}">{{ $status->da_status_name }}</option>
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
        <!-- Actions List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">Date</th>
                            <th class="text-left font-bold text-slate-400">Warning Level</th>
                            <th class="text-left font-bold text-slate-400">Action Type</th>
                            <th class="text-left font-bold text-slate-400">Remarks</th>
                            <th class="text-center font-bold text-slate-400">Status</th>
                            <th class="text-center font-bold text-slate-400">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50" id="da-container">
                        @forelse($actions as $ac)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <span class="font-bold text-slate-700 text-sm">{{ $ac->added_date }}</span>
                                </td>
                                <td>
                                    <span
                                        class="px-3 py-1 bg-red-50 text-red-600 rounded-lg font-bold text-xs uppercase tracking-tight">
                                        {{ $ac->warning->da_warning_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-700">{{ $ac->type->da_type_code ?? 'N/A' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $ac->type->da_type_text ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-500 truncate" title="{{ $ac->da_remark }}">
                                        {{ $ac->da_remark }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                        style="background: #{{ $ac->status->status_color ?? '64748b' }};">
                                         {{ $ac->status->da_status_name ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('emp.da.show', $ac->da_id) }}"
                                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20">
                                    <i class="fa-solid fa-file-shield text-5xl text-slate-100 mb-4"></i>
                                    <p class="text-slate-400 font-medium">Keep up the good work! No disciplinary actions found.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                    <!-- AJAX Pagination -->
                    <div id="da-pagination"></div>

                    @if (false && $actions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $actions->links() }}
                </div>
            @endif
        </div>

    </div>
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('emp.da.data') }}",
            containerSelector: '#da-container',
            paginationSelector: '#da-pagination',
            getAdditionalParams: () => ({
                warning_id: document.getElementById('warning-filter').value,
                status_id: document.getElementById('status-filter').value,
                start_date: document.getElementById('start-date-filter').value,
                end_date: document.getElementById('end-date-filter').value
            }),
            renderCallback: function(data) {
                const container = document.querySelector('#da-container');
                let html = '';
                if (data.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-20">
                                <i class="fa-solid fa-file-shield text-5xl text-slate-100 mb-4"></i>
                                <p class="text-slate-400 font-medium">No disciplinary actions found matching your filters.</p>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(ac => {
                    const statusColor = ac.status ? ac.status.status_color : '64748b';
                    const statusName = ac.status ? ac.status.da_status_name : 'Unknown';
                    const warningName = ac.warning ? ac.warning.da_warning_name : 'N/A';
                    const typeCode = ac.type ? ac.type.da_type_code : 'N/A';
                    const typeText = ac.type ? ac.type.da_type_text : '';

                    html += `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td>
                                <span class="font-bold text-slate-700 text-sm">${ac.added_date}</span>
                            </td>
                            <td>
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg font-bold text-xs uppercase tracking-tight">
                                    ${warningName}
                                </span>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">${typeCode}</span>
                                    <span class="text-[10px] text-slate-400">${typeText}</span>
                                </div>
                            </td>
                            <td class="max-w-xs">
                                <p class="text-sm text-slate-500 truncate" title="${ac.da_remark || ''}">
                                    ${ac.da_remark || '---'}
                                </p>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                    style="background: #${statusColor};">
                                    ${statusName}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="/emp/da/${ac.da_id}"
                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Initialize pagination metrics on load
        @if($actions->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $actions->currentPage() }},
                last_page: {{ $actions->lastPage() }},
                from: {{ $actions->firstItem() }},
                to: {{ $actions->lastItem() }},
                total: {{ $actions->total() }}
            });
        @endif

        function applyFilters() {
            window.ajaxPagination.loadPage(1);
        }

        function resetFilters() {
            window.location.href = "{{ route('emp.da.index') }}";
        }
    </script>
@endsection
