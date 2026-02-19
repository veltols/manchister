<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - IQC Sense</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        primary: '#4f46e5',          // Indigo-600
                        'primary-dark': '#4338ca',   // Indigo-700
                        secondary: '#9333ea',        // Purple-600
                        'secondary-dark': '#7e22ce', // Purple-700
                        brand: {
                            DEFAULT: '#004F68',
                            dark: '#00384a',
                            light: '#006a8a',
                            accent: '#0088b3',
                        }
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #4f46e5 0%, #9333ea 100%)',
                        'gradient-custom': 'linear-gradient(135deg, #4f46e5 0%, #9333ea 100%)',
                        'gradient-brand': 'linear-gradient(135deg, #004F68 0%, #006a8a 100%)',
                    }
                }
            }
        }
    </script>

    <style>
        /* Glassmorphism Base */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-dark {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Animated Background */
        .animated-bg {
            background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
            position: relative;
            overflow: hidden;
        }

        .animated-bg::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Sidebar Gradient */
        .sidebar-gradient {
            background: linear-gradient(180deg, var(--theme-color) 0%, var(--theme-secondary) 100%);
            position: relative;
        }

        .sidebar-gradient::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40%;
            background-image: url("/images/pattern.png");
            background-size: 250px;
            background-repeat: repeat;
            opacity: 0.12;
            pointer-events: none;
            mask-image: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
            -webkit-mask-image: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
            z-index: 0;
        }

        /* Nav Items */
        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 0 solid transparent;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, #06b6d4, #3b82f6);
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            height: 60%;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            scale: 1.02;
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.12);
            border-left: 3px solid #06b6d4;
        }

        /* Cards with Hover Effect */
        .premium-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            transition: all 0.5s ease;
        }

        .stat-card:hover::before {
            top: -25%;
            right: -25%;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* Table Styles */
        .premium-table {
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .premium-table thead th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #64748b;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border: none;
        }

        .premium-table thead th:first-child {
            border-radius: 12px 0 0 12px;
        }

        .premium-table thead th:last-child {
            border-radius: 0 12px 12px 0;
        }

        .premium-table tbody tr {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .premium-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }

        .premium-table tbody td {
            padding: 1.25rem 1.5rem;
            border: none;
            background: white;
        }

        .premium-table tbody td:first-child {
            border-radius: 12px 0 0 12px;
        }

        .premium-table tbody td:last-child {
            border-radius: 0 12px 12px 0;
        }

        /* Input Styles */
        .premium-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(99, 102, 241, 0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .premium-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: white;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Buttons */
        .premium-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
            color: white !important;
            font-weight: 600;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 79, 104, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            text-decoration: none !important;
        }

        .premium-button:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 79, 104, 0.4);
            filter: brightness(1.1);
        }

        .premium-button:active {
            transform: scale(0.95);
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            /* Equal to modal-lg */
            margin: 1.5rem;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid rgba(0, 0, 0, 0.05);
            animation: modalScaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes modalScaleUp {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #004F68, #006a8a);
            border-radius: 0px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #004F68, #006a8a);
        }

        /* Premium Text Color */
        .text-premium {
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        @keyframes bellShake {

            0%,
            100% {
                transform: rotate(0);
            }

            20% {
                transform: rotate(15deg);
            }

            40% {
                transform: rotate(-15deg);
            }

            60% {
                transform: rotate(10deg);
            }

            80% {
                transform: rotate(-10deg);
            }
        }

        .group-hover\:shake {
            animation: bellShake 0.5s ease-in-out;
        }
    </style>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global SweetAlert Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Handle Laravel Session Flashes with SweetAlert
        window.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif
            @if(session('error'))
                Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
            @endif
            @if(session('warning'))
                Toast.fire({ icon: 'warning', title: '{{ session('warning') }}' });
            @endif
            @if($errors->any())
                Toast.fire({ icon: 'error', title: '{{ $errors->first() }}' });
            @endif
        });
    </script>
    @stack('styles')
    <style>
        :root {
            --theme-color:
                {{ $themeColor ?? '#004F68' }}
            ;
            --theme-secondary:
                {{ $themeSecondary ?? '#00384a' }}
            ;
        }
    </style>
