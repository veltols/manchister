@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp

<aside class="w-64 flex-shrink-0 flex flex-col shadow-2xl relative z-20 hidden md:flex sidebar-gradient-bg">

    <!-- Decorative Orb -->
    <div class="absolute bottom-0 left-0 right-0 h-1/2 pointer-events-none overflow-hidden">
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 w-48 h-48 rounded-full opacity-20"
            style="background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, transparent 70%); filter: blur(30px);">
        </div>
    </div>

    <!-- Logo Area - Blended with Sidebar -->
    <div class="h-44 flex items-center justify-center px-4 text-center relative overflow-hidden">
        
        <!-- Subtle Radial Bloom for Prominence (No hard edges) -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/40 blur-3xl"></div>
            <div class="absolute top-10 left-10 w-32 h-32 rounded-full bg-brand-light/10 blur-3xl"></div>
        </div>

        <a href="{{ route('dashboard') }}" class="relative z-10 transition-all duration-500 hover:scale-105 active:scale-95">
            @php
                $sLogoPath = \App\Models\AppSetting::where('key', 'logo_path')->value('value');
                $sLogoUrl = $sLogoPath ? asset('uploads/' . $sLogoPath) : asset('images/logo.png');
            @endphp
            <img src="{{ $sLogoUrl }}" alt="IQC Logo" class="h-28 w-auto object-contain sidebar-logo filter drop-shadow-[0_10px_20px_rgba(0,0,0,0.08)]">
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