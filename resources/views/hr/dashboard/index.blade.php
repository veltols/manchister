@extends('layouts.app')

@section('title', 'HR Dashboard')
@section('subtitle', 'Overview of your workforce')

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .hr-welcome-banner {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 45%, #1a8aaa 80%, #0ea5e9 100%);
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(0,79,104,0.3), 0 4px 12px rgba(0,0,0,0.1);
        min-height: 180px;
    }
    .hr-welcome-banner::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .hr-welcome-banner::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── Tab icon-box cards ── */
    .hr-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 115px;
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
    .hr-tab-btn:hover {
        transform: translateY(-4px) scale(1.04);
        box-shadow: 0 8px 24px rgba(0,79,104,0.15);
    }
    .hr-tab-btn .tab-icon-box {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        position: relative; overflow: hidden;
    }
    .hr-tab-btn .tab-icon-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        border-radius: 14px 14px 0 0;
        pointer-events: none;
    }
    .hr-tab-btn:hover .tab-icon-box {
        transform: scale(1.12) rotate(-5deg);
    }
    .hr-tab-btn.active-tab {
        border-color: rgba(0,79,104,0.2);
        box-shadow: 0 8px 28px rgba(0,79,104,0.18), inset 0 1px 0 rgba(255,255,255,0.9);
        transform: translateY(-2px);
    }

    /* ── Gradient stat card ── */
    .grad-stat-card {
        border-radius: 22px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34,1.2,0.64,1);
        text-decoration: none;
    }
    .grad-stat-card:hover {
        transform: translateY(-6px);
    }
    .grad-stat-card .stat-icon-box {
        width: 56px; height: 56px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        position: relative; overflow: hidden;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        flex-shrink: 0;
    }
    .grad-stat-card:hover .stat-icon-box {
        transform: scale(1.12) rotate(-5deg);
    }
    .grad-stat-card .stat-icon-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.35) 0%, transparent 100%);
        border-radius: 18px 18px 0 0;
    }

    /* ── Panel header ── */
    .panel-header-gradient {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 60%, #1a8aaa 100%);
        padding: 1.1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Quick action card ── */
    .quick-action-card {
        display: flex; align-items: center; gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-radius: 20px;
        text-decoration: none;
        position: relative; overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34,1.2,0.64,1);
    }
    .quick-action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.18);
    }
    .quick-action-card .qa-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        position: relative; overflow: hidden;
    }
    .quick-action-card .qa-icon::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.35) 0%, transparent 100%);
        pointer-events: none;
    }

    /* ── Announcement card ── */
    .ann-card {
        background: linear-gradient(135deg, rgba(0,79,104,0.04) 0%, rgba(0,106,138,0.02) 100%);
        border-radius: 20px;
        border: 1.5px solid rgba(0,79,104,0.1);
        padding: 1.5rem;
        position: relative; overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .ann-card::before {
        content: '';
        position: absolute;
        right: -20px; top: -20px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,79,104,0.06) 0%, transparent 70%);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════
         WELCOME BANNER
    ═══════════════════════════════════ --}}
    <div class="hr-welcome-banner">
        <div class="flex flex-col justify-center h-full px-8 md:px-14 py-10 md:py-12 relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full mb-4 w-fit"
                 style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-white">HR Command Centre</span>
            </div>
            <div class="flex items-center gap-4 mb-2">
                {{-- 3D Icon Box --}}
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.28),rgba(255,255,255,0.1));
                            border:1.5px solid rgba(255,255,255,0.35);
                            box-shadow:0 8px 24px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.5);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.35);"></div>
                    <i class="fa-solid fa-users text-white text-2xl relative z-10 drop-shadow-sm"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-extrabold text-white tracking-tight drop-shadow-sm">
                    Workforce Overview
                </h1>
            </div>
            <p class="text-sky-100 text-sm md:text-base font-medium max-w-md leading-relaxed mb-6">
                Monitor your team, demographics, skills and HR actions — all in one place.
            </p>

            {{-- Premium Announcements Ticker --}}
            <div class="w-full max-w-xl relative group">
                <!-- Glow behind the card -->
                <div class="absolute inset-0 bg-gradient-to-r from-amber-400/20 via-orange-500/10 to-transparent blur-xl rounded-2xl group-hover:from-amber-400/30 transition-all duration-500 pointer-events-none"></div>
                
                @php
                    $latest = $announcements->first();
                    $announcementsData = $latest 
                        ? [[
                            'id' => $latest->document_id,
                            'title' => $latest->document_title,
                            'description' => $latest->document_description ?: 'No additional details provided.',
                            'attachment' => $latest->document_attachment ? asset('uploads/' . $latest->document_attachment) : '',
                            'date' => $latest->added_date ? \Carbon\Carbon::parse($latest->added_date)->format('M d, Y') : 'Recent'
                        ]]
                        : [['id' => 0, 'title' => 'No new announcements at this time. You\'re all caught up!', 'description' => '', 'attachment' => '', 'date' => '']];
                @endphp

                <div x-data="{ 
                        activeIndex: 0, 
                        items: {{ json_encode($announcementsData) }},
                        showModal: false,
                        selectedItem: null
                    }" 
                    x-init="if(items.length > 1) { setInterval(() => { if(!showModal) activeIndex = (activeIndex + 1) % items.length }, 5000) }"
                    class="w-full">
                    
                    <!-- Ticker Card -->
                    <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-4 flex items-center gap-4 shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:bg-white/15 transition-all duration-300 overflow-hidden cursor-pointer"
                         style="box-shadow: inset 0 1px 0 rgba(255,255,255,0.4), 0 10px 25px rgba(0,0,0,0.1);"
                         @click="if(items[activeIndex].id > 0) { selectedItem = items[activeIndex]; showModal = true; }">
                         
                         <!-- Animated 3D Icon -->
                         <div class="relative flex-shrink-0">
                             <div class="absolute inset-0 bg-amber-400 rounded-xl blur-md opacity-50 animate-pulse"></div>
                             <div class="w-10 h-10 rounded-xl flex items-center justify-center relative z-10" 
                                  style="background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.05) 100%); border: 1px solid rgba(255,255,255,0.4); box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);">
                                 <i class="fa-solid fa-bolt text-amber-300 text-sm"></i>
                             </div>
                         </div>
                         
                         <!-- Carousel Content -->
                         <div class="flex-1 min-w-0">
                             <div class="flex items-center gap-2 mb-1">
                                 <span class="px-2 py-0.5 rounded-md bg-amber-500/20 border border-amber-400/30 text-[9px] font-black text-amber-200 uppercase tracking-widest shadow-sm">Announcement</span>
                             </div>
                             
                             <div class="relative h-5">
                                 <template x-for="(item, index) in items" :key="index">
                                     <div x-show="activeIndex === index" 
                                          x-transition:enter="transition-all duration-700 ease-out absolute inset-0"
                                          x-transition:enter-start="opacity-0 translate-x-8"
                                          x-transition:enter-end="opacity-100 translate-x-0"
                                          x-transition:leave="transition-all duration-700 ease-in absolute inset-0"
                                          x-transition:leave-start="opacity-100 translate-x-0"
                                          x-transition:leave-end="opacity-0 -translate-x-8"
                                          class="flex items-center">
                                         <p class="text-sm font-bold text-white truncate" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);" x-text="item.title"></p>
                                     </div>
                                 </template>
                             </div>
                         </div>
                         
                         <!-- Interaction Hint -->
                         <div class="hidden sm:flex flex-shrink-0">
                             <div class="w-8 h-8 rounded-full border border-white/30 flex items-center justify-center text-white/70 group-hover:text-white group-hover:border-white/60 group-hover:bg-white/10 transition-all">
                                 <i class="fa-solid fa-expand text-xs transform group-hover:scale-110 transition-transform"></i>
                             </div>
                         </div>
                    </div>

                    <!-- AlpineJS Modal for Announcement Details -->
                    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <!-- Background overlay -->
                            <div x-show="showModal" 
                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" @click="showModal = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal panel -->
                            <div x-show="showModal" 
                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                                 
                                 <!-- Header -->
                                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shadow-inner">
                                            <i class="fa-solid fa-bullhorn"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-800 leading-tight" id="modal-title" x-text="selectedItem?.title"></h3>
                                            <p class="text-xs font-semibold text-slate-500 mt-0.5 uppercase tracking-wider" x-text="selectedItem?.date"></p>
                                        </div>
                                    </div>
                                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200/50 p-2 rounded-lg transition-colors">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>

                                <!-- Content -->
                                <div class="px-6 py-6">
                                    <div class="prose prose-sm text-slate-600 whitespace-pre-line" x-text="selectedItem?.description"></div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Big 3D HR icon cluster (right side) --}}
        <div class="absolute right-8 md:right-16 top-1/2 -translate-y-1/2 pointer-events-none hidden md:flex items-center justify-center" style="width:220px; height:180px;">

            {{-- Main large icon --}}
            <div class="absolute" style="width:90px; height:90px; top:50%; left:50%; transform:translate(-50%,-55%);">
                <div class="w-full h-full rounded-3xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg, rgba(255,255,255,0.28) 0%, rgba(255,255,255,0.08) 100%);
                            border:1.5px solid rgba(255,255,255,0.4);
                            box-shadow:0 16px 48px rgba(0,0,0,0.2), inset 0 2px 0 rgba(255,255,255,0.5);
                            animation: float 4s ease-in-out infinite;">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-3xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-users text-white relative z-10" style="font-size:2.4rem; filter:drop-shadow(0 4px 8px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

            {{-- Top-right: chart icon --}}
            <div class="absolute" style="width:52px; height:52px; top:0; right:10px; animation: float 4s ease-in-out infinite; animation-delay:0.6s;">
                <div class="w-full h-full rounded-2xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.22) 0%,rgba(255,255,255,0.06) 100%);
                            border:1.5px solid rgba(255,255,255,0.3);
                            box-shadow:0 8px 24px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.45);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-chart-pie text-white relative z-10" style="font-size:1.2rem; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

            {{-- Bottom-left: briefcase icon --}}
            <div class="absolute" style="width:52px; height:52px; bottom:0; left:0; animation: float 4s ease-in-out infinite; animation-delay:1.2s;">
                <div class="w-full h-full rounded-2xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.22) 0%,rgba(255,255,255,0.06) 100%);
                            border:1.5px solid rgba(255,255,255,0.3);
                            box-shadow:0 8px 24px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.45);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-briefcase text-white relative z-10" style="font-size:1.2rem; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

            {{-- Bottom-right: certificate icon --}}
            <div class="absolute" style="width:46px; height:46px; bottom:4px; right:4px; animation: float 4s ease-in-out infinite; animation-delay:1.8s;">
                <div class="w-full h-full rounded-xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.18) 0%,rgba(255,255,255,0.05) 100%);
                            border:1.5px solid rgba(255,255,255,0.25);
                            box-shadow:0 6px 18px rgba(0,0,0,0.12),inset 0 1px 0 rgba(255,255,255,0.4);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.25);"></div>
                    <i class="fa-solid fa-certificate text-white relative z-10" style="font-size:1rem; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

        </div>
    </div>


    {{-- ═══════════════════════════════════
         TABS + CONTENT
    ═══════════════════════════════════ --}}
    <div x-data="{ activeTab: 'activity' }" class="space-y-6">

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
                <h3 class="text-lg font-display font-bold text-premium">Current Announcements</h3>
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

        {{-- Icon-box tab buttons --}}
        <div class="flex flex-wrap items-center justify-end gap-3 w-full">

            {{-- Workforce --}}
            <button @click="activeTab = 'workforce'"
                class="hr-tab-btn"
                :class="activeTab === 'workforce' ? 'active-tab' : ''"
                :style="activeTab === 'workforce' ? 'background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8;' : 'color:#1d4ed8;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'workforce'
                        ? 'background:linear-gradient(145deg,#3b82f6,#2563eb); box-shadow:0 6px 18px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#eff6ff,#bfdbfe); color:#3b82f6; box-shadow:0 3px 8px rgba(37,99,235,0.15);'">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span>Workforce</span>
            </button>

            {{-- Demographics --}}
            <button @click="activeTab = 'demographics'"
                class="hr-tab-btn"
                :class="activeTab === 'demographics' ? 'active-tab' : ''"
                :style="activeTab === 'demographics' ? 'background:linear-gradient(135deg,#fdf4ff,#fae8ff); color:#7e22ce;' : 'color:#7e22ce;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'demographics'
                        ? 'background:linear-gradient(145deg,#a855f7,#9333ea); box-shadow:0 6px 18px rgba(168,85,247,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#fdf4ff,#e9d5ff); color:#a855f7; box-shadow:0 3px 8px rgba(168,85,247,0.15);'">
                    <i class="fa-solid fa-venus-mars"></i>
                </div>
                <span>Demographics</span>
            </button>

            {{-- Skills --}}
            <button @click="activeTab = 'skills'"
                class="hr-tab-btn"
                :class="activeTab === 'skills' ? 'active-tab' : ''"
                :style="activeTab === 'skills' ? 'background:linear-gradient(135deg,#f0fdf4,#dcfce7); color:#15803d;' : 'color:#15803d;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'skills'
                        ? 'background:linear-gradient(145deg,#22c55e,#16a34a); box-shadow:0 6px 18px rgba(22,163,74,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#f0fdf4,#bbf7d0); color:#22c55e; box-shadow:0 3px 8px rgba(22,163,74,0.15);'">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <span>Skills &amp; Certs</span>
            </button>

            {{-- Quick Actions --}}
            <button @click="activeTab = 'actions'"
                class="hr-tab-btn"
                :class="activeTab === 'actions' ? 'active-tab' : ''"
                :style="activeTab === 'actions' ? 'background:linear-gradient(135deg,#fff7ed,#fed7aa); color:#c2410c;' : 'color:#c2410c;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'actions'
                        ? 'background:linear-gradient(145deg,#f97316,#ea580c); box-shadow:0 6px 18px rgba(234,88,12,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#fff7ed,#fed7aa); color:#f97316; box-shadow:0 3px 8px rgba(234,88,12,0.15);'">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span>Quick Actions</span>
            </button>

            {{-- Activity --}}
            <button @click="activeTab = 'activity'"
                class="hr-tab-btn"
                :class="activeTab === 'activity' ? 'active-tab' : ''"
                :style="activeTab === 'activity' ? 'background:linear-gradient(135deg,#f0fdfa,#ccfbf1); color:#0f766e;' : 'color:#0f766e;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'activity'
                        ? 'background:linear-gradient(145deg,#14b8a6,#0d9488); box-shadow:0 6px 18px rgba(13,148,136,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#f0fdfa,#ccfbf1); color:#14b8a6; box-shadow:0 3px 8px rgba(13,148,136,0.15);'">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span>My Activity</span>
            </button>

        </div>

        {{-- ── Tab: Workforce ── --}}
        <div x-show="activeTab === 'workforce'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Stat cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Total Employees --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1.5px solid rgba(37,99,235,0.15); box-shadow:0 4px 16px rgba(37,99,235,0.1);">
                        <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-500 rounded-2xl"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#3b82f6,#2563eb); box-shadow:0 8px 22px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-users text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none count" style="color:#2563eb;" data-target="{{ $totalEmps }}">0</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#3b82f6;">Total Employees</p>
                    </div>

                    {{-- Departments --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#fff7ed,#fed7aa); border:1.5px solid rgba(245,158,11,0.2); box-shadow:0 4px 16px rgba(245,158,11,0.1);">
                        <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-500 rounded-2xl"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#f59e0b,#d97706); box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-building text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#d97706;">{{ count($deptDataLabels) }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">Departments</p>
                    </div>

                </div>

                {{-- Chart --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(37,99,235,0.08); border:1.5px solid rgba(37,99,235,0.1);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 60%,#1d4ed8 100%);">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-white"></i>
                            <h3 class="font-bold text-white">Employees by Department</h3>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold text-white"
                              style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                            {{ array_sum($deptDataCounts) }} Total
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="empByDept"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Demographics ── --}}
        <div x-show="activeTab === 'demographics'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Stat cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Average Age --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#fdf4ff,#f3e8ff); border:1.5px solid rgba(168,85,247,0.15); box-shadow:0 4px 16px rgba(168,85,247,0.1);">
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#a855f7,#9333ea); box-shadow:0 8px 22px rgba(168,85,247,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-cake-candles text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#9333ea;">
                            <span class="count" data-target="{{ $averageAge }}">0</span>
                        </h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#a855f7;">Avg Age</p>
                    </div>

                    {{-- Diversity --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#fff1f2,#ffe4e6); border:1.5px solid rgba(244,63,94,0.15); box-shadow:0 4px 16px rgba(244,63,94,0.1);">
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#f43f5e,#e11d48); box-shadow:0 8px 22px rgba(244,63,94,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-venus-mars text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#e11d48;">{{ $diversityStat }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f43f5e;">Diversity</p>
                    </div>

                </div>

                {{-- Chart --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(168,85,247,0.08); border:1.5px solid rgba(168,85,247,0.12);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#a855f7 0%,#9333ea 60%,#7e22ce 100%);">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-white"></i>
                            <h3 class="font-bold text-white">Gender Distribution</h3>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold text-white"
                              style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                            Diversity Metrics
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="empByGender"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Skills & Certs ── --}}
        <div x-show="activeTab === 'skills'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Stat cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Certifications --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(22,163,74,0.15); box-shadow:0 4px 16px rgba(22,163,74,0.1);">
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#22c55e,#16a34a); box-shadow:0 8px 22px rgba(22,163,74,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-certificate text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#16a34a;">{{ count($certDataLabels) }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#22c55e;">Certifications</p>
                    </div>

                </div>

                {{-- Chart --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(22,163,74,0.08); border:1.5px solid rgba(22,163,74,0.12);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#22c55e 0%,#16a34a 60%,#15803d 100%);">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-bar text-white"></i>
                            <h3 class="font-bold text-white">Employees by Certification</h3>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold text-white"
                              style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                            Skills Overview
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="empByCert"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Quick Actions ── --}}
        <div x-show="activeTab === 'actions'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(234,88,12,0.08); border:1.5px solid rgba(234,88,12,0.12);">
                <div class="panel-header-gradient" style="background:linear-gradient(135deg,#f97316 0%,#ea580c 60%,#c2410c 100%);">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-white"></i>
                        <h3 class="font-bold text-white">Quick Actions</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        {{-- Manage Leaves --}}
                        <a href="{{ route('hr.leaves.index') }}" class="quick-action-card"
                           style="background:linear-gradient(135deg,#0ea5e9 0%,#0284c7 60%,#0369a1 100%); box-shadow:0 6px 20px rgba(14,165,233,0.3);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);"></div>
                            <div class="qa-icon" style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                                <i class="fa-solid fa-calendar-check text-white relative z-10"></i>
                            </div>
                            <div class="flex-1 relative z-10">
                                <p class="font-bold text-white">Manage Leaves</p>
                                <p class="text-xs text-white/70">Review &amp; approve requests</p>
                            </div>
                            <i class="fa-solid fa-chevron-right text-white/60 relative z-10 transition-transform group-hover:translate-x-1"></i>
                        </a>

                        {{-- Performance --}}
                        <a href="{{ route('hr.performance.index') }}" class="quick-action-card"
                           style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 60%,#b45309 100%); box-shadow:0 6px 20px rgba(245,158,11,0.3);">
                            <div class="qa-icon" style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                                <i class="fa-solid fa-star text-white relative z-10"></i>
                            </div>
                            <div class="flex-1 relative z-10">
                                <p class="font-bold text-white">Performance</p>
                                <p class="text-xs text-white/70">Employee reviews</p>
                            </div>
                            <i class="fa-solid fa-chevron-right text-white/60 relative z-10"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: My Activity (Tickets) ── --}}
        <div x-show="activeTab === 'activity'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(13,148,136,0.08); border:1.5px solid rgba(13,148,136,0.12);">
                <div class="panel-header-gradient" style="background:linear-gradient(135deg,#14b8a6 0%,#0d9488 60%,#0f766e 100%);">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-ticket text-white"></i>
                        <h3 class="font-bold text-white">My Assigned Tickets</h3>
                    </div>
                    <a href="{{ route('hr.tickets.index') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all hover:-translate-y-0.5 group"
                       style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3); color:white;">
                        <span>View All Tickets</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr style="background:rgba(13,148,136,0.04); border-bottom:1.5px solid rgba(13,148,136,0.08);">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#0f766e;">Ref ID</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#0f766e;">Subject</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#0f766e;">Requested By</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#0f766e;">Date Added</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center" style="color:#0f766e;">Status</th>
                            </tr>
                        </thead>
                        <tbody style="divide-color:rgba(13,148,136,0.06);">
                            @forelse($myTickets as $ticket)
                                <tr class="transition-colors hover:bg-teal-50/40" style="border-bottom:1px solid rgba(13,148,136,0.06);">
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-semibold px-2 py-1 rounded text-xs select-all bg-teal-50 text-teal-700">
                                            #{{ $ticket->ticket_ref ?? $ticket->ticket_id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-slate-800">{{ $ticket->ticket_subject }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if($ticket->added_employee)
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white ring-2 ring-white"
                                                    style="background:linear-gradient(135deg,#0d9488,#0f766e); box-shadow:0 2px 8px rgba(13,148,136,0.3);">
                                                    {{ strtoupper(substr($ticket->added_employee, 0, 1)) }}
                                                </div>
                                                <span class="text-sm font-medium text-slate-700">{{ $ticket->added_employee }}</span>
                                            @else
                                                <span class="text-sm text-slate-500">N/A</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($ticket->status_id == 1)
                                            <span class="px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-100 rounded-full">Open</span>
                                        @elseif($ticket->status_id == 2)
                                            <span class="px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 rounded-full">In Progress</span>
                                        @elseif($ticket->status_id == 3)
                                            <span class="px-3 py-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-100 rounded-full">Resolved</span>
                                        @else
                                            <span class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-full">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                                                 style="background:rgba(13,148,136,0.06);">
                                                <i class="fa-solid fa-inbox text-2xl text-teal-600/30"></i>
                                            </div>
                                            <p class="font-medium text-teal-800">No tickets assigned to you.</p>
                                            <p class="text-xs mt-1 text-teal-600">You're all caught up!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js Library --}}
<script src="{{ asset('libs/chartjs/chart.min.js') }}"></script>
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
        const inc = target / 50;
        if (target > 0) {
            const timer = setInterval(() => {
                count += inc;
                if (count >= target) { el.innerText = target; clearInterval(timer); }
                else { el.innerText = Math.ceil(count); }
            }, 20);
        }
    });

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#006a8a';

    // 1. Department Chart (Doughnut)
    const ctxDept = document.getElementById('empByDept');
    new Chart(ctxDept, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deptDataLabels) !!},
            datasets: [{
                data: {!! json_encode($deptDataCounts) !!},
                backgroundColor: [
                    'rgba(59,130,246,0.85)',
                    'rgba(139,92,246,0.85)',
                    'rgba(16,185,129,0.85)',
                    'rgba(245,158,11,0.85)',
                    'rgba(244,63,94,0.85)',
                ],
                borderWidth: 0, hoverOffset: 10
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, font: { size: 12, weight: '500' }, usePointStyle: true, pointStyle: 'circle' } },
                tooltip: { backgroundColor: 'rgba(0,79,104,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 } }
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
                    'rgba(59,130,246,0.85)',
                    'rgba(244,63,94,0.85)',
                    'rgba(168,85,247,0.85)',
                ],
                borderWidth: 0, hoverOffset: 10
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, font: { size: 12, weight: '500' }, usePointStyle: true, pointStyle: 'circle' } },
                tooltip: { backgroundColor: 'rgba(168,85,247,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 } }
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
                backgroundColor: 'rgba(22,163,74,0.85)',
                borderRadius: 10, borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,79,104,0.05)', drawBorder: false }, ticks: { font: { size: 11 }, padding: 8 } },
                x: { grid: { display: false }, ticks: { font: { size: 11 }, padding: 8 } }
            },
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: 'rgba(22,163,74,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 } }
            }
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