</head>

<body class="bg-slate-50 font-sans h-screen flex overflow-hidden">

    <!-- Sidebar -->
    @include('layouts.sidebar.index')

    <!-- Main Content -->
    <div x-data="{ mobileSidebarOpen: false }" class="flex-1 flex flex-col h-full relative min-w-0 overflow-hidden">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileSidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 z-40 md:hidden"
            @click="mobileSidebarOpen = false" style="display: none;"></div>

        <!-- Mobile Sidebar -->
        <div x-show="mobileSidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 w-64 bg-slate-900 sidebar-gradient z-50 md:hidden flex flex-col"
            style="display: none;">

            <div class="h-20 flex items-center justify-between px-4 border-b border-white/10">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="IQC Logo" class="h-10 w-auto">
                </a>
                <button @click="mobileSidebarOpen = false" class="text-white hover:text-slate-300">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                @php $user = Auth::user(); @endphp
                @if($user && $user->user_type == 'emp')
                    @include('layouts.sidebar.menus.emp')
                @elseif($user && in_array($user->user_type, ['hr', 'admin_hr', 'sys_admin', 'root', 'eqa']))
                    @include('layouts.sidebar.menus.manager')
                @endif
            </nav>
        </div>

        <!-- Header -->
        <header
            class="h-24 bg-white/80 backdrop-blur-lg flex items-center justify-between px-4 md:px-8 border-b border-slate-200/50 z-10 shadow-sm shrink-0">
            <div class="flex items-center gap-3">
                <!-- Mobile Menu Toggle -->
                <button @click="mobileSidebarOpen = true"
                    class="md:hidden w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 transition-colors text-slate-600">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div class="flex items-center gap-3 md:gap-4">
                    <img src="{{ asset('images/connect_icon.png') }}" class="h-10 md:h-12 w-auto" alt="Icon">
                    <div>
                        <h1 class="text-xl md:text-2xl font-display font-bold text-premium leading-tight">
                            @yield('title', 'Dashboard')
                        </h1>
                        <p class="text-xs md:text-sm text-slate-500 mt-0.5">@yield('subtitle', 'Welcome back')</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">

                <div class="flex items-center gap-3">
                    @php
                        $user = Auth::user();
                        $notifRoute = 'notifications.index';
                        $chatRoute = 'messages.index';
                        $unreadNotifs = 0;
                        $unreadMessages = 0;

                        if ($user) {
                            // Determine routes
                            if (in_array($user->user_type, ['hr', 'admin_hr'])) {
                                $notifRoute = 'hr.notifications.index';
                                $chatRoute = 'hr.messages.index';
                            } elseif (in_array($user->user_type, ['root', 'sys_admin'])) {
                                $notifRoute = 'admin.notifications.index';
                                $chatRoute = 'admin.messages.index';
                            } elseif ($user->user_type == 'emp') {
                                $notifRoute = 'emp.notifications.index';
                                $chatRoute = 'emp.messages.index';
                            }

                            $unreadNotifs = $user->unread_notifications_count;
                            $unreadMessages = $user->unread_messages_count;
                        }
                    @endphp

                    <div class="flex items-center bg-white p-1 rounded-2xl border border-slate-200/50 shadow-sm">
                        <a href="{{ route($chatRoute) }}"
                            class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-slate-50 transition-all duration-300 group relative"
                            title="Messages">
                            <i class="fa-solid fa-comment-dots group-hover:scale-110"></i>
                            @if($unreadMessages > 0)
                                <span class="absolute top-2 right-2 flex h-4 w-4">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-4 w-4 bg-indigo-600 text-[9px] text-white font-bold items-center justify-center transition-transform duration-200 ease-out">
                                        {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                                    </span>
                                </span>
                            @endif
                        </a>

                        <div class="w-[1px] h-6 bg-slate-100 mx-1"></div>

                        <a href="{{ route($notifRoute) }}"
                            class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-slate-50 transition-all duration-300 group relative"
                            title="Notifications">
                            <i class="fa-solid fa-bell group-hover:scale-110"></i>
                            @if($unreadNotifs > 0)
                                <span class="absolute top-2 right-2 flex h-4 w-4">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-[9px] text-white font-bold items-center justify-center">
                                        {{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}
                                    </span>
                                </span>
                            @endif
                        </a>
                    </div>

                    @if($user && ($user->user_type == 'emp' || $user->user_type == 'eqa'))
                        <div class="w-px h-6 bg-slate-200 mx-1"></div>
                        <a href="{{ route('emp.settings.index') }}"
                            class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-slate-50 transition-all duration-300 group relative"
                            title="Settings">
                            <i class="fa-solid fa-cog group-hover:rotate-90 transition-transform duration-500"></i>
                        </a>
                    @endif

                    <div class="w-px h-6 bg-slate-200 mx-1"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false, showPasswordModal: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-2 py-1.5 rounded-xl hover:bg-slate-50 transition-all group">
                            <div
                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#004F68] to-[#00384a] flex items-center justify-center shadow-lg group-hover:scale-105 transition-all">
                                <i class="fa-solid fa-user text-white text-xs"></i>
                            </div>
                            @if($user && $user->employee && $user->employee->status)
                                <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white"
                                    style="background-color: {{ $user->employee->status->staus_color }};"></div>
                            @endif
                            <div class="hidden lg:block text-left mr-1">
                                <p class="text-[10px] font-bold text-slate-900 leading-none">Account</p>
                                <p class="text-[9px] text-slate-500 leading-none mt-1">
                                    {{ ucfirst($user->user_type ?? 'User') }}</p>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-300 group-hover:text-indigo-500 transition-all"
                                :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50 overflow-hidden"
                            style="display: none;">

                            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Logged in
                                    as</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $user->user_email }}</p>
                            </div>

                            @if(isset($userStatuses) && $user && $user->employee)
                                <div class="px-2 py-2">
                                    <p class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Set
                                        Status</p>
                                    <div class="grid grid-cols-1 gap-1">
                                        @foreach($userStatuses as $status)
                                            <button onclick="updateUserStatus({{ $status->staus_id }})"
                                                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 transition-all text-left w-full group
                                                        {{ $user->employee->emp_status_id == $status->staus_id ? 'bg-slate-50' : '' }}">
                                                <div class="w-2 h-2 rounded-full"
                                                    style="background-color: {{ $status->staus_color }}"></div>
                                                <span
                                                    class="text-xs font-medium text-slate-600 group-hover:text-slate-900">{{ $status->staus_name }}</span>
                                                @if($user->employee->emp_status_id == $status->staus_id)
                                                    <i class="fa-solid fa-check text-xs text-indigo-600 ml-auto"></i>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="h-px bg-slate-50 my-1"></div>
                            @endif

                            <div class="px-2">
                                <button @click="showPasswordModal = true; open = false"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all group text-left">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-indigo-50 transition-colors">
                                        <i class="fa-solid fa-key text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium">Change Password</span>
                                </button>

                                <div class="h-px bg-slate-50 my-1"></div>

                                <a href="{{ route('logout') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 transition-all group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center group-hover:bg-rose-100 transition-colors">
                                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium">Logout</span>
                                </a>
                            </div>
                        </div>

                        <!-- Change Password Modal -->
                        <template x-teleport="body">
                            <div x-show="showPasswordModal"
                                class="fixed inset-0 z-[100] flex items-center justify-center px-4"
                                style="display: none;" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                                    @click="showPasswordModal = false"></div>
                                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden transform"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100">
                                    <div class="px-8 pt-8 pb-6">
                                        <div class="flex items-center justify-between mb-6">
                                            <h3 class="text-xl font-bold text-slate-900">Change Password</h3>
                                            <button @click="showPasswordModal = false"
                                                class="text-slate-400 hover:text-slate-600">
                                                <i class="fa-solid fa-xmark text-xl"></i>
                                            </button>
                                        </div>
                                        <form action="{{ route('profile.change-password') }}" method="POST">
                                            @csrf
                                            <div class="space-y-4">
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Current
                                                        Password</label>
                                                    <input type="password" name="current_password" required
                                                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New
                                                        Password</label>
                                                    <input type="password" name="new_password" required
                                                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Confirm
                                                        New Password</label>
                                                    <input type="password" name="new_password_confirmation" required
                                                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                                </div>
                                            </div>
                                            <div class="mt-8 flex gap-3">
                                                <button type="button" @click="showPasswordModal = false"
                                                    class="flex-1 px-4 py-3 rounded-xl text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="flex-1 px-4 py-3 rounded-xl text-sm font-bold text-white bg-[#004F68] hover:bg-[#00384a] shadow-lg transition-all">
                                                    Update Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area  max-w-7xl-->
        <main
            class="flex-1 overflow-y-auto overflow-x-hidden p-8 bg-gradient-to-br from-slate-50 via-cyan-50/10 to-blue-50/10">
            <div class=" mx-auto w-full animate-fade-in-up">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Global real-time header message count update
        @auth
            (function () {
                const messageLink = document.querySelector('a[href="{{ route($chatRoute) }}"]');

                console.log('Message count polling initialized');
                console.log('Message link:', messageLink);
                console.log('Chat route:', '{{ route($chatRoute) }}');

                if (!messageLink) {
                    console.error('Message link not found!');
                    return; // Exit if message link not found
                }

                // Determine the correct route based on user type
                let unreadCountRoute = '';
                @if(auth()->user()->user_type === 'root' || auth()->user()->user_type === 'sys_admin')
                    unreadCountRoute = '/admin/messages-unread-count';
                @elseif(in_array(auth()->user()->user_type, ['hr', 'admin_hr']))
                    unreadCountRoute = '/hr/messages-unread-count';
                @else
                    unreadCountRoute = '/emp/messages-unread-count';
                @endif

                console.log('Unread count route:', unreadCountRoute);

                function updateHeaderMessageCount() {
                    fetch(unreadCountRoute)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Unread count response:', data);
                            if (data.success) {
                                const count = data.unread_count;
                                console.log('Count:', count);

                                // Remove any existing badge first
                                const existingBadge = messageLink.querySelector('span.absolute.top-2.right-2');
                                if (existingBadge) {
                                    existingBadge.remove();
                                    console.log('Removed existing badge');
                                }

                                // Create new badge if count > 0
                                if (count > 0) {
                                    const displayCount = count > 9 ? '9+' : count;
                                    const badgeContainer = document.createElement('span');
                                    badgeContainer.className = 'absolute top-2 right-2 flex h-4 w-4';
                                    badgeContainer.innerHTML = `
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-indigo-600 text-[9px] text-white font-bold items-center justify-center transition-transform duration-200 ease-out">
                                            ${displayCount}
                                        </span>
                                    `;
                                    messageLink.appendChild(badgeContainer);
                                    console.log('Created new badge with count:', displayCount);
                                }
                            }
                        })
                        .catch(error => console.error('Error fetching unread count:', error));
                }

                // Poll every 5 seconds
                setInterval(updateHeaderMessageCount, 5000);

                // Initial update
                updateHeaderMessageCount();
            })();
        @endauth
    </script>

    <script>
        function updateUserStatus(statusId) {
            fetch("{{ route('emp.status.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ new_status: statusId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data[0].success) {
                        window.location.reload();
                    } else {
                        alert('Failed to update status');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

    @stack('scripts')
</body>

</html>