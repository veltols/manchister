@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp

<aside class="sidebar-gradient w-40 flex-shrink-0 flex flex-col text-white shadow-2xl relative z-20 hidden md:flex">
    <!-- Logo Area -->
    <div class="h-20 flex flex-col items-center justify-center border-b border-white/10 px-2 text-center">
        <img src="{{ asset('images/logo.png') }}" alt="IQC Logo" class="w-10 h-10 object-contain mb-1">
        <span class="font-display text-[10px] font-bold text-white uppercase tracking-tighter">IQC Sense</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        @if($user && $user->user_type == 'emp')
            @include('layouts.sidebar.menus.emp')
        @elseif($user && in_array($user->user_type, ['hr', 'admin_hr', 'sys_admin', 'root', 'eqa']))
            @include('layouts.sidebar.menus.manager')
        @endif
    </nav>

</aside>
