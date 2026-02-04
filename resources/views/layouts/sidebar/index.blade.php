@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp

<aside class="sidebar-gradient w-64 flex-shrink-0 flex flex-col text-white shadow-2xl relative z-20 hidden md:flex">
    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-center border-b border-white/10 px-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="IQC Logo" class="w-12 h-12 object-contain">
            <span class="font-display text-xl font-bold text-white">IQC Sense</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        @if($user && $user->user_type == 'emp')
            @include('layouts.sidebar.menus.emp')
        @elseif($user && in_array($user->user_type, ['hr', 'admin_hr', 'sys_admin', 'root', 'eqa']))
            @include('layouts.sidebar.menus.manager')
        @endif
    </nav>

    <!-- Profile Section -->
    <div class="p-4 border-t border-white/10 bg-black/10">
        <div class="glass-dark rounded-xl p-3 flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                <i class="fa-solid fa-user text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ $user->user_email ?? 'User' }}</p>
                <p class="text-xs text-white/60">{{ ucfirst($user->user_type ?? 'Employee') }}</p>
            </div>
            <a href="{{ route('logout') }}" class="text-white/60 hover:text-white transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>
</aside>
