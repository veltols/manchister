@extends('layouts.app')

@section('title', 'My Dashboard')
@section('subtitle', 'Welcome back!')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Header -->
    <div class="premium-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-slate-800">Welcome, {{ Auth::user()->user_email }}</h2>
                <p class="text-slate-500 mt-1">{{ now()->format('l, jS F Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="?mode=today" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $mode == 'today' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    Today
                </a>
                <a href="?mode=this_week" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $mode == 'this_week' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    This Week
                </a>
                <a href="?mode=this_month" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $mode == 'this_month' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    This Month
                </a>
            </div>
        </div>
    </div>

    @if($announcements->count() > 0)
    <!-- Announcements Carousel -->
    <div class="premium-card p-6 relative">
        <h3 class="text-lg font-display font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bullhorn text-indigo-600"></i>
            Latest Announcements
        </h3>
        @foreach($announcements as $index => $ann)
            <div class="announcement-slide {{ $index === 0 ? '' : 'hidden' }}" id="ann-{{ $index }}">
                <h4 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">{{ $ann->document_title }}</h4>
                <p class="text-xs text-slate-400 mb-3">{{ $ann->added_date }}</p>
                <p class="text-slate-600">{{ $ann->document_description }}</p>
            </div>
        @endforeach
        
        @if($announcements->count() > 1)
            <button onclick="prevAnn()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-lg hover:bg-slate-50 flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button onclick="nextAnn()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-lg hover:bg-slate-50 flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        @endif
    </div>
    @endif

    <!-- My Tickets Stats -->
    <div>
        <h3 class="text-xl font-display font-bold text-slate-800 mb-4">My Tickets</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('emp.tickets.index', ['stt' => 0]) }}" class="stat-card group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Tickets</p>
                        <h3 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent count" data-target="{{ $ticketStats->total }}">0</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file text-white text-xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('emp.tickets.index', ['stt' => 1]) }}" class="stat-card group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Unassigned</p>
                        <h3 class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent count" data-target="{{ $ticketStats->unassigned }}">0</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-folder text-white text-xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('emp.tickets.index', ['stt' => 2]) }}" class="stat-card group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">In Progress</p>
                        <h3 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent count" data-target="{{ $ticketStats->progress }}">0</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-list text-white text-xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('emp.tickets.index', ['stt' => 3]) }}" class="stat-card group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Resolved</p>
                        <h3 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent count" data-target="{{ $ticketStats->resolved }}">0</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-check text-white text-xl"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- My Assets -->
    <div class="premium-card overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-display font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-laptop text-indigo-600"></i>
                My Assets
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">REF</th>
                        <th class="text-left">Name</th>
                        <th class="text-left">Assigned By</th>
                        <th class="text-left">Assigned Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td><span class="font-mono text-sm font-semibold text-slate-600">{{ $asset->asset_ref }}</span></td>
                        <td><span class="font-semibold text-slate-800">{{ $asset->asset_name }}</span></td>
                        <td><span class="text-sm text-slate-600">{{ $asset->assignedBy ? $asset->assignedBy->first_name . ' ' . $asset->assignedBy->last_name : '-' }}</span></td>
                        <td><span class="text-sm text-slate-600">{{ $asset->assigned_date }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8">
                            <p class="text-slate-500">No assets assigned</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- HR Dashboard -->
    <div>
        <h3 class="text-xl font-display font-bold text-slate-800 mb-4">Leave Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Leave Balance</p>
                        <p class="text-2xl font-bold text-slate-800 count" data-target="{{ $hrStats['leave_balance'] }}">0</p>
                    </div>
                </div>
            </div>

            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Remaining</p>
                        <p class="text-2xl font-bold text-slate-800 count" data-target="{{ $hrStats['remaining_leaves'] }}">0</p>
                    </div>
                </div>
            </div>

            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Requests</p>
                        <p class="text-2xl font-bold text-slate-800 count" data-target="{{ $hrStats['requests'] }}">0</p>
                    </div>
                </div>
            </div>

            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-slate-800 count" data-target="{{ $hrStats['pending_approval'] }}">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Counter Animation
    document.querySelectorAll('.count').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));
        if(isNaN(target)) return;
        
        let count = 0;
        const inc = target / 50;
        if(target > 0) {
            const timer = setInterval(() => {
                count += inc;
                if(count >= target) {
                    el.innerText = target;
                    clearInterval(timer);
                } else {
                    el.innerText = Math.ceil(count);
                }
            }, 20);
        }
    });

    // Carousel Logic
    let curAnn = 0;
    const slides = document.querySelectorAll('.announcement-slide');
    const totalSlides = slides.length;

    function showAnn(index) {
        slides.forEach((slide, i) => {
            if (i === index) slide.classList.remove('hidden');
            else slide.classList.add('hidden');
        });
    }

    function nextAnn() {
        curAnn = (curAnn + 1) % totalSlides;
        showAnn(curAnn);
    }

    function prevAnn() {
        curAnn = (curAnn - 1 + totalSlides) % totalSlides;
        showAnn(curAnn);
    }
</script>
@endsection
