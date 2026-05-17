@extends('layouts.app')

@section('title', 'Communication Record')
@section('subtitle', $request->communication_code)

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

        @if($request->modification_notes && ($request->is_approved_1 == 3 || $request->is_approved_2 == 3))
            <div class="premium-card p-8 bg-amber-50 border-l-8 border-amber-500 shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-200 flex items-center justify-center text-amber-700 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-amber-800 mb-1">Action Required: Modification Requested</h3>
                        <p class="text-amber-700 font-medium italic leading-relaxed">"{{ $request->modification_notes }}"</p>
                        <p class="text-[10px] text-amber-500 font-bold uppercase mt-3 italic tracking-wider">Please update the request and resubmit for review.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Top Card -->
        <div class="premium-card p-10 bg-white border-l-8 border-teal-500 shadow-xl">
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div class="space-y-4 flex-1">
                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1 bg-teal-50 text-teal-700 rounded-lg font-mono text-sm font-bold">{{ $request->communication_code }}</span>
                        <span class="text-slate-300">•</span>
                        <span
                            class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $request->type->communication_type_name ?? '-' }}</span>
                    </div>
                    <h1 class="text-4xl font-display font-bold text-premium leading-tight">
                        {{ $request->communication_subject }}
                    </h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $request->priority == 'high' ? 'bg-red-100 text-red-700' : ($request->priority == 'medium' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700') }}">
                            {{ $request->priority }} Priority
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $request->confidentiality == 'restricted' ? 'bg-purple-100 text-purple-700' : ($request->confidentiality == 'confidential' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700') }}">
                            {{ $request->confidentiality }}
                        </span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-building text-slate-400"></i>
                            <span class="text-slate-600 font-bold">{{ $request->external_party_name }}</span>
                        </div>
                        <div class="flex items-center gap-2 border-l border-slate-100 pl-6">
                            <i class="fa-solid fa-calendar text-slate-400"></i>
                            <span class="text-slate-600 font-medium">{{ $request->requested_date }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="flex flex-col items-center gap-4 bg-slate-50 p-6 rounded-3xl border border-slate-100 min-w-[200px]">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Current Approval
                        Status</span>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl shadow-lg mb-2"
                        style="background: #{{ $request->status->status_color ?? '64748b' }};">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <span
                        class="font-bold text-slate-800 text-lg">{{ $request->status->communication_status_name ?? 'Pending' }}</span>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Description -->
            <div class="premium-card p-8">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-align-left text-teal-500"></i>
                    Description / Summary
                </h3>
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed font-medium">
                    {!! nl2br(e($request->communication_description)) !!}
                </div>
            </div>

            <!-- Purpose -->
            <div class="premium-card p-8 bg-indigo-50/30">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-bullseye text-indigo-500"></i>
                    Purpose / Reason
                </h3>
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed font-medium italic">
                    {!! nl2br(e($request->communication_purpose)) !!}
                </div>
            </div>
        </div>

        <!-- Information & Attachments -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Information Shared -->
            <div class="premium-card p-8 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-share-nodes text-teal-500"></i>
                    Information Shared
                </h3>
                <div class="p-6 bg-white rounded-2xl border border-slate-100 text-slate-600">
                    {!! nl2br(e($request->information_shared)) !!}
                </div>
            </div>

            <!-- Attachments -->
            <div class="premium-card p-8">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-paperclip text-teal-500"></i>
                    Source Attachments
                </h3>
                <div class="space-y-3">
                    @forelse($request->attachments as $file)
                        <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-slate-100 hover:border-brand hover:shadow-md transition-all group cursor-pointer" 
                             onclick="window.previewRemoteFile('{{ asset('storage/' . $file->file_path) }}', '{{ $file->file_name }}')">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-brand/10 group-hover:text-brand transition-colors">
                                    <i class="fa-solid fa-file-pdf text-xl"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700 truncate max-w-[150px]">{{ $file->file_name }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold">{{ strtoupper($file->file_type) }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-brand font-bold uppercase opacity-0 group-hover:opacity-100 transition-opacity">Preview</span>
                                <i class="fa-solid fa-eye text-slate-300 group-hover:text-brand"></i>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 italic text-sm">
                            No attachments found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Approval Progress (Legacy placeholder) -->
        <div class="premium-card p-8">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8 text-center">Multi-Level Approval
                Flow</h3>
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16">
                <!-- Stage 1 -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full {{ $request->is_approved_1 == 1 ? 'bg-green-500 text-white' : ($request->is_approved_1 == 2 ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-300') }} flex items-center justify-center shadow-sm relative">
                        <i class="fa-solid fa-user-tie"></i>
                        @if($request->is_approved_1 == 0)
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-orange-500 border-2 border-white rounded-full animate-pulse"></div>
                        @endif
                    </div>
                    <span
                        class="text-[10px] font-bold {{ $request->is_approved_1 == 1 ? 'text-green-600' : 'text-slate-400' }} uppercase">Line Manager</span>
                </div>

                <div class="h-0.5 w-12 bg-slate-100 hidden md:block"></div>

                <!-- Stage 2 -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full {{ $request->is_approved_2 == 1 ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-300' }} flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold {{ $request->is_approved_2 == 1 ? 'text-green-600' : 'text-slate-400' }} uppercase">GM</span>
                </div>

                <div class="h-0.5 w-12 bg-slate-100 hidden md:block"></div>

                <!-- Stage 3 -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full {{ $request->communication_status_id == 3 ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-300' }} flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold {{ $request->communication_status_id == 3 ? 'text-indigo-600' : 'text-slate-400' }} uppercase">Liaison</span>
                </div>

                <div class="h-0.5 w-12 bg-slate-100 hidden md:block"></div>

                <!-- Final -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full {{ $request->communication_status_id == 4 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-300' }} flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-earth-americas"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold {{ $request->communication_status_id == 4 ? 'text-blue-600' : 'text-slate-400' }} uppercase">External Entity</span>
                </div>
            </div>
        </div>
        <!-- Line Manager Review Action -->
        @if(auth()->user()->employee && (int)auth()->user()->employee->employee_id === (int)$request->approval_id_1 && $request->is_approved_1 == 0)
            <div class="premium-card p-10 bg-indigo-50/50 border-2 border-indigo-100 shadow-xl">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-user-check text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-display font-bold text-premium">Line Manager Review</h2>
                        <p class="text-sm text-slate-500">Please provide your decision on this outbound communication request.</p>
                    </div>
                </div>

                <form id="lmReviewForm" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Reviewer Comments / Internal Notes</label>
                        <textarea name="notes" id="lmNotes" rows="4" class="premium-input w-full bg-white border-slate-200" placeholder="Enter reason for approval, modification, or rejection..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button type="button" onclick="submitLmReview('approve')" class="group px-6 py-6 bg-emerald-600 text-white rounded-3xl font-bold text-xs uppercase shadow-xl shadow-emerald-200 hover:scale-105 transition-all">
                            <i class="fa-solid fa-check-circle mb-3 block text-2xl group-hover:scale-125 transition-transform"></i>
                            Approve & Forward to GM
                        </button>
                        <button type="button" onclick="submitLmReview('modify')" class="group px-6 py-6 bg-amber-500 text-white rounded-3xl font-bold text-xs uppercase shadow-xl shadow-amber-200 hover:scale-105 transition-all">
                            <i class="fa-solid fa-pen-to-square mb-3 block text-2xl group-hover:scale-125 transition-transform"></i>
                            Request Modification
                        </button>
                        <button type="button" onclick="submitLmReview('reject')" class="group px-6 py-6 bg-rose-600 text-white rounded-3xl font-bold text-xs uppercase shadow-xl shadow-rose-200 hover:scale-105 transition-all">
                            <i class="fa-solid fa-ban mb-3 block text-2xl group-hover:scale-125 transition-transform"></i>
                            Reject Request
                        </button>
                    </div>
                </form>
            </div>

            <script>
                function submitLmReview(action) {
                    const notes = document.getElementById('lmNotes').value;
                    const form = document.getElementById('lmReviewForm');
                    
                    if ((action === 'modify' || action === 'reject') && !notes) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Comments Required',
                            text: 'Please provide comments explaining why modification or rejection is required.',
                            confirmButtonColor: '#6366f1'
                        });
                        return;
                    }
                    
                    let confirmTitle = '';
                    let confirmText = '';
                    let confirmColor = '';
                    let route = '';

                    if (action === 'approve') {
                        confirmTitle = 'Approve Request?';
                        confirmText = 'This will forward the request to the GM for final review.';
                        confirmColor = '#10b981';
                        route = "{{ route('emp.lm.communications.approve', $request->communication_id) }}";
                    } else if (action === 'modify') {
                        confirmTitle = 'Request Modification?';
                        confirmText = 'The employee will be notified to update and resubmit the request.';
                        confirmColor = '#f59e0b';
                        route = "{{ route('emp.lm.communications.modify', $request->communication_id) }}";
                    } else {
                        confirmTitle = 'Reject Request?';
                        confirmText = 'This will permanently reject this communication request.';
                        confirmColor = '#ef4444';
                        route = "{{ route('emp.lm.communications.reject', $request->communication_id) }}";
                    }

                    Swal.fire({
                        title: confirmTitle,
                        text: confirmText,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Yes, Proceed'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.action = route;
                            form.submit();
                        }
                    });
                }
            </script>
        @endif

    </div>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
@endsection
