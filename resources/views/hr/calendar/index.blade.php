@extends('layouts.app')

@section('title', 'Team Calendar')
@section('subtitle', 'Schedule, events, and task deadlines')

@section('content')
    <div
        class="calendar-layout flex flex-col h-full bg-white rounded-[32px] border border-slate-200/60 shadow-lg overflow-hidden">

        <!-- Header Controls -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white z-10 relative">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-display font-bold text-slate-800">{{ $date->format('F Y') }}</h2>
                <div class="flex bg-slate-100 rounded-xl p-1">
                    <a href="{{ route('hr.calendar.index', ['date' => $prevDate->format('Y-m-d')]) }}"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white hover:text-indigo-600 hover:shadow-sm transition-all text-slate-500">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    <a href="{{ route('hr.calendar.index', ['date' => $nextDate->format('Y-m-d')]) }}"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white hover:text-indigo-600 hover:shadow-sm transition-all text-slate-500">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
                <a href="{{ route('hr.calendar.index') }}"
                    class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider hover:bg-indigo-100 transition-colors">
                    Today
                </a>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex bg-slate-100 rounded-xl p-1 font-bold text-xs uppercase tracking-wider">
                    <a href="#" class="px-4 py-2 rounded-lg bg-white shadow-sm text-indigo-600 transition-all">Month</a>
                    <a href="#"
                        class="px-4 py-2 rounded-lg text-slate-500 hover:text-slate-700 transition-all opacity-50 cursor-not-allowed"
                        title="Coming Soon">Week</a>
                    <a href="#"
                        class="px-4 py-2 rounded-lg text-slate-500 hover:text-slate-700 transition-all opacity-50 cursor-not-allowed"
                        title="Coming Soon">Day</a>
                </div>
                <button
                    class="premium-button from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl text-sm shadow-md">
                    <i class="fa-solid fa-plus mr-2"></i> Add Event
                </button>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="flex-1 overflow-y-auto bg-slate-50">
            <div class="grid grid-cols-7 h-full min-h-[600px] border-l border-slate-100">
                <!-- Headers -->
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div
                        class="p-3 bg-white border-b border-r border-slate-100 text-center font-bold text-slate-400 uppercase text-xs tracking-widest sticky top-0 z-10">
                        {{ $day }}
                    </div>
                @endforeach

                <!-- Days -->
                @php
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                    $daysInMonth = $startOfMonth->daysInMonth;
                    $daysInPrevMonth = $prevDate->daysInMonth;
                    $totalSlots = ceil(($startDayOfWeek + $daysInMonth) / 7) * 7;
                    $dayCount = 1;
                    $nextMonthDay = 1;
                @endphp

                @for ($i = 0; $i < $totalSlots; $i++)
                    @if ($i < $startDayOfWeek)
                        <!-- Previous Month Day -->
                        <div class="bg-slate-50/50 border-b border-r border-slate-100 p-2 min-h-[120px] opacity-40">
                            <span
                                class="text-sm font-bold text-slate-400">{{ $daysInPrevMonth - ($startDayOfWeek - $i - 1) }}</span>
                        </div>
                    @elseif ($dayCount <= $daysInMonth)
                        <!-- Current Month Day -->
                        @php
                            $currentDateStr = $date->format('Y-m') . '-' . str_pad($dayCount, 2, '0', STR_PAD_LEFT);
                            $isToday = $currentDateStr == date('Y-m-d');
                        @endphp
                        <div class="bg-white border-b border-r border-slate-100 p-2 min-h-[120px] hover:bg-indigo-50/30 transition-colors group relative"
                            data-date="{{ $currentDateStr }}">
                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="w-7 h-7 flex items-center justify-center rounded-full text-sm font-bold {{ $isToday ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-700' }}">
                                    {{ $dayCount }}
                                </span>
                                <button
                                    class="w-6 h-6 rounded-full hover:bg-slate-100 text-slate-300 hover:text-indigo-600 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all"
                                    title="Add Event">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>
                            <div class="space-y-1 events-container" id="events-{{ $currentDateStr }}">
                                <!-- Events injected via JS -->
                            </div>
                        </div>
                        @php $dayCount++; @endphp
                    @else
                        <!-- Next Month Day -->
                        <div class="bg-slate-50/50 border-b border-r border-slate-100 p-2 min-h-[120px] opacity-40">
                            <span class="text-sm font-bold text-slate-400">{{ $nextMonthDay++ }}</span>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
    </div>

    <!-- Event Preview Popover (Hidden by default) -->
    <div id="event-popover"
        class="fixed z-50 hidden bg-white rounded-xl shadow-2xl border border-slate-100 w-72 p-4 animate-scale-in">
        <div class="flex justify-between items-start mb-3">
            <h4 id="popover-title" class="font-bold text-slate-800 leading-tight">Event Title</h4>
            <button onclick="closePopover()" class="text-slate-400 hover:text-slate-600"><i
                    class="fa-solid fa-times"></i></button>
        </div>
        <div class="space-y-3">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <i class="fa-regular fa-clock"></i>
                <span id="popover-time">All Day</span>
            </div>
            <p id="popover-desc" class="text-sm text-slate-600 leading-relaxed hidden">No description.</p>
            <div class="pt-2 border-t border-slate-50 flex justify-end">
                <a id="popover-link" href="#"
                    class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider flex items-center">
                    View Details <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetchEvents();
        });

        async function fetchEvents() {
            const start = '{{ $startOfMonth->subDays($startDayOfWeek)->format("Y-m-d") }}';
            const end = '{{ $endOfMonth->addDays(14)->format("Y-m-d") }}'; // Buffer

            try {
                const response = await fetch(`{{ route('hr.calendar.events') }}?start=${start}&end=${end}`);
                const events = await response.json();

                events.forEach(event => {
                    const container = document.getElementById(`events-${event.start}`);
                    if (container) {
                        const el = document.createElement('div');
                        el.className = 'px-2 py-1 rounded-md text-[10px] font-bold text-white cursor-pointer hover:brightness-110 transition-all shadow-sm truncate';
                        el.style.backgroundColor = event.color;
                        el.innerText = event.title;
                        el.onclick = (e) => showPopover(e, event);
                        container.appendChild(el);
                    }
                });
            } catch (error) {
                console.error('Error fetching events:', error);
            }
        }

        function showPopover(e, event) {
            e.stopPropagation();
            const popover = document.getElementById('event-popover');
            const title = document.getElementById('popover-title');
            const link = document.getElementById('popover-link');

            title.innerText = event.title;
            link.href = event.url || '#';

            // Position
            const rect = e.target.getBoundingClientRect();
            popover.style.left = `${rect.right + 10}px`;
            popover.style.top = `${rect.top}px`;

            // Adjust if off screen
            if (rect.right + 300 > window.innerWidth) {
                popover.style.left = `${rect.left - 300}px`;
            }

            popover.classList.remove('hidden');
        }

        function closePopover() {
            document.getElementById('event-popover').classList.add('hidden');
        }

        // Close popover on outside click
        document.addEventListener('click', (e) => {
            const popover = document.getElementById('event-popover');
            if (!popover.classList.contains('hidden') && !popover.contains(e.target)) {
                closePopover();
            }
        });
    </script>
@endsection