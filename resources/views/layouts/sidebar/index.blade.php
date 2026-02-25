@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp

<aside
    class="w-64 flex-shrink-0 flex flex-col shadow-2xl relative z-20 hidden md:flex sidebar-gradient-bg">

    <!-- Decorative Orb -->
    <div class="absolute bottom-0 left-0 right-0 h-1/2 pointer-events-none overflow-hidden">
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 w-48 h-48 rounded-full opacity-20"
             style="background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, transparent 70%); filter: blur(30px);"></div>
    </div>

    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-center border-b border-white/15 px-4 text-center bg-white/60 backdrop-blur-sm">
        <a href="{{ route('dashboard') }}">
            @php
                $sLogoPath = \App\Models\AppSetting::where('key', 'logo_path')->value('value');
                $sLogoUrl = $sLogoPath ? asset('uploads/' . $sLogoPath) : asset('images/logo.png');
            @endphp
            <img src="{{ $sLogoUrl }}" alt="IQC Logo" class="h-10 w-auto object-contain sidebar-logo drop-shadow-md">
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 flex flex-col py-4 px-3 overflow-y-auto relative z-10">
        @if($user && in_array($user->user_type, ['emp', 'eqa']))
            @include('layouts.sidebar.menus.emp')
        @elseif($user && in_array($user->user_type, ['hr', 'admin_hr', 'sys_admin', 'root', 'eqa']))
            @include('layouts.sidebar.menus.manager')
        @endif
    </nav>

</aside>