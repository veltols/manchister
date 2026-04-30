@extends('layouts.app')

@section('title', 'My Dashboard')
@section('subtitle', 'Welcome back to IQC Sense Portal')

@push('styles')
<style>
    /* ── Announcement Slider Banner ── */
    .admin-banner {
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(0,79,104,0.3);
        min-height: 220px;
        background: #004F68;
    }
    .banner-slide {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        padding: 0 5rem;
        background: linear-gradient(135deg, #004F68 0%, #006a8a 45%, #1a8aaa 80%, #0ea5e9 100%);
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media (max-width: 640px) {
        .banner-slide { padding: 0 2rem; }
    }
    .banner-slide::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .banner-slide.active { opacity: 1; transform: translateX(0); z-index: 10; }
    .banner-slide.inactive-left { opacity: 0; transform: translateX(-100%); z-index: 0; }
    .banner-slide.inactive-right { opacity: 0; transform: translateX(100%); z-index: 0; }

    .slider-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .slider-dot.active {
        width: 24px;
        border-radius: 10px;
        background: white;
    }
    .slider-nav {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 20;
    }
    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 20;
    }
    .slider-arrow:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-50%) scale(1.1);
    }
    .slider-arrow.left { left: 20px; }
    .slider-arrow.right { right: 20px; }

    .news-badge {
        background: white;
        padding: 4px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
        width: fit-content;
    }
    .news-badge span {
        font-size: 10px;
        font-weight: 800;
        color: #004F68;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .news-badge i {
        color: #0ea5e9;
        font-size: 10px;
    }
    .slide-image {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 45%;
        object-fit: cover;
        mask-image: linear-gradient(to left, black 60%, transparent 100%);
        -webkit-mask-image: linear-gradient(to left, black 60%, transparent 100%);
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50%       { transform: translateY(-15px) rotate(2deg); }
    }
    .animate-float { animation: float 8s ease-in-out infinite; }

    @media (max-width: 768px) {
        .welcome-banner { border-radius: 20px; }
        .welcome-banner h1 { font-size: 2rem; }
    }

    /* ── 3D Stat Icon ── */
    .stat-icon-3d {
        width: 52px; height: 52px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        position: relative; overflow: hidden; flex-shrink: 0;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
    }
    .stat-icon-3d::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        border-radius: 15px 15px 0 0;
        pointer-events: none;
    }
    .stat-card:hover .stat-icon-3d {
        transform: scale(1.14) rotate(-6deg) translateY(-3px);
    }

    /* ── Upgraded stat-card ── */
    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        border: 1.5px solid rgba(0,79,104,0.12);
        transition: all 0.28s cubic-bezier(0.34,1.2,0.64,1);
        display: block; text-decoration: none;
        box-shadow: 0 2px 8px rgba(0,79,104,0.06);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,79,104,0.12);
    }

    /* ── Tab navigation — Icon Box Cards ── */
    .dash-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 90px;
        padding: 0.85rem 0.5rem;
        border-radius: 18px;
        font-weight: 700;
        font-size: 0.65rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        transition: all 0.3s cubic-bezier(0.34, 1.3, 0.64, 1);
        cursor: pointer;
        border: 1.5px solid transparent;
        background: white;
        position: relative;
        overflow: hidden;
    }
    .dash-tab-btn:hover {
        transform: translateY(-4px) scale(1.04);
        box-shadow: 0 8px 24px rgba(0,79,104,0.15);
    }
    .dash-tab-btn .tab-icon-box {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        position: relative; overflow: hidden;
    }
    /* Gloss on icon box */
    .dash-tab-btn .tab-icon-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        border-radius: 14px 14px 0 0;
        pointer-events: none;
    }
    .dash-tab-btn:hover .tab-icon-box {
        transform: scale(1.12) rotate(-5deg);
    }
    .dash-tab-btn.active-tab {
        border-color: rgba(0,79,104,0.2);
        box-shadow: 0 8px 28px rgba(0,79,104,0.18), inset 0 1px 0 rgba(255,255,255,0.9);
        transform: translateY(-2px);
    }
    .dash-tab-btn:not(.active-tab) {
        color: #006a8a;
        box-shadow: 0 2px 8px rgba(0,79,104,0.06);
        border-color: rgba(0,79,104,0.08);
    }

    /* ── Panel headers ── */
    .panel-header-gradient {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 60%, #1a8aaa 100%);
        padding: 1.1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Announcement card ── */
    .ann-card {
        background: linear-gradient(135deg, rgba(0,79,104,0.04) 0%, rgba(0,106,138,0.02) 100%);
        border-radius: 20px;
        border: 1.5px solid rgba(0,79,104,0.1);
        padding: 1.5rem;
        position: relative; overflow: hidden;
    }
    .ann-card::before {
        content: '';
        position: absolute;
        right: -20px; top: -20px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,79,104,0.06) 0%, transparent 70%);
    }

    /* ── HR stat rows ── */
    .hr-stat-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem;
        border-radius: 16px;
        background: white;
        border: 1.5px solid rgba(0,79,104,0.1);
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .hr-stat-row:hover {
        border-color: #004F68;
        box-shadow: 0 4px 16px rgba(0,79,104,0.1);
        transform: translateX(4px);
    }
	.dash-tab-btn {
    min-height: 160px;
	    width: auto;
}
</style>
@endpush

