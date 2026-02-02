@extends('layouts.app')

@section('title', 'Accredited Training Partners')
@section('subtitle', 'Manage and monitor training partners')

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        <!-- Filters & Search -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div
                class="flex bg-slate-100 p-1.5 rounded-2xl shadow-inner border border-slate-200/50 overflow-x-auto max-w-full scrollbar-hide">
                <a href="{{ route('emp.ext.atps.index') }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ !$statusId ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    All
                </a>
                @foreach($statuses as $status)
                    <a href="{{ route('emp.ext.atps.index', ['status_id' => $status->atp_status_id]) }}"
                        class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $statusId == $status->atp_status_id ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark whitespace-nowrap' }}">
                        {{ $status->atp_status_name }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('emp.ext.atps.index') }}" class="relative w-full md:w-80">
                @if($statusId)
                    <input type="hidden" name="status_id" value="{{ $statusId }}">
                @endif
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search }}"
                    class="premium-input pl-11 pr-4 py-2.5 w-full text-sm" placeholder="Search ATPs...">
            </form>

            <a href="{{ route('emp.ext.atps.create') }}" class="premium-button px-6 py-2.5 text-sm md:w-auto w-full justify-center">
                <i class="fa-solid fa-plus"></i>
                <span>Add New</span>
            </a>
        </div>

        <!-- ATPs List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">ATP Name</th>
                            <th class="text-left font-bold text-slate-400">Contact Info</th>
                            <th class="text-center font-bold text-slate-400">Status</th>
                            <th class="text-left font-bold text-slate-400">Added By</th>
                            <th class="text-center font-bold text-slate-400">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($atps as $atp)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold">
                                            {{ substr($atp->atp_name ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700">{{ $atp->atp_name }}</p>
                                            <p class="text-xs text-slate-400">Ref: {{ $atp->atp_ref }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-xs text-slate-500">
                                            <i class="fa-solid fa-user w-4"></i>
                                            <span>{{ $atp->contact_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-500">
                                            <i class="fa-solid fa-envelope w-4"></i>
                                            <span>{{ $atp->atp_email ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-500">
                                            <i class="fa-solid fa-phone w-4"></i>
                                            <span>{{ $atp->atp_phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                @if($atp->atp_status_id == 1) bg-yellow-100 text-yellow-600
                                                @elseif($atp->atp_status_id == 2) bg-blue-100 text-blue-600
                                                @elseif($atp->atp_status_id == 3) bg-emerald-100 text-emerald-600
                                                @else bg-slate-100 text-slate-500 @endif">
                                        {{ $atp->status->atp_status_name ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            {{ substr($atp->addedBy->first_name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-600">
                                                {{ $atp->addedBy->first_name ?? 'System' }}</p>
                                            <p class="text-[10px] text-slate-400">
                                                {{ \Carbon\Carbon::parse($atp->added_date)->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('emp.ext.atps.show', $atp->atp_id) }}" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-brand-dark hover:text-white transition-all shadow-sm" title="View Details">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        @if($atp->atp_status_id == 1)
                                            <button onclick="sendRegEmail({{ $atp->atp_id }})" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Send Registration Email">
                                                <i class="fa-solid fa-envelope text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div
                                        class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="fa-solid fa-folder-open text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-400">No ATPs found</h3>
                                    <p class="text-slate-300 text-sm mt-1">Try adjusting your search or filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($atps->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $atps->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function sendRegEmail(id) {
            if(confirm('Are you sure you want to send the registration email?')) {
                // Logic to send email would go here (AJAX request)
                alert('Email sending logic would be triggered for ID: ' + id);
            }
        }
    </script>
@endsection
