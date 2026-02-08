@extends('layouts.app')

@section('title', 'HR Dashboard')
@section('subtitle', 'Overview of your workforce')

@section('content')
<div class="space-y-6">
    
    <!-- KPI Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="stat-card group">
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Employees</p>
                    <h3 class="text-3xl font-bold text-premium count" data-target="{{ $totalEmps }}">0</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm relative z-10">
                <span class="text-green-600 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-up text-xs"></i> 12%
                </span>
                <span class="text-slate-500">vs last month</span>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Average Age</p>
                    <h3 class="text-3xl font-bold text-premium">
                        <span class="count" data-target="{{ $averageAge }}">0</span>
                        <span class="text-lg">yrs</span>
                    </h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-cake-candles text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm relative z-10">
                <span class="text-slate-500">Workforce maturity</span>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Diversity</p>
                    <h3 class="text-3xl font-bold text-premium">{{ $diversityStat }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-venus-mars text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm relative z-10">
                <span class="text-slate-500">Gender balance</span>
            </div>
        </div>
        
        <div class="stat-card group">
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Departments</p>
                    <h3 class="text-3xl font-bold text-premium">{{ count($deptDataLabels) }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-building text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm relative z-10">
                <span class="text-slate-500">Active divisions</span>
            </div>
        </div>

    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Employees by Department -->
        <div class="premium-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-display font-bold text-premium">Employees by Department</h3>
                <div class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-semibold">
                    {{ array_sum($deptDataCounts) }} Total
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="empByDept"></canvas>
            </div>
        </div>

        <!-- Employees by Gender -->
        <div class="premium-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-display font-bold text-premium">Gender Distribution</h3>
                <div class="px-3 py-1 rounded-lg bg-pink-50 text-pink-600 text-xs font-semibold">
                    Diversity Metrics
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="empByGender"></canvas>
            </div>
        </div>

    </div>

    <!-- Certifications & Additional Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Certifications Chart -->
        <div class="premium-card p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-display font-bold text-premium">Employees by Certification</h3>
                <div class="px-3 py-1 rounded-lg bg-cyan-50 text-cyan-600 text-xs font-semibold">
                    Skills Overview
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="empByCert"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="premium-card p-6">
            <h3 class="text-lg font-display font-bold text-premium mb-4">Quick Actions</h3>
            <div class="space-y-3">
                

                <a href="{{ route('hr.leaves.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-cyan-600 to-blue-700 hover:shadow-lg hover:shadow-cyan-200 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-calendar-check text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-white text-sm">Manage Leaves</p>
                        <p class="text-xs text-white/70">Review requests</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-white/50 group-hover:text-white transition-colors"></i>
                </a>

                <a href="{{ route('hr.performance.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 hover:shadow-lg hover:shadow-amber-200 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-star text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-white text-sm">Performance</p>
                        <p class="text-xs text-white/70">View reviews</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-white/50 group-hover:text-white transition-colors"></i>
                </a>
            </div>
        </div>

    </div>

</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Counter Animation
    document.querySelectorAll('.count').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));
        if(isNaN(target)) return;
        
        let count = 0;
        const inc = target / 50; 
        if(target > 0) {
            const timer = setInterval(() => {
                count += inc;
                if(count >= target) {
                    el.innerText = target;
                    clearInterval(timer);
                } else {
                    el.innerText = Math.ceil(count);
                }
            }, 20);
        }
    });

    // Chart.js Global Config
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Department Chart (Doughnut)
    const ctxDept = document.getElementById('empByDept');
    new Chart(ctxDept, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deptDataLabels) !!},
            datasets: [{
                data: {!! json_encode($deptDataCounts) !!},
                backgroundColor: [
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(6, 182, 212, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12, weight: '500' },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 }
                }
            }
        }
    });

    // 2. Gender Chart (Pie)
    const ctxGender = document.getElementById('empByGender');
    new Chart(ctxGender, {
        type: 'pie',
        data: {
            labels: {!! json_encode($genderDataLabels) !!},
            datasets: [{
                data: {!! json_encode($genderDataCounts) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12, weight: '500' },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 }
                }
            }
        }
    });

    // 3. Certifications Chart (Bar)
    const ctxCert = document.getElementById('empByCert');
    new Chart(ctxCert, {
        type: 'bar',
        data: {
            labels: {!! json_encode($certDataLabels) !!},
            datasets: [{
                label: 'Employees',
                data: {!! json_encode($certDataCounts) !!},
                backgroundColor: 'rgba(6, 182, 212, 0.8)',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: { size: 11 },
                        padding: 8
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 11 },
                        padding: 8
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 }
                }
            }
        }
    });

</script>
@endsection