@section('content')
<div class="space-y-6">
        {{-- ═══════════════════════════════════
         DATE FILTER BAR
    ═══════════════════════════════════ --}}
    <div class="premium-card p-5 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-28 h-28 rounded-full -mr-14 -mt-14"
             style="background:radial-gradient(circle, rgba(0,79,104,0.05) 0%, transparent 70%);"></div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 relative z-10">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Hello, <span class="text-brand-dark">{{ $employeeName }}</span></h2>
                <p class="text-teal-700 mt-1 flex items-center gap-2 text-sm">
                    <i class="fa-regular fa-calendar-check text-brand-dark"></i>
                    {{ now()->format('l, jS F Y') }}
                </p>
            </div>
 

        </div>
    </div>
    
    {{-- ═══════════════════════════════════
         WELCOME BANNER
    ═══════════════════════════════════ --}}
    <div class="relative mb-8" style="padding-top: 2.5rem;">
	
        {{-- Floating character --}}
        <!--div class="absolute -right-4 md:right-8 bottom-0 w-36 md:w-60 lg:w-72 pointer-events-none drop-shadow-[0_20px_50px_rgba(0,0,0,0.18)] animate-float overflow-visible" style="top: 0;z-index:1000; bottom: auto; display:flex; align-items:flex-end; height:100%;">
            <img src="{{ asset('images/char.png') }}" alt="Staff character"  class="w-full h-auto object-contain" style="width:70%">
        </div !-->
        <div class="admin-banner" x-data="{ 
            active: 0, 
            total: {{ $announcements->count() }},
            next() { this.active = (this.active + 1) % this.total },
            prev() { this.active = (this.active - 1 + this.total) % this.total },
            init() { if(this.total > 1) setInterval(() => this.next(), 6000) }
        }">
            @if($announcements->count() > 0)
                @foreach($announcements as $index => $ann)
                    <div class="banner-slide" 
                         :class="active === {{ $index }} ? 'active' : (active > {{ $index }} ? 'inactive-left' : 'inactive-right')"
                         x-show="active === {{ $index }}"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 transform translate-x-full"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-700"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-full">
                        
                        @if($ann->document_attachment && (Str::endsWith($ann->document_attachment, ['.jpg', '.jpeg', '.png', '.webp', '.gif'])))
                            <img src="{{ asset('uploads/' . $ann->document_attachment) }}" class="slide-image" alt="">
                        @endif

                        <div class="relative z-10 max-w-2xl">
                            <div class="news-badge">
                                <i class="fa-solid fa-circle"></i>
                                <span>IQC News</span>
                            </div>
                            <h1 class="text-2xl md:text-3xl font-display font-bold text-white tracking-tight drop-shadow-lg mb-2">
                                {{ $ann->document_title }}
                            </h1>
                            <p class="text-sky-50 text-sm md:text-lg font-medium leading-relaxed drop-shadow-md">
                                {{ Str::limit($ann->document_description, 150) }}
                            </p>
                        </div>
                    </div>
                @endforeach
                
                {{-- Navigation Arrows --}}
                @if($announcements->count() > 1)
                    <button @click="prev()" class="slider-arrow left">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button @click="next()" class="slider-arrow right">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>

                    {{-- Dots indicator --}}
                    <div class="slider-nav">
                        @foreach($announcements as $index => $ann)
                            <div @click="active = {{ $index }}" 
                                 class="slider-dot" 
                                 :class="active === {{ $index }} ? 'active' : ''"></div>
                        @endforeach
                    </div>
                @endif
            @else
                {{-- Default Welcome Slide --}}
                <div class="banner-slide active">
                    <div class="relative z-10 max-w-2xl">
                        <div class="news-badge">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Enterprise Portal</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-display font-bold text-white tracking-tight drop-shadow-lg mb-2">
                            Hello, {{ $employeeName }} 👋
                        </h1>
                        <p class="text-sky-50 text-sm md:text-lg font-medium leading-relaxed drop-shadow-md">
                            Ready to start your day? Your dashboard is all set.
                        </p>
                    </div>
                </div>
            @endif
        </div>


    </div>

