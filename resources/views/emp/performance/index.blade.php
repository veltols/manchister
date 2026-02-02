@extends('layouts.app')

@section('title', 'Performance Reviews')
@section('subtitle', 'Track your growth and professional milestones')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Summary Stats (Optional but nice) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="premium-card p-6 bg-gradient-to-br from-indigo-500 to-purple-600 text-white border-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-md">
                        <i class="fa-solid fa-star text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs font-bold uppercase tracking-widest">Total Reviews</p>
                        <h3 class="text-3xl font-display font-bold">{{ $reviews->total() }}</h3>
                    </div>
                </div>
            </div>
            <!-- Additional Stat Cards can go here -->
        </div>

        <!-- Performance List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">Date</th>
                            <th class="text-left font-bold text-slate-400">Objectives</th>
                            <th class="text-left font-bold text-slate-400">KPIs</th>
                            <th class="text-center font-bold text-slate-400">Assessed By</th>
                            <th class="text-center font-bold text-slate-400">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($reviews as $rev)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($rev->added_date)->format('d M Y') }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">Ref
                                            #{{ $rev->performance_id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-600 line-clamp-1 max-w-xs"
                                        title="{{ $rev->performance_object }}">
                                        {{ $rev->performance_object }}
                                    </p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        <p class="text-sm text-slate-600 line-clamp-1 max-w-xs"
                                            title="{{ $rev->performance_kpi }}">
                                            {{ $rev->performance_kpi }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            {{ substr($rev->marker->first_name ?? '?', 0, 1) }}
                                        </div>
                                        <span
                                            class="text-xs text-slate-600 font-medium">{{ $rev->marker->first_name ?? 'Manager' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button onclick="viewDetails({{ $rev->performance_id }})"
                                        class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-brand-dark hover:text-white transition-all shadow-sm mx-auto">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <i class="fa-solid fa-ranking-star text-5xl text-slate-100 mb-4"></i>
                                    <p class="text-slate-400 font-medium">No performance reviews available yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reviews->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Review Details Modal -->
    <div class="modal" id="reviewModal">
        <div class="modal-backdrop" onclick="closeModal('reviewModal')"></div>
        <div class="modal-content max-w-2xl p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Review Details</h2>
                    <p class="text-sm text-slate-500" id="modal-date"></p>
                </div>
                <button onclick="closeModal('reviewModal')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="premium-card p-5 bg-slate-50 border-0">
                        <label
                            class="block text-[10px] font-bold text-indigo-600 uppercase tracking-widest mb-2">Performance
                            Objectives</label>
                        <p class="text-sm text-slate-700 leading-relaxed" id="modal-objectives"></p>
                    </div>
                    <div class="premium-card p-5 bg-slate-50 border-0">
                        <label class="block text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-2">KPIs &
                            Achievements</label>
                        <p class="text-sm text-slate-700 leading-relaxed" id="modal-kpi"></p>
                    </div>
                </div>

                <div class="premium-card p-6 border-slate-100">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Manager's
                        Remarks</label>
                    <div class="p-4 bg-slate-50 rounded-xl italic text-slate-600 text-sm border-l-4 border-slate-300"
                        id="modal-remarks"></div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-2xl bg-indigo-50/50">
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Assessed By</p>
                        <p class="text-sm font-bold text-slate-800" id="modal-marker"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const reviews = @json($reviews->items());

        function viewDetails(id) {
            const rev = reviews.find(r => r.performance_id == id);
            if (rev) {
                document.getElementById('modal-date').innerText = `Ref: #${rev.performance_id} | Date: ${rev.added_date}`;
                document.getElementById('modal-objectives').innerText = rev.performance_object;
                document.getElementById('modal-kpi').innerText = rev.performance_kpi;
                document.getElementById('modal-remarks').innerText = rev.performance_remark || 'No specific remarks provided.';
                document.getElementById('modal-marker').innerText = rev.marker ? `${rev.marker.first_name} ${rev.marker.last_name}` : 'N/A';
                openModal('reviewModal');
            }
        }
    </script>
@endsection
