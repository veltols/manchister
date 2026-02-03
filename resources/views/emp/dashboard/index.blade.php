@extends('layouts.app')

@section('title', 'My Dashboard')
@section('subtitle', 'Welcome back to Manchester Portal')

@section('content')
    <div class="space-y-6">

        <!-- Welcome Header -->
        <div class="premium-card p-6 bg-white overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-dark/5 rounded-full -mr-16 -mt-16"></div>
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 relative z-10">
                <div>
                    <h2 class="text-3xl font-display font-bold text-premium">Hello, <span
                            class="text-brand-dark">{{ $employeeName }}</span></h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2">
                        <i class="fa-regular fa-calendar-check text-brand-dark"></i>
                        {{ now()->format('l, jS F Y') }}
                    </p>
                </div>
                <div class="flex bg-slate-100 p-1.5 rounded-2xl shadow-inner border border-slate-200/50">
                    <a href="?mode=today"
                        class="px-5 py-2 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $mode == 'today' ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                        Today
                    </a>
                    <a href="?mode=this_week"
                        class="px-5 py-2 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $mode == 'this_week' ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                        This Week
                    </a>
                    <a href="?mode=this_month"
                        class="px-5 py-2 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $mode == 'this_month' ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                        This Month
                    </a>
                </div>
            </div>
        </div>

        @if($announcements->count() > 0)
            <!-- Announcements Carousel -->
            <div class="premium-card p-6 border-l-4 border-brand-dark relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                    <i class="fa-solid fa-bullhorn text-9xl text-brand-dark"></i>
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-brand-dark/10 flex items-center justify-center text-brand-dark">
                        <i class="fa-solid fa-bullhorn rotate-[-15deg]"></i>
                    </div>
                    <h3 class="text-lg font-display font-bold text-premium">Recent Announcements</h3>
                </div>

                <div class="min-h-[140px] relative">
                    @foreach($announcements as $index => $ann)
                        <div class="announcement-slide transition-all duration-500 transform {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10 absolute inset-0 pointer-events-none' }}"
                            id="ann-{{ $index }}">
                            <h4 class="text-2xl font-bold text-brand-dark mb-2">{{ $ann->document_title }}</h4>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand-dark"></span>
                                Posted on {{ \Carbon\Carbon::parse($ann->added_date)->format('M d, Y') }}
                            </p>
                            <p class="text-slate-600 leading-relaxed text-lg">{{ Str::limit($ann->document_description, 200) }}</p>
                        </div>
                    @endforeach
                </div>

                @if($announcements->count() > 1)
                    <div class="flex items-center gap-3 mt-8">
                        <button onclick="prevAnn()"
                            class="w-10 h-10 rounded-full border border-slate-200 hover:border-brand-dark hover:text-brand-dark bg-white shadow-sm flex items-center justify-center transition-all">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <div class="flex gap-1.5" id="ann-dots">
                            @foreach($announcements as $index => $ann)
                                <div class="w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-brand-dark w-4' : 'bg-slate-200' }}"
                                    id="ann-dot-{{ $index }}"></div>
                            @endforeach
                        </div>
                        <button onclick="nextAnn()"
                            class="w-10 h-10 rounded-full border border-slate-200 hover:border-brand-dark hover:text-brand-dark bg-white shadow-sm flex items-center justify-center transition-all">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Support & Tasks Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- IT Tickets -->
            <div>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h3 class="text-xl font-display font-bold text-premium">Support Center</h3>
                    <a href="{{ route('emp.tickets.index') }}" class="text-sm font-bold text-brand-dark hover:underline">View All <i class="fa-solid fa-arrow-right-long ml-1"></i></a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('emp.tickets.index') }}" class="stat-card group hover:border-brand-dark transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total</p>
                                <h3 class="text-2xl font-bold text-premium count" data-target="{{ $ticketStats->total }}">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-brand-dark group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-ticket text-sm"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('emp.tickets.index', ['stt' => 4]) }}" class="stat-card group hover:border-amber-500 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Unassigned</p>
                                <h3 class="text-2xl font-bold text-amber-600 count" data-target="{{ $ticketStats->unassigned }}">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-user-slash text-sm"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('emp.tickets.index', ['stt' => 2]) }}" class="stat-card group hover:border-brand-dark transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">In Progress</p>
                                <h3 class="text-2xl font-bold text-brand-dark count" data-target="{{ $ticketStats->progress }}">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-brand-dark/5 flex items-center justify-center text-brand-dark group-hover:bg-brand-dark group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-spinner fa-spin-pulse text-sm"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('emp.tickets.index', ['stt' => 3]) }}" class="stat-card group hover:border-green-600 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Resolved</p>
                                <h3 class="text-2xl font-bold text-green-600 count" data-target="{{ $ticketStats->resolved }}">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-check-double text-sm"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Task Summary -->
            <div>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h3 class="text-xl font-display font-bold text-premium">My Tasks</h3>
                    <a href="{{ route('emp.tasks.index') }}" class="text-sm font-bold text-indigo-600 hover:underline">View All Tasks <i class="fa-solid fa-arrow-right-long ml-1"></i></a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('emp.tasks.index') }}" class="stat-card group hover:border-indigo-600 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tasks</p>
                                <h3 class="text-2xl font-bold text-premium count" data-target="{{ $taskStats['total'] }}">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-tasks text-sm"></i>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('emp.tasks.index', ['status_id' => 1]) }}" class="stat-card group hover:border-amber-500 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">To Do</p>
                                <h3 class="text-2xl font-bold text-amber-600 count" data-target="{{ $taskStats['todo'] }}">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-list-ul text-sm"></i>
                            </div>
                        </div>
                    </a>
                    @if($taskStats['overdue'] > 0)
                        <a href="{{ route('emp.tasks.index') }}" class="stat-card group border-rose-100 hover:border-rose-500 transition-all duration-300 bg-rose-50/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mb-1">Overdue</p>
                                    <h3 class="text-2xl font-bold text-rose-600 count" data-target="{{ $taskStats['overdue'] }}">0</h3>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-clock text-sm"></i>
                                </div>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('emp.tasks.index', ['status_id' => 2]) }}" class="stat-card group hover:border-brand-dark transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">In Progress</p>
                                    <h3 class="text-2xl font-bold text-brand-dark count" data-target="{{ $taskStats['progress'] }}">0</h3>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-brand-dark/5 flex items-center justify-center text-brand-dark group-hover:bg-brand-dark group-hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-spinner fa-spin-pulse text-sm"></i>
                                </div>
                            </div>
                        </a>
                    @endif
                    <a href="{{ route('emp.tasks.index', ['status_id' => 3]) }}" class="stat-card group hover:border-green-600 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Done</p>
                                <h3 class="text-2xl font-bold text-green-600 count" data-target="{{ $taskStats['done'] }}">0</h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-check-circle text-sm"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent Tasks & Assets -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Tasks -->
                <div class="premium-card overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-display font-bold text-premium flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-indigo-600"></i>
                            Recent Tasks
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="premium-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left font-bold text-slate-400">Task</th>
                                    <th class="text-center font-bold text-slate-400">Status</th>
                                    <th class="text-left font-bold text-slate-400">Due</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($recentTasks as $task)
                                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location='{{ route('emp.tasks.show', $task->task_id) }}'">
                                        <td>
                                            <div class="font-bold text-slate-700 truncate max-w-[200px]">{{ $task->task_title }}</div>
                                            <div class="text-[10px] text-slate-400 uppercase">{{ $task->priority->priority_name ?? 'Normal' }} Priority</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-black text-white uppercase tracking-wider" style="background: #{{ $task->status->status_color ?? '999' }}">
                                                {{ $task->status->status_name ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-medium text-slate-600">{{ $task->task_due_date ? $task->task_due_date->format('M d') : '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-10 text-slate-400 font-medium">No tasks found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Assigned Assets -->
                <div class="premium-card overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-display font-bold text-premium flex items-center gap-2">
                            <i class="fa-solid fa-laptop-code text-brand-dark"></i>
                            Assigned Assets
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="premium-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left font-bold text-slate-400">REF</th>
                                    <th class="text-left font-bold text-slate-400">Name</th>
                                    <th class="text-left font-bold text-slate-400">Assigned By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($assets as $asset)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td><span class="font-mono text-xs font-bold bg-slate-100 text-slate-600 px-2 py-1 rounded">{{ $asset->asset_ref }}</span></td>
                                        <td>
                                            <div class="font-bold text-slate-700">{{ $asset->asset_name }}</div>
                                            <div class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $asset->asset_sku }}</div>
                                        </td>
                                        <td>
                                            <span class="text-sm font-medium text-slate-600">{{ $asset->assignedBy ? $asset->assignedBy->first_name : 'System' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-10">
                                            <i class="fa-solid fa-box-open text-3xl text-slate-200 mb-2"></i>
                                            <p class="text-slate-400 font-medium">No assets assigned yet</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- HR / Leaves Mini -->
            <div class="premium-card flex flex-col h-fit">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-display font-bold text-premium flex items-center gap-2">
                        <i class="fa-solid fa-umbrella-beach text-brand-dark"></i>
                        HR Summary
                    </h3>
                </div>
                <div class="p-6 space-y-4">

                    <a href="{{ route('emp.leaves.index') }}"
                        class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-brand-dark transition-all group">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-brand-dark/10 flex items-center justify-center text-brand-dark group-hover:bg-brand-dark group-hover:text-white transition-colors">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </div>
                            <span class="font-bold text-slate-700">Total Requests</span>
                        </div>
                        <span class="text-2xl font-black text-brand-dark count"
                            data-target="{{ $hrStats['requests'] }}">0</span>
                    </a>

                    <a href="{{ route('emp.leaves.index', ['status' => 2]) }}"
                        class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-amber-50 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                            </div>
                            <span class="font-bold text-slate-700">Pending Approval</span>
                        </div>
                        <span class="text-2xl font-black text-amber-600 count"
                            data-target="{{ $hrStats['pending_approval'] }}">0</span>
                    </a>

                    <div class="pt-4">
                        <a href="{{ route('emp.leaves.index') }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-brand-dark text-white rounded-2xl font-bold shadow-lg shadow-brand-dark/20 hover:shadow-xl hover:-translate-y-1 transition-all">
                            Request a Leave
                            <i class="fa-solid fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

    </div>

    <script>
        // Counter Animation
        document.querySelectorAll('.count').forEach(el => {
            const target = parseInt(el.getAttribute('data-target'));
            if (isNaN(target)) return;

            let count = 0;
            const inc = Math.max(1, target / 30);
            if (target >= 0) {
                const timer = setInterval(() => {
                    count += inc;
                    if (count >= target) {
                        el.innerText = target;
                        clearInterval(timer);
                    } else {
                        el.innerText = Math.ceil(count);
                    }
                }, 30);
            }
        });

        // Carousel Logic
        let curAnn = 0;
        const slides = document.querySelectorAll('.announcement-slide');
        const dots = document.querySelectorAll('#ann-dots > div');
        const totalSlides = slides.length;

        function showAnn(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none', 'absolute', 'inset-0');
                    slide.classList.add('opacity-100', 'translate-y-0');
                } else {
                    slide.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none', 'absolute', 'inset-0');
                    slide.classList.remove('opacity-100', 'translate-y-0');
                }
            });

            // Update dots
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('bg-brand-dark', 'w-4');
                    dot.classList.remove('bg-slate-200');
                } else {
                    dot.classList.remove('bg-brand-dark', 'w-4');
                    dot.classList.add('bg-slate-200');
                }
            });
        }

        function nextAnn() {
            curAnn = (curAnn + 1) % totalSlides;
            showAnn(curAnn);
        }

        function prevAnn() {
            curAnn = (curAnn - 1 + totalSlides) % totalSlides;
            showAnn(curAnn);
        }
    </script>
@endsection
