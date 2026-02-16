@extends('layouts.app')

@section('title', 'New Information Request')
@section('subtitle', 'Evidence & Documentation Requirements')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-widest">New Information Request</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $atp->atp_name }}</p>
            </div>
            
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}" 
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Planner</span>
            </a>
        </div>

        <!-- Form Card -->
        <form action="{{ route('eqa.atps.store_info_request', $atp->atp_id) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden group/card">
                <!-- Card Header -->
                <div class="p-6 md:p-8 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20">
                                <i class="fa-solid fa-file-lines text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Required Evidence Items</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Add documentation requirements</p>
                            </div>
                        </div>
                        
                        <button type="button" onclick="addEvidenceRow()" 
                                class="premium-button bg-brand hover:bg-brand-dark shadow-md">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Item</span>
                        </button>
                    </div>
                </div>

                <!-- Evidence Container -->
                <div class="p-6 md:p-8">
                    <div id="evidence-container" class="space-y-4">
                        <!-- Rows will be added here -->
                    </div>
                    
                    <div id="empty-state" class="text-center py-12 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50/30">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 mx-auto mb-4">
                            <i class="fa-solid fa-inbox text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">No Evidence Items</h4>
                        <p class="text-xs text-slate-400 mt-1">Click "Add Item" to start building your request</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}" 
                   class="px-8 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95 shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="premium-button bg-brand hover:bg-brand-dark shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Submit Request</span>
                </button>
            </div>
        </form>
    </div>

<template id="evidence-row-template">
    <div class="evidence-row group relative animate-fade-in-up">
        <div class="flex items-start gap-4 p-5 rounded-xl border-2 border-slate-100 bg-white hover:border-brand/20 hover:shadow-md transition-all duration-300">
            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-brand/5 text-brand flex items-center justify-center text-xs font-black mt-1">
                <span class="row-number"></span>
            </div>
            <div class="flex-1">
                <textarea name="required_evidences[]" rows="4" 
                          placeholder="Describe the required evidence or documentation in detail..." 
                          required
                          class="w-full rounded-xl border-slate-200 text-sm font-medium placeholder:text-slate-300 focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all resize-none"></textarea>
            </div>
            <button type="button" onclick="removeEvidenceRow(this)" 
                    class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center">
                <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
        </div>
    </div>
</template>

<script>
    let rowCounter = 0;

    function addEvidenceRow() {
        const template = document.getElementById('evidence-row-template');
        const container = document.getElementById('evidence-container');
        const emptyState = document.getElementById('empty-state');
        
        const clone = template.content.cloneNode(true);
        rowCounter++;
        
        // Set row number
        const numberSpan = clone.querySelector('.row-number');
        numberSpan.textContent = rowCounter;
        
        container.appendChild(clone);
        
        // Hide empty state
        if (emptyState) {
            emptyState.style.display = 'none';
        }
        
        // Focus on the new textarea
        setTimeout(() => {
            const textareas = container.querySelectorAll('textarea');
            textareas[textareas.length - 1].focus();
        }, 100);
        
        updateRowNumbers();
    }

    function removeEvidenceRow(btn) {
        const row = btn.closest('.evidence-row');
        row.style.opacity = '0';
        row.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            row.remove();
            updateRowNumbers();
            
            // Show empty state if no rows
            const container = document.getElementById('evidence-container');
            const emptyState = document.getElementById('empty-state');
            if (container.children.length === 0 && emptyState) {
                emptyState.style.display = 'block';
            }
        }, 200);
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('.evidence-row');
        rows.forEach((row, index) => {
            const numberSpan = row.querySelector('.row-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    // Add initial row on page load
    document.addEventListener('DOMContentLoaded', () => {
        addEvidenceRow();
    });
</script>

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.3s ease-out;
    }
</style>
@endsection
