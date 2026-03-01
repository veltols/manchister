@extends('layouts.app')

@section('title', 'Study: ' . $study->study_title)
@section('subtitle', $study->study_ref)

@section('content')
<div x-data="{
        activeTab: 'overview',
        editOpen: false,
        addPageOpen: false, addPageType: 'introductry',
        editPageOpen: false, editPageId: 0, editPageTitle: '', editPageContent: '',
        deletePageOpen: false, deletePageId: 0, deletePageTitle: '',
    }"
     class="space-y-6 animate-fade-in-up">

    {{-- ── EDIT STUDY MODAL ── --}}
    <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);">
        <div @click.outside="editOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto"
             style="border:1.5px solid rgba(0,79,104,0.12);">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-pen text-brand-dark"></i> Edit Study
                </h3>
                <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form action="{{ route('emp.ext.strategies.self_studies.update', $study->study_id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Title *</label>
                    <input type="text" name="study_title" value="{{ $study->study_title }}" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Overview *</label>
                    <textarea name="study_overview" rows="5" required
                              class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors">{{ $study->study_overview }}</textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="editOpen = false"
                            class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit" class="premium-button px-5 py-2 text-sm">
                        <i class="fa-solid fa-save text-xs"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── ADD PAGE MODAL ── --}}
    <div x-show="addPageOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);">
        <div @click.outside="addPageOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6"
             style="border:1.5px solid rgba(0,79,104,0.12);">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-file-plus text-brand-dark"></i>
                    <span x-text="addPageType === 'introductry' ? 'Add Introductory Page' : 'Add Section'"></span>
                </h3>
                <button @click="addPageOpen = false" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form action="{{ route('emp.ext.strategies.self_studies.pages.store', $study->study_id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="page_type" :value="addPageType">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Page Title *</label>
                    <input type="text" name="page_title" required placeholder="e.g. Executive Summary"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Content</label>
                    <textarea name="page_content" rows="5"
                              placeholder="Page content (optional, can be added later)..."
                              class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="addPageOpen = false"
                            class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="premium-button px-5 py-2 text-sm">
                        <i class="fa-solid fa-plus text-xs"></i> Add Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── EDIT PAGE MODAL ── --}}
    <div x-show="editPageOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);">
        <div @click.outside="editPageOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto"
             style="border:1.5px solid rgba(0,79,104,0.12);">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-brand-dark"></i> Edit Page
                </h3>
                <button @click="editPageOpen = false" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form :action="`{{ url('emp/ext/strategies/self_studies/view/' . $study->study_id . '/pages') }}/${editPageId}`" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Page Title *</label>
                    <input type="text" name="page_title" :value="editPageTitle" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Content</label>
                    <textarea name="page_content" rows="8" x-text="editPageContent"
                              class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="editPageOpen = false"
                            class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="premium-button px-5 py-2 text-sm">
                        <i class="fa-solid fa-save text-xs"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DELETE PAGE MODAL ── --}}
    <div x-show="deletePageOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);">
        <div @click.outside="deletePageOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
             style="border:1.5px solid rgba(239,68,68,0.2);">
            <div class="text-center mb-5">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-trash text-red-400 text-lg"></i>
                </div>
                <h3 class="text-base font-bold text-slate-700">Delete Page?</h3>
                <p class="text-sm text-slate-400 mt-1">
                    "<span class="font-semibold text-slate-600" x-text="deletePageTitle"></span>" will be permanently removed.
                </p>
            </div>
            <form :action="`{{ url('emp/ext/strategies/self_studies/view/' . $study->study_id . '/pages') }}/${deletePageId}/delete`" method="POST">
                @csrf
                <div class="flex gap-2">
                    <button type="button" @click="deletePageOpen = false"
                            class="flex-1 px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 rounded-xl text-sm font-bold text-white transition-colors"
                            style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── HEADER ── --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-400 mb-2">
                <a href="{{ route('emp.ext.strategies.self_studies.index') }}"
                   class="hover:text-brand-dark transition-colors flex items-center gap-1.5 font-semibold">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Self Studies
                </a>
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                <span class="text-slate-500">{{ $study->study_ref }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-display font-bold text-premium">{{ $study->study_title }}</h1>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-xs text-slate-400">
                    Created {{ \Carbon\Carbon::parse($study->added_date)->format('d M Y') }}
                </span>
            </div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <button @click="editOpen = true" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-pen text-xs"></i> Edit Study
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl text-emerald-700 text-sm font-semibold"
             style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.2);">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ── TABS ── --}}
    <div class="border-b border-slate-200">
        <nav class="-mb-px flex overflow-x-auto">
            @foreach(['overview' => ['Overview','fa-circle-info'], 'introductry' => ['Introductory Pages','fa-file-lines'], 'sections' => ['Sections','fa-layer-group']] as $tab => [$label, $icon])
            <button @click="activeTab = '{{ $tab }}'"
                :class="activeTab === '{{ $tab }}' ? 'font-bold' : 'text-slate-500 hover:text-slate-700'"
                :style="activeTab === '{{ $tab }}' ? 'color:#004F68; border-bottom:2px solid #004F68;' : 'border-bottom:2px solid transparent;'"
                class="whitespace-nowrap py-4 px-4 text-sm transition-all flex items-center gap-1.5">
                <i class="fa-solid {{ $icon }} text-xs"></i> {{ $label }}
                @if($tab === 'introductry')
                    <span class="ml-1 text-[10px] font-black px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600">{{ $introPages->count() }}</span>
                @elseif($tab === 'sections')
                    <span class="ml-1 text-[10px] font-black px-1.5 py-0.5 rounded-full bg-purple-50 text-purple-600">{{ $sections->count() }}</span>
                @endif
            </button>
            @endforeach
        </nav>
    </div>

    {{-- ── OVERVIEW TAB ── --}}
    <div x-show="activeTab === 'overview'" class="space-y-5">
        <div class="premium-card p-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fa-solid fa-align-left"></i> Study Overview
            </h3>
            <div class="text-slate-700 leading-relaxed whitespace-pre-line text-sm">{{ $study->study_overview }}</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="premium-card p-5" @click="activeTab = 'introductry'" style="cursor:pointer;">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         style="background:linear-gradient(145deg,#6366f1,#4f46e5);">
                        <i class="fa-solid fa-file-lines text-white text-xs"></i>
                    </div>
                    <h3 class="font-bold text-slate-700">Introductory Pages</h3>
                </div>
                <p class="text-3xl font-black" style="color:#6366f1;">{{ $introPages->count() }}</p>
                <p class="text-xs text-slate-400 mt-1">pages added</p>
            </div>
            <div class="premium-card p-5" @click="activeTab = 'sections'" style="cursor:pointer;">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         style="background:linear-gradient(145deg,#8b5cf6,#7c3aed);">
                        <i class="fa-solid fa-layer-group text-white text-xs"></i>
                    </div>
                    <h3 class="font-bold text-slate-700">Sections</h3>
                </div>
                <p class="text-3xl font-black" style="color:#8b5cf6;">{{ $sections->count() }}</p>
                <p class="text-xs text-slate-400 mt-1">sections added</p>
            </div>
        </div>
    </div>

    {{-- ── INTRODUCTORY PAGES TAB ── --}}
    <div x-show="activeTab === 'introductry'" style="display:none;">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-base font-bold text-slate-700">Introductory Pages</h3>
            <button @click="addPageOpen = true; addPageType = 'introductry'" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Add Page
            </button>
        </div>

        @forelse($introPages as $page)
        <div class="premium-card mb-4 overflow-hidden">
            <div class="flex justify-between items-start p-5">
                <div class="flex items-start gap-3 flex-1">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                         style="background:rgba(99,102,241,0.1);">
                        <i class="fa-solid fa-file text-xs" style="color:#6366f1;"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-700 text-sm mb-1">{{ $page->page_title }}</h4>
                        @if($page->page_content)
                            <p class="text-xs text-slate-400 line-clamp-2">{{ Str::limit($page->page_content, 120) }}</p>
                        @else
                            <p class="text-xs text-slate-300 italic">No content yet</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0 ml-4">
                    <button @click="editPageOpen = true; editPageId = {{ $page->page_id }}; editPageTitle = '{{ addslashes($page->page_title) }}'; editPageContent = '{{ addslashes($page->page_content) }}'"
                            class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-indigo-600 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-pencil text-xs"></i>
                    </button>
                    <button @click="deletePageOpen = true; deletePageId = {{ $page->page_id }}; deletePageTitle = '{{ addslashes($page->page_title) }}'"
                            class="w-8 h-8 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-14 bg-white rounded-2xl border-2 border-dashed border-slate-200">
            <i class="fa-solid fa-file-lines text-3xl text-slate-300 mb-3"></i>
            <p class="text-slate-500 font-bold text-sm mb-1">No Introductory Pages</p>
            <p class="text-xs text-slate-400 mb-3">Add pages like executive summary, methodology, etc.</p>
            <button @click="addPageOpen = true; addPageType = 'introductry'" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Add First Page
            </button>
        </div>
        @endforelse
    </div>

    {{-- ── SECTIONS TAB ── --}}
    <div x-show="activeTab === 'sections'" style="display:none;">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-base font-bold text-slate-700">Study Sections</h3>
            <button @click="addPageOpen = true; addPageType = 'section'" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Add Section
            </button>
        </div>

        @forelse($sections as $i => $section)
        <div class="premium-card mb-4 overflow-hidden">
            <div class="flex justify-between items-start p-5">
                <div class="flex items-start gap-3 flex-1">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 font-black text-xs"
                         style="background:rgba(139,92,246,0.1); color:#8b5cf6;">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-700 text-sm mb-1">{{ $section->page_title }}</h4>
                        @if($section->page_content)
                            <p class="text-xs text-slate-400 line-clamp-2">{{ Str::limit($section->page_content, 120) }}</p>
                        @else
                            <p class="text-xs text-slate-300 italic">No content yet</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0 ml-4">
                    <button @click="editPageOpen = true; editPageId = {{ $section->page_id }}; editPageTitle = '{{ addslashes($section->page_title) }}'; editPageContent = '{{ addslashes($section->page_content) }}'"
                            class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-purple-600 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-pencil text-xs"></i>
                    </button>
                    <button @click="deletePageOpen = true; deletePageId = {{ $section->page_id }}; deletePageTitle = '{{ addslashes($section->page_title) }}'"
                            class="w-8 h-8 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-14 bg-white rounded-2xl border-2 border-dashed border-slate-200">
            <i class="fa-solid fa-layer-group text-3xl text-slate-300 mb-3"></i>
            <p class="text-slate-500 font-bold text-sm mb-1">No Sections Yet</p>
            <p class="text-xs text-slate-400 mb-3">Add content sections to structure your study.</p>
            <button @click="addPageOpen = true; addPageType = 'section'" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Add First Section
            </button>
        </div>
        @endforelse
    </div>

</div>
@endsection
