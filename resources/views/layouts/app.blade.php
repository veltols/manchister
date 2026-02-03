<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - IQC System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

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
            background: linear-gradient(180deg, #004F68 0%, #00384a 100%);
            position: relative;
        }

        .sidebar-gradient::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        /* Nav Items */
        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: linear-gradient(180deg, #06b6d4, #3b82f6);
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            height: 70%;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: rgba(0, 79, 104, 0.25);
            border-left: 4px solid transparent;
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
            max-width: 90vw;
            max-height: 90vh;
            overflow: auto;
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
            background: linear-gradient(180deg, #6366f1, #8b5cf6);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #4f46e5, #7c3aed);
        }

        /* Premium Text Color */
        .text-premium {
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
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
</head>

<body class="bg-slate-50 font-sans h-screen flex overflow-hidden">

    <!-- Sidebar -->
    @include('layouts.sidebar.index')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full relative">

        <!-- Header -->
        <header
            class="h-20 bg-white/80 backdrop-blur-lg flex items-center justify-between px-8 border-b border-slate-200/50 z-10 shadow-sm">
            <div>
                <h1 class="text-2xl font-display font-bold text-premium">
                    @yield('title', 'Dashboard')
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">@yield('subtitle', 'Welcome back')</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" class="premium-input pl-11 pr-4 py-2.5 w-80 text-sm"
                        placeholder="Search anything...">
                </div>

                <div class="flex items-center gap-3">
                    <button
                        class="w-10 h-10 rounded-xl glass hover:bg-white/90 flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
                        <i class="fa-solid fa-bell"></i>
                    </button>
                    <button
                        class="w-10 h-10 rounded-xl glass hover:bg-white/90 flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto p-8 bg-gradient-to-br from-slate-50 via-cyan-50/10 to-blue-50/10">
            <div class="max-w-7xl mx-auto w-full animate-fade-in-up">
                @if(session('success'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate-fade-in">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-center gap-3 animate-fade-in">
                        <i class="fa-solid fa-circle-xmark text-rose-500"></i>
                        <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>

</html>