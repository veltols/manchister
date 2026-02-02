@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('subtitle', 'System Overview & Statistics')

@section('content')

    <!-- TICKETS KPI -->
    <div class="mb-8">
        <h2 class="text-lg font-display font-bold text-slate-700 mb-4 px-1">Support Tickets Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- Total -->
            <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-ticket text-6xl text-indigo-600"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Tickets</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-premium">{{ $totalTickets }}</h3>
                </div>
            </div>

            <!-- Open -->
            <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-envelope-open text-6xl text-red-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Open</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-red-600">{{ $totalOpen }}</h3>
                </div>
            </div>

            <!-- Unassigned -->
            <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-user-slash text-6xl text-orange-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Unassigned</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-orange-600">{{ $totalUnassigned }}</h3>
                </div>
            </div>

             <!-- In Progress -->
             <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-spinner text-6xl text-blue-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">In Progress</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-blue-600">{{ $totalProgress }}</h3>
                </div>
            </div>

             <!-- Resolved -->
             <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-check-circle text-6xl text-green-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Resolved</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-green-600">{{ $totalResolved }}</h3>
                </div>
            </div>
            
        </div>
    </div>

    <!-- MAIN CHARTS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Tickets by Dept -->
        <div class="premium-card p-6">
            <h3 class="font-display font-bold text-lg text-slate-700 mb-6">Tickets by Department</h3>
            <div class="h-64 relative">
                <canvas id="ticketsDeptChart"></canvas>
            </div>
        </div>

        <!-- Tickets by Priority -->
        <div class="premium-card p-6">
            <h3 class="font-display font-bold text-lg text-slate-700 mb-6">Tickets by Priority</h3>
            <div class="h-64 relative">
                <canvas id="ticketsPrioChart"></canvas>
            </div>
        </div>

    </div>


     <!-- ASSETS KPI -->
     <div class="mb-8">
        <h2 class="text-lg font-display font-bold text-slate-700 mb-4 px-1">Assets Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Total -->
            <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-cubes text-6xl text-indigo-600"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Assets</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-premium">{{ $totalAssets }}</h3>
                </div>
            </div>

            <!-- In Stock -->
            <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-box text-6xl text-blue-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">In Stock</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-blue-600">{{ $totalAssetsInStock }}</h3>
                </div>
            </div>

            <!-- In Use -->
            <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-user-tag text-6xl text-green-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">In Use</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-green-600">{{ $totalAssetsInUse }}</h3>
                </div>
            </div>

             <!-- Retired -->
             <div class="stat-card group relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="absolute right-0 top-0 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-ban text-6xl text-slate-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Retired</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-slate-600">{{ $totalAssetsRetired }}</h3>
                </div>
            </div>
            
        </div>
    </div>

    <!-- ASSETS CHARTS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Assets by Dept -->
        <div class="premium-card p-6">
            <h3 class="font-display font-bold text-lg text-slate-700 mb-6">Assets by Department</h3>
            <div class="h-64 relative">
                <canvas id="assetsDeptChart"></canvas>
            </div>
        </div>

        <!-- Assets by Category -->
        <div class="premium-card p-6">
            <h3 class="font-display font-bold text-lg text-slate-700 mb-6">Assets by Category</h3>
            <div class="h-64 relative">
                <canvas id="assetsCatChart"></canvas>
            </div>
        </div>

    </div>

    <!-- RECENT TICKETS -->
    <div class="premium-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-white flex justify-between items-center">
            <h3 class="font-display font-bold text-lg text-premium">Recent Support Tickets</h3>
            <a href="{{ route('emp.tickets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-xs font-semibold text-slate-500 uppercase tracking-wider">Ref</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-xs font-semibold text-slate-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-xs font-semibold text-slate-500 uppercase tracking-wider">Added By</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTickets as $ticket)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-700">#{{ $ticket->ticket_ref ?? $ticket->ticket_id }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $ticket->ticket_subject }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                        {{ substr($ticket->added_employee, 0, 1) }}
                                    </div>
                                    {{ $ticket->added_employee }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                @if($ticket->status_id == 1)
                                    <span class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100/50 border border-red-200 rounded-full">Open</span>
                                @elseif($ticket->status_id == 2)
                                    <span class="px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100/50 border border-blue-200 rounded-full">In Progress</span>
                                @elseif($ticket->status_id == 3)
                                    <span class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100/50 border border-green-200 rounded-full">Resolved</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200 rounded-full">Unknown</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-20"></i>
                                <p>No recent tickets found.</p>
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

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 20 }
                }
            },
            cutout: '65%'
        };

        // 1. Tickets by Dept
        new Chart(document.getElementById('ticketsDeptChart'), {
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

        // 2. Tickets by Priority
        new Chart(document.getElementById('ticketsPrioChart'), {
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

         // 3. Assets by Dept
         new Chart(document.getElementById('assetsDeptChart'), {
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

         // 4. Assets by Category
         new Chart(document.getElementById('assetsCatChart'), {
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

    </script>

@endsection
