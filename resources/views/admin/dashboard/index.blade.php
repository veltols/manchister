@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('subtitle', 'System Overview & Statistics')

@section('actions')
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-indigo-500">
                <i class="fa-solid fa-calendar-alt"></i>
            </div>
            <select name="mode" onchange="this.form.submit()" class="appearance-none pl-10 pr-10 py-2.5 text-sm font-semibold rounded-xl bg-white border border-slate-200 text-slate-700 shadow-sm focus:ring-2 focus:ring-indigo-500 hover:border-indigo-300 transition-all cursor-pointer">
                <option value="all" {{ $mode == 'all' ? 'selected' : '' }}>All Time</option>
                <option value="today" {{ $mode == 'today' ? 'selected' : '' }}>Today</option>
                <option value="this_week" {{ $mode == 'this_week' ? 'selected' : '' }}>This Week</option>
                <option value="this_month" {{ $mode == 'this_month' ? 'selected' : '' }}>This Month</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </div>
        </div>
    </form>
@endsection

@section('content')

    <!-- TICKETS KPI -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
            <h2 class="text-xl font-display font-bold text-slate-800 tracking-tight">Support Tickets Overview</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Total (Full Width Hero) -->
            <div class="lg:col-span-4 bg-gradient-to-r from-indigo-600 to-violet-600 p-8 rounded-2xl shadow-xl shadow-indigo-200 text-white relative group overflow-hidden transition-all hover:-translate-y-1">
                <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-white/10 to-transparent pointer-events-none"></div>
                <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-20 scale-150 transform rotate-12">
                    <i class="fa-solid fa-ticket text-9xl"></i>
                </div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl shadow-inner border border-white/10">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="text-indigo-100 font-medium text-lg mb-1">Total System Tickets</p>
                            <h3 class="text-5xl font-black tracking-tight text-white">{{ $totalTickets }}</h3>
                        </div>
                    </div>
                    
                    <div class="flex gap-8 border-l border-white/20 pl-8 hidden md:flex">
                        <div class="text-center">
                             <span class="block text-3xl font-bold">{{ $totalOpen }}</span>
                             <span class="text-xs text-indigo-200 uppercase tracking-wider font-semibold">Open</span>
                        </div>
                        <div class="text-center">
                             <span class="block text-3xl font-bold">{{ $totalProgress }}</span>
                             <span class="text-xs text-indigo-200 uppercase tracking-wider font-semibold">Processing</span>
                        </div>
                         <div class="text-center">
                             <span class="block text-3xl font-bold">{{ $totalResolved }}</span>
                             <span class="text-xs text-indigo-200 uppercase tracking-wider font-semibold">Resolved</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Open -->
            <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(239,68,68,0.1)] border border-slate-100 relative group overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg">
                 <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Open</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalOpen }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-envelope-open"></i>
                    </div>
                </div>
                 <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-red-500 h-full rounded-full" style="width: {{ $totalTickets > 0 ? ($totalOpen/$totalTickets)*100 : 0 }}%"></div>
                </div>
            </div>
            
            <!-- In Progress -->
            <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(59,130,246,0.1)] border border-slate-100 relative group overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">In Progress</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalProgress }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-spinner animate-spin-slow"></i>
                    </div>
                </div>
                 <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-blue-500 h-full rounded-full" style="width: {{ $totalTickets > 0 ? ($totalProgress/$totalTickets)*100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(34,197,94,0.1)] border border-slate-100 relative group overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Resolved</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalResolved }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                </div>
                 <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-green-500 h-full rounded-full" style="width: {{ $totalTickets > 0 ? ($totalResolved/$totalTickets)*100 : 0 }}%"></div>
                </div>
            </div>
            
             <!-- Unassigned (Moved to end for better flow or keep order? User wanted 4 distinct. If I make simple list: Open, Progress, Resolved, Unassigned -> That is 4 cards.) -->
            <!-- Unassigned -->
            <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(249,115,22,0.1)] border border-slate-100 relative group overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Unassigned</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalUnassigned }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                </div>
                 <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-orange-500 h-full rounded-full" style="width: {{ $totalTickets > 0 ? ($totalUnassigned/$totalTickets)*100 : 0 }}%"></div>
                </div>
            </div>
    </div>


    <!-- MAIN CHARTS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        
        <!-- Tickets by Dept -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
            <h3 class="font-display font-bold text-lg text-slate-800 mb-8 border-b border-slate-100 pb-4">Tickets by Department</h3>
            <div class="relative flex-1 min-h-[300px] flex items-center justify-center">
                @if(array_sum($ticketsByDeptCounts) > 0)
                    <canvas id="ticketsDeptChart"></canvas>
                @else
                    <div class="text-center text-slate-400">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-chart-pie text-3xl opacity-20"></i>
                        </div>
                        <p class="text-sm font-medium">No ticket data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tickets by Priority -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
            <h3 class="font-display font-bold text-lg text-slate-800 mb-8 border-b border-slate-100 pb-4">Tickets by Priority</h3>
            <div class="relative flex-1 min-h-[300px] flex items-center justify-center">
                @if(array_sum($ticketsByPriorityCounts) > 0)
                    <canvas id="ticketsPrioChart"></canvas>
                @else
                    <div class="text-center text-slate-400">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-chart-pie text-3xl opacity-20"></i>
                        </div>
                        <p class="text-sm font-medium">No priority data available</p>
                    </div>
                @endif
            </div>
        </div>

    </div>


     <!-- ASSETS KPI -->
     <div class="mb-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-teal-500 rounded-full"></div>
            <h2 class="text-xl font-display font-bold text-slate-800 tracking-tight">Assets Overview</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Total -->
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-6 rounded-2xl shadow-lg shadow-indigo-200 text-white relative group overflow-hidden transition-all hover:-translate-y-1">
                <div class="absolute right-0 top-0 opacity-10 scale-150 transform translate-x-4 -translate-y-4">
                    <i class="fa-solid fa-cubes text-9xl"></i>
                </div>
                <p class="text-xs font-bold text-indigo-200 uppercase tracking-wider mb-1">Total Assets</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-4xl font-black tracking-tight">{{ $totalAssets }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs font-medium text-indigo-200 bg-white/10 w-fit px-3 py-1 rounded-full backdrop-blur-sm">
                    <i class="fa-solid fa-database"></i> System Total
                </div>
            </div>

            <!-- In Stock -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative group overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg">
                <div class="flex justify-between items-start mb-4">
                     <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">In Stock</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalAssetsInStock }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-box"></i>
                    </div>
                </div>
            </div>

            <!-- In Use -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative group overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg">
                <div class="flex justify-between items-start mb-4">
                     <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">In Use</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalAssetsInUse }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user-tag"></i>
                    </div>
                </div>
            </div>

             <!-- Retired -->
             <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative group overflow-hidden transition-all hover:-translate-y-1 hover:shadow-lg">
                <div class="flex justify-between items-start mb-4">
                     <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Retired</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalAssetsRetired }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- ASSETS CHARTS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        
        <!-- Assets by Dept -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
            <h3 class="font-display font-bold text-lg text-slate-800 mb-8 border-b border-slate-100 pb-4">Assets by Department</h3>
            <div class="relative flex-1 min-h-[250px] flex items-center justify-center">
                @if(array_sum($assetsByDeptCounts) > 0)
                    <canvas id="assetsDeptChart"></canvas>
                @else
                    <div class="text-center text-slate-400">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-chart-pie text-2xl opacity-20"></i>
                        </div>
                        <p class="text-xs font-medium">No asset data</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assets by Category -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
            <h3 class="font-display font-bold text-lg text-slate-800 mb-8 border-b border-slate-100 pb-4">Assets by Category</h3>
            <div class="relative flex-1 min-h-[250px] flex items-center justify-center">
                @if(array_sum($assetsByCatCounts) > 0)
                    <canvas id="assetsCatChart"></canvas>
                @else
                    <div class="text-center text-slate-400">
                         <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-chart-pie text-2xl opacity-20"></i>
                        </div>
                        <p class="text-xs font-medium">No asset data</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Assets by Status -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
             <h3 class="font-display font-bold text-lg text-slate-800 mb-8 border-b border-slate-100 pb-4">Assets by Status</h3>
            <div class="relative flex-1 min-h-[250px] flex items-center justify-center">
                @if(array_sum($assetsByStatusCounts) > 0)
                    <canvas id="assetsStatusChart"></canvas>
                @else
                    <div class="text-center text-slate-400">
                         <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-chart-pie text-2xl opacity-20"></i>
                        </div>
                        <p class="text-xs font-medium">No status data</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- RECENT TICKETS -->
    <div class="bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden mb-12">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-white to-slate-50">
            <div class="flex items-center gap-3">
                 <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-lg text-slate-800">Recent Activity</h3>
                    <p class="text-xs text-slate-500 font-medium">Latest support tickets raised</p>
                </div>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-sm font-bold shadow-sm hover:border-indigo-600 hover:text-indigo-600 transition-all">
                <span>View All</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Ref ID</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Subject</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Requested By</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTickets as $ticket)
                        <tr class="group hover:bg-slate-50 transition-colors cursor-default">
                            <td class="px-8 py-5">
                                <span class="font-mono font-semibold text-slate-700 bg-slate-100 px-2 py-1 rounded text-xs select-all">#{{ $ticket->ticket_ref ?? $ticket->ticket_id }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $ticket->ticket_subject }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xs font-bold shadow-sm ring-2 ring-white">
                                        {{ substr($ticket->added_employee, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-600">{{ $ticket->added_employee }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm font-medium text-slate-500">
                                {{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($ticket->status_id == 1)
                                    <span class="px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-100 rounded-full shadow-sm">Open</span>
                                @elseif($ticket->status_id == 2)
                                    <span class="px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 rounded-full shadow-sm">In Progress</span>
                                @elseif($ticket->status_id == 3)
                                    <span class="px-3 py-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-100 rounded-full shadow-sm">Resolved</span>
                                @else
                                    <span class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-full shadow-sm">Unknown</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-inbox text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No recent tickets found.</p>
                                    <p class="text-xs text-slate-400 mt-1">New support requests will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Colors
        const colors = {
            primary: '#4f46e5', // Indigo-600
            primaryLight: '#818cf8',
            accent: '#06b6d4',
            success: '#22c55e',
            warning: '#f59e0b',
            danger: '#ef4444',
            slate: '#64748b',
            slateLight: '#cbd5e1'
        };

        // Common Chart Options for Clean Look
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 10
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    align: 'center',
                    labels: { 
                        usePointStyle: true, 
                        pointStyle: 'circle',
                        padding: 20,
                        font: {
                            family: "'Plus Jakarta Sans', sans-serif",
                            size: 11,
                            weight: 600
                        },
                        color: '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                    bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            cutout: '75%',
            elements: {
                arc: {
                    borderWidth: 0, 
                    hoverOffset: 10
                }
            }
        };

        // 1. Tickets by Dept
        const ticketsDeptChartEl = document.getElementById('ticketsDeptChart');
        if (ticketsDeptChartEl) {
            new Chart(ticketsDeptChartEl, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($ticketsByDeptLabels) !!},
                    datasets: [{
                        data: {!! json_encode($ticketsByDeptCounts) !!},
                        backgroundColor: [colors.primary, colors.accent, colors.success, colors.warning, colors.danger],
                        borderWidth: 0
                    }]
                },
                options: chartOptions
            });
        }

        // 2. Tickets by Priority
        const ticketsPrioChartEl = document.getElementById('ticketsPrioChart');
        if (ticketsPrioChartEl) {
            new Chart(ticketsPrioChartEl, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($ticketsByPriorityLabels) !!},
                    datasets: [{
                        data: {!! json_encode($ticketsByPriorityCounts) !!},
                        backgroundColor: [colors.danger, colors.warning, colors.success, colors.slate],
                        borderWidth: 0
                    }]
                },
                options: chartOptions
            });
        }

         // 3. Assets by Dept
         const assetsDeptChartEl = document.getElementById('assetsDeptChart');
         if (assetsDeptChartEl) {
            new Chart(assetsDeptChartEl, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($assetsByDeptLabels) !!},
                    datasets: [{
                        data: {!! json_encode($assetsByDeptCounts) !!},
                        backgroundColor: [colors.primary, colors.accent, colors.success, colors.warning, colors.danger],
                        borderWidth: 0
                    }]
                },
                options: chartOptions
            });
         }

         // 4. Assets by Category
         const assetsCatChartEl = document.getElementById('assetsCatChart');
         if (assetsCatChartEl) {
             new Chart(assetsCatChartEl, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($assetsByCatLabels) !!},
                    datasets: [{
                        data: {!! json_encode($assetsByCatCounts) !!},
                        backgroundColor: [colors.primaryLight, colors.accent, colors.slate, colors.warning],
                        borderWidth: 0
                    }]
                },
                options: chartOptions
            });
         }

         // 5. Assets by Status
         const assetsStatusChartEl = document.getElementById('assetsStatusChart');
         if (assetsStatusChartEl) {
             new Chart(assetsStatusChartEl, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($assetsByStatusLabels) !!},
                    datasets: [{
                        data: {!! json_encode($assetsByStatusCounts) !!},
                        backgroundColor: [colors.success, colors.primary, colors.danger, colors.warning, colors.slate],
                        borderWidth: 0
                    }]
                },
                options: chartOptions
            });
         }

    </script>

@endsection