<div class="space-y-6">
        {{-- ═══════════════════════════════════
        Quick access tab
    ═══════════════════════════════════ --}}
    <div class="premium-card p-5 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-28 h-28 rounded-full -mr-14 -mt-14"
             style="background:radial-gradient(circle, rgba(0,79,104,0.05) 0%, transparent 70%);"></div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 relative z-10">
			   <div>
                <h2 class="text-2xl font-display font-bold text-premium">Quick View</h2>
                <p class="text-teal-700 mt-1 flex items-center gap-2 text-sm">
                    <i class="fa-regular fa-calendar-check text-brand-dark"></i>
                    Filter by Period
                </p>
            </div>
            <div class="flex items-center gap-3">

                {{-- Today --}}
                <a href="?mode=today"
                   class="relative overflow-hidden flex flex-col items-center gap-1.5 py-3 rounded-2xl transition-all duration-300 group hover:-translate-y-1 text-center"
                   style="width:6.5rem; {{ $mode == 'today'
                        ? 'background:linear-gradient(135deg,#004F68,#006a8a); box-shadow:0 6px 20px rgba(0,79,104,0.3); border:1.5px solid rgba(255,255,255,0.1);'
                        : 'background:linear-gradient(135deg,#e0f2fe,#bae6fd); border:1.5px solid rgba(0,79,104,0.12); box-shadow:0 3px 10px rgba(0,79,104,0.08);' }}">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400"
                         style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.2) 50%,transparent 70%);"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                         style="{{ $mode == 'today'
                            ? 'background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); box-shadow:inset 0 1px 0 rgba(255,255,255,0.3);'
                            : 'background:linear-gradient(145deg,#0ea5e9,#0284c7); box-shadow:0 4px 12px rgba(14,165,233,0.35),inset 0 1px 0 rgba(255,255,255,0.3);' }}">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-sun text-white text-sm relative z-10"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $mode == 'today' ? 'text-white' : 'text-teal-700' }}">Today</span>
                </a>

                {{-- This Week --}}
                <a href="?mode=this_week"
                   class="relative overflow-hidden flex flex-col items-center gap-1.5 py-3 rounded-2xl transition-all duration-300 group hover:-translate-y-1 text-center"
                   style="width:6.5rem; {{ $mode == 'this_week'
                        ? 'background:linear-gradient(135deg,#004F68,#006a8a); box-shadow:0 6px 20px rgba(0,79,104,0.3); border:1.5px solid rgba(255,255,255,0.1);'
                        : 'background:linear-gradient(135deg,#f5f3ff,#ede9fe); border:1.5px solid rgba(139,92,246,0.15); box-shadow:0 3px 10px rgba(139,92,246,0.08);' }}">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400"
                         style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.2) 50%,transparent 70%);"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                         style="{{ $mode == 'this_week'
                            ? 'background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); box-shadow:inset 0 1px 0 rgba(255,255,255,0.3);'
                            : 'background:linear-gradient(145deg,#8b5cf6,#7c3aed); box-shadow:0 4px 12px rgba(139,92,246,0.35),inset 0 1px 0 rgba(255,255,255,0.3);' }}">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-calendar-week text-white text-sm relative z-10"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $mode == 'this_week' ? 'text-white' : 'text-violet-700' }}">This Week</span>
                </a>

                {{-- This Month --}}
                <a href="?mode=this_month"
                   class="relative overflow-hidden flex flex-col items-center gap-1.5 py-3 rounded-2xl transition-all duration-300 group hover:-translate-y-1 text-center"
                   style="width:6.5rem; {{ $mode == 'this_month'
                        ? 'background:linear-gradient(135deg,#004F68,#006a8a); box-shadow:0 6px 20px rgba(0,79,104,0.3); border:1.5px solid rgba(255,255,255,0.1);'
                        : 'background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.15); box-shadow:0 3px 10px rgba(16,185,129,0.08);' }}">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400"
                         style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.2) 50%,transparent 70%);"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                         style="{{ $mode == 'this_month'
                            ? 'background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); box-shadow:inset 0 1px 0 rgba(255,255,255,0.3);'
                            : 'background:linear-gradient(145deg,#10b981,#059669); box-shadow:0 4px 12px rgba(16,185,129,0.35),inset 0 1px 0 rgba(255,255,255,0.3);' }}">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-calendar-days text-white text-sm relative z-10"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $mode == 'this_month' ? 'text-white' : 'text-emerald-700' }}">This Month</span>
                </a>

            </div>


        </div>
    </div>

    {{-- ═══════════════════════════════════
         MAIN LAYOUT: CONTENT + RIGHT SIDEBAR
    ═══════════════════════════════════ --}}
    <div x-data="{ activeTab: 'tickets' }" class="space-y-6">

        {{-- ═══════════════════════════════════
             ANNOUNCEMENTS CAROUSEL
        ═══════════════════════════════════ --}}
        <!-- @if($announcements->count() > 0)
        <div class="ann-card">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                <i class="fa-solid fa-bullhorn text-9xl text-brand-dark"></i>
            </div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background:linear-gradient(135deg,#004F68,#006a8a);
                            box-shadow:0 4px 14px rgba(0,79,104,0.3),inset 0 1px 0 rgba(255,255,255,0.2);">
                    <i class="fa-solid fa-bullhorn rotate-[-15deg] text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-display font-bold text-premium">Recent Announcements</h3>
            </div>

            <div class="min-h-[120px] relative">
                @foreach($announcements as $index => $ann)
                    <div class="announcement-slide transition-all duration-500 transform {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10 absolute inset-0 pointer-events-none' }}"
                         id="ann-{{ $index }}">
                        <h4 class="text-xl font-bold text-brand-dark mb-1">{{ $ann->document_title }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-dark inline-block"></span>
                            Posted on {{ \Carbon\Carbon::parse($ann->added_date)->format('M d, Y') }}
                        </p>
                        <div class="flex items-center justify-between mt-3">
                            <p class="text-slate-600 leading-relaxed flex-1">{{ Str::limit($ann->document_description, 200) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($announcements->count() > 1)
            <div class="flex items-center gap-3 mt-6">
                <button onclick="prevAnn()"
                    class="w-9 h-9 rounded-full border border-slate-200 hover:border-brand-dark hover:text-brand-dark bg-white shadow-sm flex items-center justify-center transition-all text-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="flex gap-1.5" id="ann-dots">
                    @foreach($announcements as $index => $ann)
                        <div class="h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'w-4 bg-brand-dark' : 'w-2 bg-slate-200' }}"
                             id="ann-dot-{{ $index }}"></div>
                    @endforeach
                </div>
                <button onclick="nextAnn()"
                    class="w-9 h-9 rounded-full border border-slate-200 hover:border-brand-dark hover:text-brand-dark bg-white shadow-sm flex items-center justify-center transition-all text-sm">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            @endif
        </div>
        @endif -->

        {{-- ═══════════════════════════════════
             HR LIBRARY (POLICIES & PROCEDURES)
        ═══════════════════════════════════ --}}
        <!--  disabled for now>
        <div x-data="{ libTab: 'policies' }" class="premium-card p-6 bg-white relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                <i class="fa-solid fa-book-open text-9xl text-brand-dark"></i>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:linear-gradient(135deg,#0d9488,#0f766e);
                                box-shadow:0 4px 14px rgba(13,148,136,0.2),inset 0 1px 0 rgba(255,255,255,0.2);">
                        <i class="fa-solid fa-book-bookmark text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-display font-bold text-premium">HR Library</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Policies &amp; Procedures</p>
                    </div>
                </div>
                
                <div class="flex bg-slate-100 p-1 rounded-xl w-fit">
                    <button @click="libTab = 'policies'"
                            :class="libTab === 'policies' ? 'bg-white text-teal-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">
                        Policies
                    </button>
                    <button @click="libTab = 'procedures'"
                            :class="libTab === 'procedures' ? 'bg-white text-teal-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">
                        Procedures
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                {{-- Policies List --}}
                <template x-if="libTab === 'policies'">
                    @forelse($policies as $policy)
                        <div class="group/doc p-3 rounded-xl border border-slate-100 hover:border-teal-200 hover:bg-teal-50/30 transition-all flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover/doc:bg-teal-100 transition-colors">
                                    <i class="fa-solid fa-file-shield text-sm"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-slate-700 truncate" title="{{ $policy->document_title }}">{{ $policy->document_title }}</p>
                                    <p class="text-[9px] text-slate-400 uppercase tracking-tighter">HR Policy</p>
                                </div>
                            </div>
                            <a href="{{ asset('uploads/' . $policy->document_attachment) }}" download
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-100 text-teal-600 shadow-sm flex items-center justify-center hover:bg-teal-600 hover:text-white transition-all"
                                    title="Download Policy">
                                <i class="fa-solid fa-download text-xs"></i>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-xs font-medium text-slate-400">No policies available</p>
                        </div>
                    @endforelse
                </template>

                {{-- Procedures List --}}
                <template x-if="libTab === 'procedures'">
                    @forelse($procedures as $proc)
                        <div class="group/doc p-3 rounded-xl border border-slate-100 hover:border-teal-200 hover:bg-teal-50/30 transition-all flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover/doc:bg-indigo-100 transition-colors">
                                    <i class="fa-solid fa-file-signature text-sm"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-slate-700 truncate" title="{{ $proc->document_title }}">{{ $proc->document_title }}</p>
                                    <p class="text-[9px] text-slate-400 uppercase tracking-tighter">Procedure</p>
                                </div>
                            </div>
                            <a href="{{ asset('uploads/' . $proc->document_attachment) }}" download
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-100 text-indigo-600 shadow-sm flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all"
                                    title="Download Procedure">
                                <i class="fa-solid fa-download text-xs"></i>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-xs font-medium text-slate-400">No procedures available</p>
                        </div>
                    @endforelse
                </template>
            </div>
        </div>
        <!-->
{{-- ── Tab: My Portal ── --}}
<div 
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0">

    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">

        <!-- Header -->
        <div class="panel-header-gradient flex items-center justify-between px-6 py-4">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-chart-pie"></i> My Portal
            </h3>
            <p class="text-white/80 text-sm">Quick Access to your Services</p>
        </div>

        <!-- Body -->
        <div class="p-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- IT Support --}}
                <button @click="activeTab = 'tickets'"
                    class="dash-tab-btn h-full flex flex-col justify-center items-center text-center rounded-2xl p-6 transition-all duration-300"
                    :class="activeTab === 'tickets' ? 'shadow-lg scale-[1.03]' : 'hover:shadow-md'"
                    :style="activeTab === 'tickets' 
                        ? 'background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;' 
                        : 'background:#f8fafc; color:#1d4ed8;'">

                    <div class="w-14 h-14 flex items-center justify-center rounded-xl mb-4"
                        :style="activeTab === 'tickets'
                            ? 'background:rgba(255,255,255,0.2);'
                            : 'background:#e0ecff;'">
                        <i class="fa-solid fa-headset text-xl"></i>
                    </div>

                    <h4 class="font-semibold text-lg">IT Support</h4>

                  
                </button>


                {{-- My Tasks --}}
                <button @click="activeTab = 'tasks'"
                    class="dash-tab-btn h-full flex flex-col justify-center items-center text-center rounded-2xl p-6 transition-all duration-300" 
                    :class="activeTab === 'tasks' ? 'shadow-lg scale-[1.03]' : 'hover:shadow-md'"
                    :style="activeTab === 'tasks' 
                        ? 'background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff;' 
                        : 'background:#f8fafc; color:#7c3aed;'">

                    <div class="w-14 h-14 flex items-center justify-center rounded-xl mb-4"
                        :style="activeTab === 'tasks'
                            ? 'background:rgba(255,255,255,0.2);'
                            : 'background:#ede9fe;'">
                        <i class="fa-solid fa-list-check text-xl"></i>
                    </div>

                    <h4 class="font-semibold text-lg">My Tasks</h4>

                
                </button>


                {{-- Assets --}}
                <button @click="activeTab = 'assets'"
                    class="dash-tab-btn h-full flex flex-col justify-center items-center text-center rounded-2xl p-6 transition-all duration-300"
                    :class="activeTab === 'assets' ? 'shadow-lg scale-[1.03]' : 'hover:shadow-md'"
                    :style="activeTab === 'assets' 
                        ? 'background:linear-gradient(135deg,#f97316,#ea580c); color:#fff;' 
                        : 'background:#f8fafc; color:#c2410c;'">

                    <div class="w-14 h-14 flex items-center justify-center rounded-xl mb-4"
                        :style="activeTab === 'assets'
                            ? 'background:rgba(255,255,255,0.2);'
                            : 'background:#fff7ed;'">
                        <i class="fa-solid fa-laptop-code text-xl"></i>
                    </div>

                    <h4 class="font-semibold text-lg">Assets</h4>

                </button>


                {{-- HR & Leaves --}}
                <button @click="activeTab = 'hr'"
                    class="dash-tab-btn h-full flex flex-col justify-center items-center text-center rounded-2xl p-6 transition-all duration-300"
                    :class="activeTab === 'hr' ? 'shadow-lg scale-[1.03]' : 'hover:shadow-md'"
                    :style="activeTab === 'hr' 
                        ? 'background:linear-gradient(135deg,#22c55e,#16a34a); color:#fff;' 
                        : 'background:#f8fafc; color:#15803d;'">

                    <div class="w-14 h-14 flex items-center justify-center rounded-xl mb-4"
                        :style="activeTab === 'hr'
                            ? 'background:rgba(255,255,255,0.2);'
                            : 'background:#f0fdf4;'">
                        <i class="fa-solid fa-umbrella-beach text-xl"></i>
                    </div>

                    <h4 class="font-semibold text-lg">HR &amp; Leaves</h4>

                
                </button>

            </div>
        </div>
    </div>
</div>
		
		
        {{-- ═══════════════════════════════════
             HORIZONTAL TAB NAV (RIGHT ALIGNED)
        ═══════════════════════════════════ --}}
        <div class="flex flex-wrap items-center justify-end gap-3 w-full">
       



    
        </div>

        {{-- ═══════════════════════════════════
             TAB CONTENT SECTIONS
        ═══════════════════════════════════ --}}
        <div class="space-y-6">

        {{-- ── Tab: IT Support ── --}}
        <div x-show="activeTab === 'tickets'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                <div class="panel-header-gradient">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie"></i> Support Matrix
                    </h3>
                    <a href="{{ route('emp.tickets.index') }}"
                       class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold transition-all hover:-translate-y-0.5 group"
                       style="background:rgba(255,255,255,0.18); color:#fff; border:1px solid rgba(255,255,255,0.3);">
                        <span>View Portal</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-[10px]"></i>
                    </a>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        {{-- Total Tickets --}}
                        <a href="{{ route('emp.tickets.index') }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#eff6ff,#dbeafe);
                                  border:1.5px solid rgba(37,99,235,0.15);
                                  box-shadow:0 4px 16px rgba(37,99,235,0.1);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            {{-- 3D Icon Box --}}
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#3b82f6,#2563eb);
                                        box-shadow:0 8px 22px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-ticket text-white text-xl relative z-10"></i>
                            </div>
                            {{-- Big Number --}}
                            <h3 class="text-4xl font-black leading-none count" style="color:#2563eb;" data-target="{{ $ticketStats->total }}">0</h3>
                            {{-- Label --}}
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#3b82f6;">Total Tickets</p>
                        </a>

                        {{-- Unassigned --}}
                        <a href="{{ route('emp.tickets.index', ['stt' => 4]) }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#fffbeb,#fef3c7);
                                  border:1.5px solid rgba(245,158,11,0.2);
                                  box-shadow:0 4px 16px rgba(245,158,11,0.12);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                        box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-user-slash text-white text-xl relative z-10"></i>
                            </div>
                            <h3 class="text-4xl font-black leading-none count" style="color:#d97706;" data-target="{{ $ticketStats->cancelled }}">0</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">Cancelled</p>
                        </a>

                        {{-- In Progress --}}
                        <a href="{{ route('emp.tickets.index', ['stt' => 2]) }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);
                                  border:1.5px solid rgba(14,165,233,0.2);
                                  box-shadow:0 4px 16px rgba(14,165,233,0.12);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#0ea5e9,#0284c7);
                                        box-shadow:0 8px 22px rgba(14,165,233,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-spinner fa-spin-pulse text-white text-xl relative z-10"></i>
                            </div>
                            <h3 class="text-4xl font-black leading-none count" style="color:#0284c7;" data-target="{{ $ticketStats->progress }}">0</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">In Progress</p>
                        </a>

                        {{-- Resolved --}}
                        <a href="{{ route('emp.tickets.index', ['stt' => 3]) }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);
                                  border:1.5px solid rgba(16,185,129,0.2);
                                  box-shadow:0 4px 16px rgba(16,185,129,0.12);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#10b981,#059669);
                                        box-shadow:0 8px 22px rgba(16,185,129,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-check-double text-white text-xl relative z-10"></i>
                            </div>
                            <h3 class="text-4xl font-black leading-none count" style="color:#059669;" data-target="{{ $ticketStats->resolved }}">0</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#10b981;">Resolved</p>
                        </a>

                    </div>
                </div>
            </div>
        </div>


        {{-- ── Tab: Tasks ── --}}
        <div x-show="activeTab === 'tasks'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Task stat cards --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                    <div class="panel-header-gradient">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-chart-column"></i> Tasks Matrix
                        </h3>
                        <a href="{{ route('emp.tasks.index') }}"
                           class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold transition-all group"
                           style="background:rgba(255,255,255,0.18); color:#fff; border:1px solid rgba(255,255,255,0.3);">
                            <span>Go to Tasks</span>
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-[10px]"></i>
                        </a>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

                            {{-- Total Tasks --}}
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);
                                      border:1.5px solid rgba(139,92,246,0.18);
                                      box-shadow:0 4px 16px rgba(139,92,246,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#8b5cf6,#7c3aed);
                                            box-shadow:0 8px 22px rgba(139,92,246,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-layer-group text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#7c3aed;" data-target="{{ $taskStats['total'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#8b5cf6;">Total Tasks</p>
                            </a>

                            {{-- To Do --}}
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#fffbeb,#fef3c7);
                                      border:1.5px solid rgba(245,158,11,0.2);
                                      box-shadow:0 4px 16px rgba(245,158,11,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                            box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-list-ul text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#d97706;" data-target="{{ $taskStats['todo'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">To Do</p>
                            </a>

                            {{-- Overdue or In Progress --}}
                            @if($taskStats['overdue'] > 0)
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#fff1f2,#ffe4e6);
                                      border:1.5px solid rgba(244,63,94,0.2);
                                      box-shadow:0 4px 16px rgba(244,63,94,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#f43f5e,#e11d48);
                                            box-shadow:0 8px 22px rgba(244,63,94,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-triangle-exclamation text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#e11d48;" data-target="{{ $taskStats['overdue'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f43f5e;">Overdue</p>
                            </a>
                            @else
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);
                                      border:1.5px solid rgba(14,165,233,0.2);
                                      box-shadow:0 4px 16px rgba(14,165,233,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#0ea5e9,#0284c7);
                                            box-shadow:0 8px 22px rgba(14,165,233,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-spinner fa-spin-pulse text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#0284c7;" data-target="{{ $taskStats['progress'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">In Progress</p>
                            </a>
                            @endif

                            {{-- Completed --}}
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);
                                      border:1.5px solid rgba(16,185,129,0.2);
                                      box-shadow:0 4px 16px rgba(16,185,129,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#10b981,#059669);
                                            box-shadow:0 8px 22px rgba(16,185,129,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-circle-check text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#059669;" data-target="{{ $taskStats['done'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#10b981;">Completed</p>
                            </a>

                            {{-- Cancelled --}}
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#fef2f2,#fee2e2);
                                      border:1.5px solid rgba(220,38,38,0.15);
                                      box-shadow:0 4px 16px rgba(220,38,38,0.1);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#ef4444,#dc2626);
                                            box-shadow:0 8px 22px rgba(220,38,38,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-circle-xmark text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#dc2626;" data-target="{{ $taskStats['cancelled'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#ef4444;">Cancelled</p>
                            </a>

                        </div>
                    </div>
                </div>

                {{-- Recent Tasks Table --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(139,92,246,0.1); border:1.5px solid rgba(139,92,246,0.12);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 60%,#6d28d9 100%);">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-list-check text-white"></i>
                            <h3 class="text-base font-bold text-white">Recent Activities</h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="premium-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left font-bold text-teal-700">Task</th>
                                    <th class="text-center font-bold text-teal-700">Status</th>
                                    <th class="text-left font-bold text-teal-700">Due</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-violet-50">
                                @forelse($recentTasks as $task)
                                <tr class="hover:bg-violet-50/40 transition-colors cursor-pointer"
                                    onclick="window.location='{{ route('emp.tasks.index') }}'">
                                    <td>
                                        <div class="font-bold text-teal-900 truncate max-w-[200px]">{{ $task->task_title }}</div>
                                        <div class="text-[10px] text-teal-600 uppercase">{{ $task->priority->priority_name ?? 'Normal' }} Priority</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-black text-white uppercase tracking-wider shadow-sm"
                                              style="background:#{{ $task->status->status_color ?? '999' }}">
                                            {{ $task->status->status_name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-xs font-semibold text-teal-700">
                                            {{ $task->task_due_date ? $task->task_due_date->format('M d') : '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-teal-500 font-medium">No tasks found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Assets ── --}}
        <div x-show="activeTab === 'assets'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                <div class="panel-header-gradient">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-laptop-code"></i> Assigned Assets
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th class="text-left font-bold text-slate-400">REF</th>
                                <th class="text-left font-bold text-slate-400">Name</th>
                                <th class="text-left font-bold text-slate-400">Assigned By</th>
                                <th class="text-left font-bold text-slate-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($assets as $asset)
                            @php
                                        $statusName = $asset->status->status_name ?? 'Active';
                                        $gradient = 'from-emerald-500 to-green-600';
                                        $icon = 'fa-circle-check';
                                        
                                        $lowerName = strtolower($statusName);
                                        if (str_contains($lowerName, 'repair') || str_contains($lowerName, 'progress')) {
                                            $gradient = 'from-amber-500 to-orange-600';
                                            $icon = 'fa-screwdriver-wrench';
                                        } elseif (str_contains($lowerName, 'lost') || str_contains($lowerName, 'damage') || str_contains($lowerName, 'broken')) {
                                            $gradient = 'from-rose-500 to-red-600';
                                            $icon = 'fa-circle-xmark';
                                        } elseif (str_contains($lowerName, 'stock') || str_contains($lowerName, 'available')) {
                                            $gradient = 'from-indigo-500 to-purple-600';
                                            $icon = 'fa-box-archive';
                                        }
                                    @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td>
                                    <span class="font-mono text-xs font-bold px-2 py-1 rounded-lg"
                                          style="background:linear-gradient(135deg,rgba(0,79,104,0.08),rgba(0,106,138,0.05)); color:#004F68;">
                                        {{ $asset->asset_ref }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-700">{{ $asset->asset_name }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $asset->asset_sku }}</div>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-slate-600">
                                        {{ $asset->assignedBy ? $asset->assignedBy->first_name : 'System' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-slate-600">
                                        {{ $statusName }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-12">
                                    <i class="fa-solid fa-box-open text-4xl text-slate-200 block mb-3"></i>
                                    <p class="text-slate-400 font-medium">No assets assigned yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Tab: HR & Leaves ── --}}
        <div x-show="activeTab === 'hr'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                    <div class="panel-header-gradient">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-umbrella-beach"></i> HR Summary
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        {{-- Total Requests card --}}
                        <a href="{{ route('emp.leaves.index') }}"
                           class="relative overflow-hidden rounded-2xl p-4 flex items-center justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                           style="background:linear-gradient(135deg,#004F68 0%,#006a8a 60%,#1a8aaa 100%);
                                  box-shadow:0 6px 20px rgba(0,79,104,0.3);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.08) 50%,transparent 70%);"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                                     style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);
                                            box-shadow:0 4px 12px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.25);"></div>
                                    <i class="fa-solid fa-paper-plane text-white relative z-10 text-sm"></i>
                                </div>
                                <span class="font-bold text-white">Total Requests</span>
                            </div>
                            <span class="text-3xl font-black text-white count" data-target="{{ $hrStats['requests'] }}">0</span>
                        </a>
                        {{-- Pending Approval card --}}
                        <a href="{{ route('emp.leaves.index', ['status' => 2]) }}"
                           class="relative overflow-hidden rounded-2xl p-4 flex items-center justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                           style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 60%,#b45309 100%);
                                  box-shadow:0 6px 20px rgba(245,158,11,0.3);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.08) 50%,transparent 70%);"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                                     style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);
                                            box-shadow:0 4px 12px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.25);"></div>
                                    <i class="fa-solid fa-clock-rotate-left text-white relative z-10 text-sm"></i>
                                </div>
                                <span class="font-bold text-slate-700">Pending Approval</span>
                            </div>
                            <span class="text-2xl font-black text-amber-600 count" data-target="{{ $pendingLeaves }}">0</span>
                        </a>
                        <div class="pt-2 flex flex-col gap-2">
                            <a href="{{ route('emp.leaves.index') }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl font-bold text-white shadow-lg transition-all hover:-translate-y-1"
                               style="background:linear-gradient(135deg,#004F68,#006a8a);
                                      box-shadow:0 6px 20px rgba(0,79,104,0.3);">
                                Request a Leave
                                <i class="fa-solid fa-plus-circle"></i>
                            </a>
                            <a href="{{ route('emp.profile.index') }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl font-bold text-white shadow-lg transition-all hover:-translate-y-1"
                               style="background:linear-gradient(135deg,#64748b,#475569);
                                      box-shadow:0 6px 20px rgba(100,116,139,0.3);">
                                View Full Profile
                                <i class="fa-solid fa-user-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center text-center p-8">
                    <div>
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                             style="background:linear-gradient(135deg,rgba(0,79,104,0.08),rgba(0,106,138,0.04));">
                            <i class="fa-solid fa-chart-line text-2xl text-brand-dark opacity-50"></i>
                        </div>
                        <h4 class="font-bold text-slate-500">More Insights Coming Soon</h4>
                        <p class="text-xs text-slate-400 mt-1">Personalized performance metrics will appear here.</p>
                    </div>
                </div>
            </div>
        </div>

            </div>{{-- end space-y-6 (tab content) --}}
    </div>{{-- end x-data tabs (grid container) --}}

<script src="{{ asset('js/attachment-preview.js') }}"></script>
<script>
    // Initialize for modal structure
    document.addEventListener('DOMContentLoaded', () => {
        window.initAttachmentPreview({
            inputSelector: '#dummy-none',
            containerSelector: '#dummy-none'
        });
    });

    // Counter Animation
    document.querySelectorAll('.count').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));
        if (isNaN(target)) return;
        let count = 0;
        const inc = Math.max(1, target / 30);
        if (target >= 0) {
            const timer = setInterval(() => {
                count += inc;
                if (count >= target) { el.innerText = target; clearInterval(timer); }
                else { el.innerText = Math.ceil(count); }
            }, 30);
        }
    });

    // Carousel Logic
    let curAnn = 0;
    const slides = document.querySelectorAll('.announcement-slide');
    const dots = document.querySelectorAll('#ann-dots > div');
    const totalSlides = slides.length;

    function showAnn(index) {
        if (!totalSlides) return;
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0','translate-y-10','pointer-events-none','absolute','inset-0');
                slide.classList.add('opacity-100','translate-y-0');
            } else {
                slide.classList.add('opacity-0','translate-y-10','pointer-events-none','absolute','inset-0');
                slide.classList.remove('opacity-100','translate-y-0');
            }
        });
        dots.forEach((dot, i) => {
            if (i === index) { dot.classList.add('bg-brand-dark','w-4'); dot.classList.remove('bg-slate-200','w-2'); }
            else { dot.classList.remove('bg-brand-dark','w-4'); dot.classList.add('bg-slate-200','w-2'); }
        });
    }
    function nextAnn() { curAnn = (curAnn + 1) % totalSlides; showAnn(curAnn); }
    function prevAnn() { curAnn = (curAnn - 1 + totalSlides) % totalSlides; showAnn(curAnn); }
</script>

@endsection