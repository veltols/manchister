@extends('layouts.app')

@section('title', 'System Activity Logs')
@section('subtitle', 'Monitor all administrative and user actions')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">System Activity Logs</h2>
                <p class="text-sm text-slate-500 mt-1">Audit trail of all system changes</p>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="premium-card p-6 border border-slate-100 shadow-sm">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[300px]">
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="text" id="logSearch" placeholder="Search by action, remark or table name..."
                            class="premium-input pl-11 pr-4 py-2.5 w-full text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                            value="{{ request('search') }}">
                    </div>
                </div>
                <select id="filterType" class="premium-input px-4 py-2.5 text-sm min-w-[150px] focus:ring-4 focus:ring-indigo-100">
                    <option value="">All Types</option>
                    <option value="admin" {{ request('type') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="hr" {{ request('type') == 'hr' ? 'selected' : '' }}>HR</option>
                    <option value="emp" {{ request('type') == 'emp' ? 'selected' : '' }}>Employee</option>
                </select>
            </div>
        </div>

        <!-- Logs List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left w-16">ID</th>
                            <th class="text-left">Date & Time</th>
                            <th class="text-left">Action</th>
                            <th class="text-left">Logger</th>
                            <th class="text-left">Module / Ref</th>
                            <th class="text-left">Details</th>
                        </tr>
                    </thead>
                    <tbody id="logs-container">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <span class="font-mono text-xs font-semibold text-slate-400">#{{ $log->log_id }}</span>
                                </td>
                                <td class="whitespace-nowrap">
                                    <p class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y') }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($log->log_date)->format('h:i A') }}</p>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $log->log_action }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-[10px] font-bold border border-slate-200">
                                            {{ substr($log->logger->first_name ?? 'S', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700 leading-none">{{ $log->logger ? $log->logger->first_name . ' ' . $log->logger->last_name : 'System' }}</p>
                                            <p class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $log->logger_type }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ str_replace('_', ' ', $log->related_table) }}</span>
                                        <span class="text-xs font-mono text-indigo-600">ID: {{ $log->related_id }}</span>
                                    </div>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-xs text-slate-600 line-clamp-2" title="{{ $log->log_remark }}">
                                        {{ $log->log_remark }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20 text-slate-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-clipboard-list text-4xl opacity-20"></i>
                                        <p class="font-medium">No activity logs found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- AJAX Pagination Container -->
            <div id="logs-pagination" class="p-6 border-t border-slate-50"></div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/ajax-pagination.js') }}"></script>
        <script>
            // Initialize AJAX Pagination
            window.ajaxPagination = new AjaxPagination({
                endpoint: "{{ route('admin.system_logs.data') }}",
                containerSelector: '#logs-container',
                paginationSelector: '#logs-pagination',
                perPage: 30,
                getAdditionalParams: function() {
                    return {
                        search: document.getElementById('logSearch').value,
                        type: document.getElementById('filterType').value
                    };
                },
                renderCallback: function(logs) {
                    const container = document.querySelector('#logs-container');
                    
                    if (logs.length === 0) {
                        container.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-20 text-slate-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-clipboard-list text-4xl opacity-20"></i>
                                        <p class="font-medium">No activity logs found</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        return;
                    }
                    
                    let html = '';
                    logs.forEach(log => {
                        const date = new Date(log.log_date);
                        const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                        const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        const loggerName = log.logger ? `${log.logger.first_name} ${log.logger.last_name}` : 'System';
                        const loggerInitial = loggerName.charAt(0);
                        const relatedTable = (log.related_table || '').replace(/_/g, ' ');

                        html += `
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <span class="font-mono text-xs font-semibold text-slate-400">#${log.log_id}</span>
                                </td>
                                <td class="whitespace-nowrap">
                                    <p class="text-sm font-semibold text-slate-700">${formattedDate}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">${formattedTime}</p>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        ${log.log_action}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-[10px] font-bold border border-slate-200">
                                            ${loggerInitial}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700 leading-none">${loggerName}</p>
                                            <p class="text-[10px] text-slate-400 uppercase tracking-tighter">${log.logger_type || ''}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">${relatedTable}</span>
                                        <span class="text-xs font-mono text-indigo-600">ID: ${log.related_id}</span>
                                    </div>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-xs text-slate-600 line-clamp-2" title="${log.log_remark}">
                                        ${log.log_remark || ''}
                                    </p>
                                </td>
                            </tr>
                        `;
                    });
                    
                    container.innerHTML = html;
                }
            });

            // Initial pagination
            @if($logs->hasPages())
                window.ajaxPagination.renderPagination({
                    current_page: {{ $logs->currentPage() }},
                    last_page: {{ $logs->lastPage() }},
                    from: {{ $logs->firstItem() ?? 0 }},
                    to: {{ $logs->lastItem() ?? 0 }},
                    total: {{ $logs->total() }}
                });
            @endif

            // Filter listeners
            let searchTimeout;
            document.getElementById('logSearch').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => window.ajaxPagination.loadPage(1), 500);
            });

            document.getElementById('filterType').addEventListener('change', () => window.ajaxPagination.loadPage(1));
        </script>
    @endpush
@endsection
