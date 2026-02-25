@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp

<aside
    class="w-64 flex-shrink-0 flex flex-col bg-white shadow-xl border-r border-slate-100 relative z-20 hidden md:flex">
    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-center border-b border-slate-100 px-4 text-center">
        <a href="{{ route('dashboard') }}">
            @php
                $sLogoPath = \App\Models\AppSetting::where('key', 'logo_path')->value('value');
                $sLogoUrl = $sLogoPath ? asset('uploads/' . $sLogoPath) : asset('images/logo.png');
            @endphp
            <img src="{{ $sLogoUrl }}" alt="IQC Logo" class="h-10 w-auto object-contain">
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 flex flex-col py-4 px-3 overflow-y-auto">
        @if($user && in_array($user->user_type, ['emp', 'eqa']))
            @include('layouts.sidebar.menus.emp')
        @elseif($user && in_array($user->user_type, ['hr', 'admin_hr', 'sys_admin', 'root', 'eqa']))
            @include('layouts.sidebar.menus.manager')
        @endif
    </nav>

</aside>