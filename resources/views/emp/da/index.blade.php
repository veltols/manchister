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
                                        {{ $ac->status->status_name ?? 'Active' }}
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
            renderCallback: function(data) {
                let html = '';
                data.forEach(ac => {
                    const statusColor = ac.status ? ac.status.status_color : '64748b';
                    const statusName = ac.status ? ac.status.status_name : 'Active';
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
                                <p class="text-sm text-slate-500 truncate" title="${ac.da_remark}">
                                    ${ac.da_remark}
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
                return html;
            }
        });
    </script>
@endsection
