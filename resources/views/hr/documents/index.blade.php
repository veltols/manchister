@extends('layouts.app')

@section('title', 'Documents')
@section('subtitle', 'HR documents and policies')

@section('content')
    <div class="space-y-6">
        @include('hr.partials.requests_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Documents & Policies</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $documents->total() }} total documents</p>
            </div>
            <button onclick="openModal('addDocModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Upload Document</span>
            </button>
        </div>

        <!-- Category Filter (Themed) -->
        <div class="premium-card p-2 w-fit">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('hr.documents.index') }}"
                    class="px-4 py-2 rounded-lg font-bold text-sm transition-all {{ !request('type_id') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-layer-group mr-2"></i>All Categories
                </a>
                @foreach($types as $type)
                    <a href="{{ route('hr.documents.index', ['type_id' => $type->document_type_id]) }}"
                        class="px-4 py-2 rounded-lg font-bold text-sm transition-all {{ request('type_id') == $type->document_type_id ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        <i
                            class="{{ $type->document_type_icon ?? 'fa-solid fa-file' }} mr-2"></i>{{ $type->document_type_name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($documents as $doc)
                <div class="premium-card p-6 relative group">
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <form action="{{ route('hr.documents.destroy', $doc->document_id) }}" method="POST"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-500 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>

                    <div class="flex items-start gap-4 mb-4">
                        <div
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md">
                            <i class="{{ $doc->type->document_type_icon ?? 'fa-solid fa-file-pdf' }} text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-premium line-clamp-2 mb-2" title="{{ $doc->document_title }}">
                                {{ $doc->document_title }}
                            </h3>
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium">
                                <i class="fa-solid fa-tag text-xs"></i>
                                {{ $doc->type->document_type_name ?? 'General' }}
                            </span>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ $doc->document_description }}</p>

                    <a href="{{ asset('uploads/' . $doc->document_attachment) }}" target="_blank"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border-2 border-indigo-200 text-indigo-600 font-semibold rounded-xl hover:bg-indigo-50 transition-colors">
                        <i class="fa-solid fa-download"></i>
                        Download
                    </a>
                </div>
            @empty
                <div class="col-span-full premium-card p-12">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                            <i class="fa-regular fa-folder-open text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No documents found</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($documents->hasPages())
            <div class="flex justify-center">
                {{ $documents->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

    <!-- Upload Modal -->
    <div class="modal" id="addDocModal">
        <div class="modal-backdrop" onclick="closeModal('addDocModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Upload Document</h2>
                <button onclick="closeModal('addDocModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('hr.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                        <input type="text" name="document_title" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                        <select name="document_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($types as $type)
                                <option value="{{ $type->document_type_id }}">{{ $type->document_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea name="document_description" rows="2"
                            class="premium-input w-full px-4 py-3 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Attachment</label>
                        <input type="file" name="document_attachment" class="premium-input w-full px-4 py-3 text-sm"
                            required>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addDocModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Upload</button>
                </div>
            </form>
        </div>
    </div>

@endsection