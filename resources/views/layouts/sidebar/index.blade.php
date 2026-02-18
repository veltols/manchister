@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp

<aside class="sidebar-gradient w-56 flex-shrink-0 flex flex-col text-white shadow-2xl relative z-20 hidden md:flex">
    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-center border-b border-white/10 px-2 text-center">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="IQC Logo" class="w-12 h-12 object-contain">
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 flex flex-col py-6 px-3">
        @if($user && in_array($user->user_type, ['emp', 'eqa']))
            @include('layouts.sidebar.menus.emp')
        @elseif($user && in_array($user->user_type, ['hr', 'admin_hr', 'sys_admin', 'root', 'eqa']))
            @include('layouts.sidebar.menus.manager')
        @endif
    </nav>

</aside>