<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - IQC System</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #4f46e5 0%, #9333ea 100%)', 
                        'gradient-custom': 'linear-gradient(135deg, #4f46e5 0%, #9333ea 100%)',
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
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
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
</head>
<body class="bg-slate-50 font-sans h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar-gradient w-64 flex-shrink-0 flex flex-col text-white shadow-2xl relative z-20 hidden md:flex">
        <!-- Logo Area -->
        <div class="h-20 flex items-center justify-center border-b border-white/10 px-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="IQC Logo" class="w-12 h-12 object-contain">
                <span class="font-display text-xl font-bold text-white">IQC System</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
            @php $user = Auth::user(); @endphp

            @if($user && $user->user_type == 'emp')
                <!-- Employee Self Service -->
                <div class="px-4 pb-2 pt-4">
                    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Self Service</p>
                </div>
                <a href="{{ route('emp.dashboard') }}" class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-chart-line text-lg w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('emp.tickets.index') }}" class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-ticket text-lg w-5"></i>
                    <span class="font-medium">IT Tickets</span>
                </a>
                <a href="{{ route('emp.leaves.index') }}" class="nav-item {{ request()->routeIs('emp.leaves.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-calendar text-lg w-5"></i>
                    <span class="font-medium">Leaves</span>
                </a>
                <a href="{{ route('emp.tasks.index') }}" class="nav-item {{ request()->routeIs('emp.tasks.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-list-check text-lg w-5"></i>
                    <span class="font-medium">Tasks</span>
                </a>
                <a href="{{ route('emp.messages.index') }}" class="nav-item {{ request()->routeIs('emp.messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-envelope text-lg w-5"></i>
                    <span class="font-medium">Messages</span>
                </a>

            @elseif($user && ($user->user_type == 'hr' || $user->user_type == 'admin_hr' || $user->user_type == 'sys_admin' || $user->user_type == 'root'))
                
                <a href="{{ in_array($user->user_type, ['root', 'sys_admin']) ? route('admin.dashboard') : route('hr.dashboard') }}" class="nav-item {{ (request()->routeIs('hr.dashboard') || request()->routeIs('admin.dashboard')) ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white mb-4">
                    <i class="fa-solid fa-chart-line text-lg w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- HR Management -->
                @if(in_array($user->user_type, ['hr', 'admin_hr']))
                <div class="px-4 pb-2 pt-2">
                    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">HR Management</p>
                </div>
                <a href="{{ route('hr.employees.index') }}" class="nav-item {{ request()->routeIs('hr.employees.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-users text-lg w-5"></i>
                    <span class="font-medium">Employees</span>
                </a>
                <a href="{{ route('hr.leaves.index') }}" class="nav-item {{ request()->routeIs('hr.leaves.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-calendar-days text-lg w-5"></i>
                    <span class="font-medium">Leaves</span>
                </a>
                <a href="{{ route('hr.permissions.index') }}" class="nav-item {{ request()->routeIs('hr.permissions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-clock text-lg w-5"></i>
                    <span class="font-medium">Permissions</span>
                </a>
                <a href="{{ route('hr.attendance.index') }}" class="nav-item {{ request()->routeIs('hr.attendance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-clipboard-check text-lg w-5"></i>
                    <span class="font-medium">Attendance</span>
                </a>
                <a href="{{ route('hr.disciplinary.index') }}" class="nav-item {{ request()->routeIs('hr.disciplinary.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
                    <i class="fa-solid fa-gavel text-lg w-5"></i>
                    <span class="font-medium">Disciplinary</span>
                </a>
                <a href="{{ route('hr.performance.index') }}" class="nav-item {{ request()->routeIs('hr.performance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
                    <i class="fa-solid fa-star text-lg w-5"></i>
                    <span class="font-medium">Performance</span>
                </a>
                <a href="{{ route('hr.exit_interviews.index') }}" class="nav-item {{ request()->routeIs('hr.exit_interviews.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
                    <i class="fa-solid fa-door-open text-lg w-5"></i>
                    <span class="font-medium">Exit Interviews</span>
                </a>
                @endif

                <!-- Organization -->
                <div class="px-4 pb-2 pt-4">
                    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Organization</p>
                </div>
                <a href="{{ route('hr.departments.index') }}" class="nav-item {{ request()->routeIs('hr.departments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-building text-lg w-5"></i>
                    <span class="font-medium">Departments</span>
                </a>
                <a href="{{ route('hr.groups.index') }}" class="nav-item {{ request()->routeIs('hr.groups.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
                    <i class="fa-solid fa-user-group text-lg w-5"></i>
                    <span class="font-medium">Groups</span>
                </a>
                <a href="{{ route('rc.atps.index') }}" class="nav-item {{ request()->routeIs('rc.atps.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-graduation-cap text-lg w-5"></i>
                    <span class="font-medium">Training (RC)</span>
                </a>

                <!-- Resources -->
                <div class="px-4 pb-2 pt-4">
                    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Resources</p>
                </div>
                <a href="{{ route('hr.assets.index') }}" class="nav-item {{ request()->routeIs('hr.assets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-laptop text-lg w-5"></i>
                    <span class="font-medium">Assets</span>
                </a>
                <a href="{{ route('hr.documents.index') }}" class="nav-item {{ request()->routeIs('hr.documents.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
                    <i class="fa-solid fa-file-lines text-lg w-5"></i>
                    <span class="font-medium">Documents</span>
                </a>
                <a href="{{ route('hr.communications.index') }}" class="nav-item {{ request()->routeIs('hr.communications.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
                    <i class="fa-solid fa-bullhorn text-lg w-5"></i>
                    <span class="font-medium">Communications</span>
                </a>

                @if($user->user_type == 'root' || $user->user_type == 'sys_admin')
                <!-- System -->
                <div class="px-4 pb-2 pt-4">
                    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">System</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
                    <i class="fa-solid fa-users-gear text-lg w-5"></i>
                    <span class="font-medium">Users</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
                    <i class="fa-solid fa-cog text-lg w-5"></i>
                    <span class="font-medium">Settings</span>
                </a>
                @endif

            @endif
        </nav>
        
        <!-- Profile Section -->
        <div class="p-4 border-t border-white/10 bg-black/10">
            <div class="glass-dark rounded-xl p-3 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
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

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full relative">
        
        <!-- Header -->
        <header class="h-20 bg-white/80 backdrop-blur-lg flex items-center justify-between px-8 border-b border-slate-200/50 z-10 shadow-sm">
            <div>
                <h1 class="text-2xl font-display font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    @yield('title', 'Dashboard')
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">@yield('subtitle', 'Welcome back')</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" class="premium-input pl-11 pr-4 py-2.5 w-80 text-sm" placeholder="Search anything...">
                </div>
                
                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 rounded-xl glass hover:bg-white/90 flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
                        <i class="fa-solid fa-bell"></i>
                    </button>
                    <button class="w-10 h-10 rounded-xl glass hover:bg-white/90 flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto p-8 bg-gradient-to-br from-slate-50 via-cyan-50/10 to-blue-50/10">
            <div class="max-w-7xl mx-auto w-full animate-fade-in-up">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
