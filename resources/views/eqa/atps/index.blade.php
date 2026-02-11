@extends('layouts.app')

@section('title', 'Training Providers')
@section('subtitle', 'Manage External Training Providers')

@section('content')
    <div class="space-y-6">

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Training Providers</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $atps->total() }} total providers</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('eqa.atps.create') }}"
                    class="ml-2 inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>New Provider</span>
                </a>
            </div>
        </div>

        <!-- Filter Tabs (Legacy Parity) -->
        <div class="premium-card p-2">
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                @php $stt = request('stt', '00'); @endphp
                <a href="{{ route('eqa.atps.index', ['stt' => '00']) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap {{ $stt == '00' ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    All
                </a>
                <a href="{{ route('eqa.atps.index', ['stt' => 1]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap {{ $stt == 1 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Pending Email
                </a>
                <a href="{{ route('eqa.atps.index', ['stt' => 2]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap {{ $stt == 2 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Pending
                </a>
                <a href="{{ route('eqa.atps.index', ['stt' => 3]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap {{ $stt == 3 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Accredited
                </a>
                <a href="{{ route('eqa.atps.index', ['stt' => 4]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap {{ $stt == 4 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Renewal
                </a>
                <a href="{{ route('eqa.atps.index', ['stt' => 5]) }}"
                    class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap {{ $stt == 5 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Expired
                </a>
            </div>
        </div>

        <!-- ATPs Table Area -->
        <div class="space-y-4">
            <div class="overflow-x-auto px-1 pb-4">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">REF</th>
                            <th class="text-left">Provider Name</th>
                            <th class="text-left">Contact Person</th>
                            <th class="text-left">Communication</th>
                            <th class="text-left">Added Info</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="atps-container">
                        @forelse($atps as $atp)
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">{{ $atp->atp_ref }}</span>
                                </td>
                                <td class="max-w-[200px]">
                                    <span class="font-semibold text-slate-800 block truncate" title="{{ $atp->atp_name }}">{{ $atp->atp_name }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $atp->emirate->city_name ?? '-' }} Emirates</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-600 font-medium">{{ $atp->contact_name }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm text-slate-600">{{ $atp->atp_email }}</span>
                                        <span class="text-xs text-slate-400 font-mono">{{ $atp->atp_phone }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-slate-500">
                                            <i class="fa-regular fa-calendar-plus mr-1"></i>
                                            {{ $atp->added_date ? \Carbon\Carbon::parse($atp->added_date)->format('d-m-Y') : '-' }}
                                        </span>
                                        <span class="text-xs text-slate-400 mt-0.5">
                                            <i class="fa-regular fa-user mr-1"></i>
                                            {{ $atp->adder ? $atp->adder->first_name . ' ' . $atp->adder->last_name : 'System' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusName = $atp->status->atp_status_name ?? 'New';
                                        $statusColor = 'bg-slate-500'; // Default
                                        if($atp->atp_status_id == 1) $statusColor = 'bg-amber-500';
                                        if($atp->atp_status_id == 2) $statusColor = 'bg-indigo-500';
                                        if($atp->atp_status_id == 3) $statusColor = 'bg-emerald-500';
                                        if($atp->atp_status_id == 4) $statusColor = 'bg-blue-500';
                                        if($atp->atp_status_id == 5) $statusColor = 'bg-rose-500';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-black uppercase tracking-widest shadow-md {{ $statusColor }}">
                                        {{ $statusName }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('eqa.atps.show', $atp->atp_id) }}"
                                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                            title="View Details">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                        @if($atp->atp_status_id == 1)
                                            <form action="{{ route('eqa.atps.send_email', $atp->atp_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Send registration email to this provider?')"
                                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                                    title="Send Registration Email">
                                                    <i class="fa-solid fa-envelope text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-building text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No training providers found</p>
                                        <a href="{{ route('eqa.atps.create') }}"
                                            class="text-brand-dark hover:text-brand-light font-bold text-sm">
                                            Add your first provider
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- AJAX Pagination Container -->
            <div id="atps-pagination"></div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        // Initialize AJAX Pagination
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('eqa.atps.data', ['stt' => $stt]) }}",
            containerSelector: '#atps-container',
            paginationSelector: '#atps-pagination',
            perPage: 20,
            renderCallback: function(atps) {
                const container = document.querySelector('#atps-container');
                
                if (atps.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-building text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No training providers found</p>
                                    <a href="{{ route('eqa.atps.create') }}"
                                        class="text-brand-dark hover:text-brand-light font-bold text-sm">
                                        Add your first provider
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                let html = '';
                atps.forEach(atp => {
                    const showUrl = `{{ route('eqa.atps.show', ':id') }}`.replace(':id', atp.atp_id);
                    const emailUrl = `{{ route('eqa.atps.send_email', ':id') }}`.replace(':id', atp.atp_id);
                    const csrf = '{{ csrf_field() }}';
                    
                    let statusColor = 'bg-slate-500';
                    if(atp.atp_status_id == 1) statusColor = 'bg-amber-500';
                    else if(atp.atp_status_id == 2) statusColor = 'bg-indigo-500';
                    else if(atp.atp_status_id == 3) statusColor = 'bg-emerald-500';
                    else if(atp.atp_status_id == 4) statusColor = 'bg-blue-500';
                    else if(atp.atp_status_id == 5) statusColor = 'bg-rose-500';

                    const statusName = atp.status ? atp.status.atp_status_name : 'New';
                    const adderName = atp.adder ? atp.adder.first_name + ' ' + atp.adder.last_name : 'System';
                    const addedDate = atp.added_date ? new Date(atp.added_date).toLocaleDateString('en-GB') : '-';

                    html += `
                        <tr>
                            <td>
                                <span class="font-mono text-sm font-semibold text-slate-600">${atp.atp_ref}</span>
                            </td>
                            <td class="max-w-[200px]">
                                <span class="font-semibold text-slate-800 block truncate" title="${atp.atp_name}">${atp.atp_name}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">${atp.emirate ? atp.emirate.city_name : '-'} Emirates</span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600 font-medium">${atp.contact_name}</span>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-sm text-slate-600">${atp.atp_email}</span>
                                    <span class="text-xs text-slate-400 font-mono">${atp.atp_phone}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-xs text-slate-500">
                                        <i class="fa-regular fa-calendar-plus mr-1"></i>
                                        ${addedDate}
                                    </span>
                                    <span class="text-xs text-slate-400 mt-0.5">
                                        <i class="fa-regular fa-user mr-1"></i>
                                        ${adderName}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-black uppercase tracking-widest shadow-md ${statusColor}">
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
                                    ${atp.atp_status_id == 1 ? `
                                        <form action="${emailUrl}" method="POST" class="inline">
                                            ${csrf}
                                            <button type="submit" onclick="return confirm('Send registration email to this provider?')"
                                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                                title="Send Registration Email">
                                                <i class="fa-solid fa-envelope text-sm"></i>
                                            </button>
                                        </form>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                container.innerHTML = html;
            }
        });

        // Use server-side rendered data for initial page load
        @if($atps->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $atps->currentPage() }},
                last_page: {{ $atps->lastPage() }},
                from: {{ $atps->firstItem() ?? 0 }},
                to: {{ $atps->lastItem() ?? 0 }},
                total: {{ $atps->total() }}
            });
        @endif
    </script>
    @endpush
@endsection

