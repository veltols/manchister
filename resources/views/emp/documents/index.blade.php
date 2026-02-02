@extends('layouts.app')

@section('title', 'Company Documents')
@section('subtitle', 'Access policies, procedures, and shared resources')

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        <!-- Filters & Search -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div
                class="flex bg-slate-100 p-1.5 rounded-2xl shadow-inner border border-slate-200/50 overflow-x-auto max-w-full scrollbar-hide">
                <a href="{{ route('emp.documents.index') }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ !$typeId ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    All Documents
                </a>
                @foreach($types as $type)
                    <a href="{{ route('emp.documents.index', ['type_id' => $type->document_type_id]) }}"
                        class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $typeId == $type->document_type_id ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark whitespace-nowrap' }}">
                        {{ $type->document_type_name }}
                    </a>
                @endforeach
            </div>

            <div class="relative w-full md:w-80">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" class="premium-input pl-11 pr-4 py-2.5 w-full text-sm" placeholder="Search documents...">
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($documents as $doc)
                <div class="premium-card p-6 flex flex-col hover:border-indigo-200 group transition-all duration-300">
                    <div class="flex items-start justify-between mb-6">
                        <div
                            class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                            @php
                                $ext = pathinfo($doc->document_attachment, PATHINFO_EXTENSION);
                                $icon = 'fa-file';
                                if (in_array($ext, ['pdf']))
                                    $icon = 'fa-file-pdf';
                                if (in_array($ext, ['doc', 'docx']))
                                    $icon = 'fa-file-word';
                                if (in_array($ext, ['xls', 'xlsx']))
                                    $icon = 'fa-file-excel';
                                if (in_array($ext, ['jpg', 'png', 'jpeg']))
                                    $icon = 'fa-file-image';
                            @endphp
                            <i class="fa-solid {{ $icon }} text-2xl"></i>
                        </div>
                        <span
                            class="text-[10px] font-bold text-slate-400 bg-slate-100/50 px-2 py-1 rounded uppercase tracking-wider">
                            {{ $doc->type->document_type_name ?? 'Document' }}
                        </span>
                    </div>

                    <h4 class="font-bold text-premium mb-2 truncate" title="{{ $doc->document_title }}">
                        {{ $doc->document_title }}
                    </h4>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-6 flex-1">
                        {{ $doc->document_description }}
                    </p>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <span class="text-[10px] font-bold text-slate-400">
                            {{ \Carbon\Carbon::parse($doc->added_date)->format('M d, Y') }}
                        </span>
                        <a href="{{ asset('uploads/' . $doc->document_attachment) }}" target="_blank"
                            class="flex items-center gap-2 text-indigo-600 text-xs font-bold hover:text-indigo-800 transition-colors">
                            <span>View / Download</span>
                            <i class="fa-solid fa-arrow-down"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div
                        class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fa-solid fa-folder-open text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-400">No documents found</h3>
                    <p class="text-slate-300 text-sm mt-1">There are no documents uploaded in this category yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="pt-8">
                {{ $documents->links() }}
            </div>
        @endif

    </div>
@endsection
