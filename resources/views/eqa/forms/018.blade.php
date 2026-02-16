@extends('layouts.app')

@section('title', 'Form 018')
@section('subtitle', 'IQA Interview Questions')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-user-shield text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">IQA Interview Protocol</p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'interim']) }}"
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Audit</span>
            </a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '018', 'atp_id' => $atp->atp_id]) }}" method="POST"
            class="space-y-8">
            @csrf

            <!-- IQA Name -->
            <div class="premium-card p-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">IQA
                        Name</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                        <input type="text" name="iqa_name"
                            class="premium-input w-full pl-11 focus:border-brand focus:ring-brand/5"
                            value="{{ $formData->iqa_name ?? '' }}" placeholder="Enter name of IQA interviewed...">
                    </div>
                </div>
            </div>

            <!-- Q&A Section -->
            <div class="premium-card p-0 overflow-hidden">
                <div
                    class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50/50 to-white">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest border-l-4 border-l-brand pl-4">
                        Interview Questions</h3>
                    <div
                        class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest border border-slate-200">
                        IQA Protocol
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <div id="qa-container" class="space-y-6">
                        @php
                            $questions = isset($formData->questions) ? json_decode($formData->questions, true) : (isset($formData->questions) ? [] : ['']);
                            $answers = isset($formData->answers) ? json_decode($formData->answers, true) : (isset($formData->answers) ? [] : ['']);
                            if (empty($questions))
                                $questions = [''];
                        @endphp

                        @foreach($questions as $index => $question)
                            <div
                                class="qa-row relative group bg-slate-50/50 p-6 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all duration-300">
                                <button type="button" onclick="removeRow(this)"
                                    class="absolute top-4 right-4 w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>

                                <div class="grid grid-cols-1 gap-6">
                                    <div class="space-y-2">
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Question</label>
                                        <textarea name="questions[]" rows="2"
                                            class="premium-input w-full resize-none font-medium text-slate-700 focus:border-brand focus:ring-brand/5"
                                            placeholder="Type question here...">{{ $question }}</textarea>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Response</label>
                                        <textarea name="answers[]" rows="3"
                                            class="premium-input w-full resize-none bg-white focus:border-brand focus:ring-brand/5"
                                            placeholder="Record response here...">{{ $answers[$index] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center">
                        <button type="button" onclick="addQaRow()"
                            class="w-full md:w-auto px-8 py-3.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.98] group/add">
                            <i class="fa-solid fa-plus mr-2 group-hover/add:rotate-90 transition-transform"></i>
                            <span>Add Question</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="flex items-center justify-between bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-file-signature text-brand"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Formal Interview Record</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="premium-button px-10 py-3.5 text-sm shadow-xl shadow-brand/30">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Save Interview
                    </button>
                </div>
            </div>

        </form>
    </div>

    <script>
        function addQaRow() {
            const container = document.getElementById('qa-container');
            const row = document.createElement('div');
            row.className = 'qa-row relative group bg-slate-50/50 p-6 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all duration-300 animate-fade-in-up';
            row.innerHTML = `
                    <button type="button" onclick="removeRow(this)" class="absolute top-4 right-4 w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Question</label>
                            <textarea name="questions[]" rows="2" class="premium-input w-full resize-none font-medium text-slate-700 focus:border-brand focus:ring-brand/5" placeholder="Type question here..."></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Response</label>
                            <textarea name="answers[]" rows="3" class="premium-input w-full resize-none bg-white focus:border-brand focus:ring-brand/5" placeholder="Record response here..."></textarea>
                        </div>
                    </div>
                `;
            container.appendChild(row);
        }

        function removeRow(btn) {
            const row = btn.closest('.qa-row');
            if (document.querySelectorAll('.qa-row').length > 1) {
                row.remove();
            } else {
                row.querySelectorAll('textarea').forEach(input => input.value = '');
            }
        }
    </script>
@endsection