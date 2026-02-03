@extends('layouts.app')

@section('title', 'Training Providers')
@section('subtitle', 'Manage Educational Institutions')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative group">
                <form action="{{ route('emp.atps.index') }}" method="GET">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search Institutions..." 
                           class="premium-input w-64 md:w-80 pl-11 pr-4 py-3 text-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand transition-colors"></i>
                </form>
            </div>
            
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="h-11 px-4 rounded-xl border border-slate-200 bg-white text-slate-600 hover:border-brand/30 hover:bg-slate-50 transition-all flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>
                <div x-show="open" @click.away="open = false" 
                     class="absolute top-12 left-0 w-64 p-4 bg-white rounded-2xl shadow-xl border border-slate-100 z-50">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Filter by Status</h4>
                    <div class="space-y-2">
                        <a href="{{ route('emp.atps.index') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-slate-50 {{ !request('status') ? 'text-brand font-bold bg-brand/5' : 'text-slate-600' }}">All Statuses</a>
                        @foreach($statuses as $status)
                            <a href="{{ route('emp.atps.index', ['status' => $status->atp_status_id]) }}" 
                               class="block px-3 py-2 rounded-lg text-sm hover:bg-slate-50 {{ request('status') == $status->atp_status_id ? 'text-brand font-bold bg-brand/5' : 'text-slate-600' }}">
                                {{ $status->atp_status_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('emp.atps.create') }}" class="premium-button flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Add New Provider
        </a>
    </div>

    <!-- Status Navigation Tabs -->
    <div class="flex items-center gap-2 p-1 bg-white/50 backdrop-blur-sm rounded-2xl border border-slate-100 w-fit">
        <a href="{{ route('emp.atps.index', ['stt' => '00']) }}" 
           class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 
           {{ request('stt') == '00' || !request('stt') ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white' }}">
            All
        </a>
        <a href="{{ route('emp.atps.index', ['stt' => '1']) }}" 
           class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 
           {{ request('stt') == '1' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white' }}">
            Pending
        </a>
        <a href="{{ route('emp.atps.index', ['stt' => '3']) }}" 
           class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 
           {{ request('stt') == '3' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white' }}">
            Accredited
        </a>
        <a href="{{ route('emp.atps.index', ['stt' => '4']) }}" 
           class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 
           {{ request('stt') == '4' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white' }}">
            Renewal
        </a>
        <a href="{{ route('emp.atps.index', ['stt' => '5']) }}" 
           class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 
           {{ request('stt') == '5' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white' }}">
            Expired
        </a>
    </div>

    <!-- ATP Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($atps as $atp)
            <div class="premium-card group hover:scale-[1.02] transition-all duration-300">
                <div class="p-6 space-y-6">
                    <!-- Top Section -->
                    <div class="flex items-start justify-between">
                        <div class="w-14 h-14 rounded-2xl bg-brand/5 flex items-center justify-center text-brand border border-brand/10 group-hover:bg-brand group-hover:text-white transition-colors">
                            <i class="fa-solid fa-building-columns text-2xl"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $atp->atp_status_id == 1 ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                            {{ $atp->status->atp_status_name ?? 'Unknown' }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="space-y-1">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">{{ $atp->atp_ref }}</div>
                        <h3 class="font-bold text-premium truncate group-hover:text-brand transition-colors" title="{{ $atp->atp_name }}">
                            {{ $atp->atp_name }}
                        </h3>
                        <p class="text-xs text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-user text-[10px]"></i>
                            {{ $atp->contact_name }}
                        </p>
                    </div>

                    <!-- Stats Row -->
                    <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-50">
                        <div class="space-y-1">
                            <div class="text-[10px] font-medium text-slate-400 tracking-wider">Type</div>
                            <div class="text-xs font-bold text-slate-700 truncate">{{ $atp->type->atp_type_name ?? 'N/A' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-[10px] font-medium text-slate-400 tracking-wider">Category</div>
                            <div class="text-xs font-bold text-slate-700 truncate">{{ $atp->category->atp_category_name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center gap-1">
                            @if($atp->atp_status_id == 1)
                                <form action="{{ route('emp.atps.send-email', $atp->atp_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all shadow-sm" title="Send Registration Email">
                                        <i class="fa-solid fa-envelope text-[10px]"></i>
                                    </button>
                                </form>
                            @endif
                            @if($atp->atp_status_id != 4)
                                <form action="{{ route('emp.atps.accredit', $atp->atp_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Accredit Provider">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <a href="{{ route('emp.atps.show', $atp->atp_id) }}" 
                           class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-brand hover:text-white transition-all shadow-sm">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-[32px] border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fa-solid fa-building-circle-exclamation text-3xl text-slate-200"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-400">No Providers Found</h3>
                <p class="text-sm text-slate-400 mt-2">Try adjusting your search or filters to find what you're looking for.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="premium-card p-4">
        {{ $atps->links() }}
    </div>
</div>
@endsection
