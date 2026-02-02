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
                    <tbody class="divide-y divide-slate-50">
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
                                        class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-brand-dark hover:text-white transition-all shadow-sm mx-auto">
                                        <i class="fa-solid fa-eye text-xs"></i>
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
            @if($actions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $actions->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
