@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="bg-white border-b border-slate-100 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}" 
                       class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">New Information Request</h1>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $atp->atp_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto bg-slate-50/50 p-6">
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('eqa.atps.store_info_request', $atp->atp_id) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800">Request Evidence</h3>
                        <button type="button" onclick="addEvidenceRow()" 
                                class="px-4 py-2 rounded-xl bg-brand/5 text-brand font-bold text-xs uppercase tracking-widest hover:bg-brand hover:text-white transition-all">
                            <i class="fa-solid fa-plus mr-2"></i> Add Item
                        </button>
                    </div>

                    <div id="evidence-container" class="space-y-4">
                        <!-- Rows will be added here -->
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 rounded-xl bg-brand text-white font-bold text-sm uppercase tracking-widest shadow-lg shadow-brand/20 hover:bg-brand-dark hover:shadow-xl transition-all active:scale-95">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="evidence-row-template">
    <div class="evidence-row group relative">
        <textarea name="required_evidences[]" rows="3" placeholder="Describe the required evidence or documentation..." 
                  class="w-full rounded-xl border-slate-200 text-sm focus:border-brand focus:ring-brand"></textarea>
        <button type="button" onclick="removeEvidenceRow(this)" 
                class="absolute top-3 right-3 text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    </div>
</template>

<script>
    function addEvidenceRow() {
        const template = document.getElementById('evidence-row-template');
        const container = document.getElementById('evidence-container');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }

    function removeEvidenceRow(btn) {
        btn.closest('.evidence-row').remove();
    }

    // Add initial row
    document.addEventListener('DOMContentLoaded', () => {
        addEvidenceRow();
    });
</script>
@endsection
