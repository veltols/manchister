@extends('layouts.app')

@section('title', 'Service Requests')
@section('subtitle', 'Manage incoming admin service requests')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Service Requests</h2>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $isAdmin ? 'All service requests across the system' : 'Requests assigned to you' }}
                </p>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($statuses as $st)
                <div class="premium-card p-5 flex items-center gap-4 cursor-pointer hover:shadow-md transition-all group"
                     onclick="filterByStatus('{{ $st->status_id }}')">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0"
                         style="background: #{{ $st->status_color }};">
                        <i class="fa-solid fa-circle-dot"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $st->status_name }}</p>
                        <p class="text-xl font-black text-slate-800 leading-none mt-0.5 status-count-{{ $st->status_id }}">—</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filters -->
        <div class="premium-card p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                    <input type="text" id="ss-search" placeholder="Search by REF or description…"
                           class="premium-input w-full pl-9 py-2 text-sm" oninput="triggerFilter()">
                </div>
                <select id="ss-status-filter" class="premium-input py-2 text-sm" onchange="triggerFilter()">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->status_id }}">{{ $st->status_name }}</option>
                    @endforeach
                </select>
                <button onclick="clearFilters()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-500 text-sm font-semibold hover:bg-slate-50 transition-all">
                    <i class="fa-solid fa-times mr-1"></i> Clear
                </button>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">REF</th>
                            <th class="text-left font-bold text-slate-400">Type</th>
                            <th class="text-left font-bold text-slate-400">Description</th>
                            <th class="text-left font-bold text-slate-400">Requested By</th>
                            @if($isAdmin)
                                <th class="text-left font-bold text-slate-400">Assigned To</th>
                            @endif
                            <th class="text-left font-bold text-slate-400">Date</th>
                            <th class="text-center font-bold text-slate-400">Status</th>
                            <th class="text-center font-bold text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50" id="ss-container">
                        @forelse($services as $sv)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $sv->ss_ref }}</span>
                                </td>
                                <td>
                                    <span class="font-bold text-slate-700 text-sm">{{ $sv->category->category_name ?? '-' }}</span>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-500 truncate" title="{{ $sv->ss_description }}">
                                        {{ $sv->ss_description }}
                                    </p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">
                                            {{ substr($sv->sender->first_name ?? '?', 0, 1) }}{{ substr($sv->sender->last_name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-sm text-slate-600 font-medium">
                                            {{ $sv->sender->first_name ?? 'Unknown' }} {{ $sv->sender->last_name ?? '' }}
                                        </span>
                                    </div>
                                </td>
                                @if($isAdmin)
                                    <td>
                                        <span class="text-sm text-slate-500">{{ $sv->receiver->first_name ?? '-' }} {{ $sv->receiver->last_name ?? '' }}</span>
                                    </td>
                                @endif
                                <td>
                                    <span class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($sv->ss_added_date)->format('M d, Y') }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                          style="background: #{{ $sv->status->status_color ?? '64748b' }};">
                                        {{ $sv->status->status_name ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('hr.ss.show', $sv->ss_id) }}"
                                       class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto"
                                       title="View Details">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center py-20">
                                    <i class="fa-solid fa-handshake-angle text-5xl text-slate-100 mb-4 block"></i>
                                    <p class="text-slate-400 font-medium">No service requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="ss-pagination" class="px-6 py-4 border-t border-slate-50"></div>
        </div>

    </div>

    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        const IS_ADMIN = {{ $isAdmin ? 'true' : 'false' }};

        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('hr.ss.data') }}",
            containerSelector: '#ss-container',
            paginationSelector: '#ss-pagination',
            getAdditionalParams: () => ({
                search: document.getElementById('ss-search').value,
                status_id: document.getElementById('ss-status-filter').value,
            }),
            renderCallback: function(data) {
                const container = document.getElementById('ss-container');
                if (!data || data.length === 0) {
                    container.innerHTML = `<tr><td colspan="${IS_ADMIN ? 8 : 7}" class="text-center py-20">
                        <i class="fa-solid fa-handshake-angle text-5xl text-slate-100 mb-4 block"></i>
                        <p class="text-slate-400 font-medium">No service requests found</p>
                    </td></tr>`;
                    return;
                }

                container.innerHTML = data.map(sv => {
                    const statusColor = sv.status ? sv.status.status_color : '64748b';
                    const statusName  = sv.status ? sv.status.status_name : 'Pending';
                    const senderInit  = ((sv.sender?.first_name || '?')[0] + (sv.sender?.last_name || '?')[0]).toUpperCase();
                    const senderName  = `${sv.sender?.first_name || 'Unknown'} ${sv.sender?.last_name || ''}`;
                    const date        = sv.ss_added_date ? new Date(sv.ss_added_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—';
                    const receiverCell = IS_ADMIN ? `<td><span class="text-sm text-slate-500">${sv.receiver?.first_name || '-'} ${sv.receiver?.last_name || ''}</span></td>` : '';

                    return `<tr class="hover:bg-slate-50/50 transition-colors">
                        <td><span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">${sv.ss_ref}</span></td>
                        <td><span class="font-bold text-slate-700 text-sm">${sv.category ? sv.category.category_name : '-'}</span></td>
                        <td class="max-w-xs"><p class="text-sm text-slate-500 truncate" title="${sv.ss_description}">${sv.ss_description}</p></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">${senderInit}</div>
                                <span class="text-sm text-slate-600 font-medium">${senderName}</span>
                            </div>
                        </td>
                        ${receiverCell}
                        <td><span class="text-xs text-slate-400 font-mono">${date}</span></td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm" style="background: #${statusColor};">${statusName}</span>
                        </td>
                        <td class="text-center">
                            <a href="/hr/ss/${sv.ss_id}" class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto" title="View Details">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                        </td>
                    </tr>`;
                }).join('');
            }
        });

        let filterTimer;
        function triggerFilter() {
            clearTimeout(filterTimer);
            filterTimer = setTimeout(() => window.ajaxPagination.loadPage(1), 350);
        }

        function filterByStatus(statusId) {
            document.getElementById('ss-status-filter').value = statusId;
            window.ajaxPagination.loadPage(1);
        }

        function clearFilters() {
            document.getElementById('ss-search').value = '';
            document.getElementById('ss-status-filter').value = '';
            window.ajaxPagination.loadPage(1);
        }

        // Load status counts
        fetch("{{ route('hr.ss.data') }}?per_page=1000")
            .then(r => r.json())
            .then(res => {
                if (!res.success) return;
                const counts = {};
                res.data.forEach(sv => {
                    const sid = sv.status_id;
                    counts[sid] = (counts[sid] || 0) + 1;
                });
                Object.entries(counts).forEach(([sid, count]) => {
                    const el = document.querySelector(`.status-count-${sid}`);
                    if (el) el.innerText = count;
                });
            });
    </script>
@endsection
