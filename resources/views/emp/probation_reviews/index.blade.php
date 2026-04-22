@extends('layouts.app')
@section('title', 'Probation Reviews — My Queue')
@section('content')
<div class="space-y-6">
    <div><h2 class="text-2xl font-display font-bold text-premium">Probation Reviews</h2><p class="text-sm text-slate-500 mt-1">Reviews assigned to you for assessment</p></div>

    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead><tr>
                    <th>Ref</th><th>Employee</th><th>Probation Type</th><th>End Date</th><th class="text-center">Status</th><th>Created</th><th class="text-center">Action</th>
                </tr></thead>
                <tbody>
                @forelse($reviews as $review)
                @php
                    $colors=['pending_manager'=>'from-amber-400 to-amber-600','reviewed'=>'from-blue-500 to-indigo-600','approved'=>'from-emerald-500 to-green-600','rejected'=>'from-rose-500 to-red-600'];
                    $color=$colors[$review->status]??'from-slate-400 to-slate-600';
                @endphp
                <tr>
                    <td><span class="font-mono text-xs text-slate-500">#{{ $review->review_id }}</span></td>
                    <td><span class="font-semibold text-slate-700">{{ $review->employee->first_name ?? 'N/A' }} {{ $review->employee->last_name ?? '' }}</span></td>
                    <td>@if($review->probation_type)<span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-bold uppercase">{{ str_replace('_',' ',$review->probation_type) }}</span>@else<span class="text-slate-400 text-xs">—</span>@endif</td>
                    <td class="text-sm text-slate-600">{{ $review->probation_end_date ? \Carbon\Carbon::parse($review->probation_end_date)->format('d M Y') : '—' }}</td>
                    <td class="text-center"><span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $color }} text-white text-[10px] font-bold shadow-sm whitespace-nowrap">{{ $review->status_label }}</span></td>
                    <td class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</td>
                    <td class="text-center">
                        <a href="{{ route('emp.probation-reviews.show', $review->review_id) }}"
                           class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center transition-colors mx-auto">
                            <i class="fa-solid fa-{{ $review->status === 'pending_manager' ? 'pen' : 'eye' }} text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-16">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center"><i class="fa-solid fa-clipboard-check text-2xl text-slate-300"></i></div>
                        <p class="text-slate-500">No probation reviews assigned to you</p>
                    </div>
                </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())<div class="px-6 py-4 border-t border-slate-100">{{ $reviews->links('pagination::bootstrap-5') }}</div>@endif
    </div>
</div>
@endsection
