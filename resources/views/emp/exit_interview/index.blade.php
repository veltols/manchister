@extends('layouts.app')

@section('title', 'Exit Interview')
@section('subtitle', 'Your feedback on your time with us')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">

        @if($interviews->count() > 0)
            <!-- List of Previous Interviews (Usually just one) -->
            <div class="premium-card p-8">
                <h3 class="text-xl font-display font-bold text-premium mb-6">Historical Records</h3>
                <div class="space-y-4" id="interviews-container">
                    @foreach($interviews as $interview)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700">Interview Ref #{{ $interview->interview_id }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ \Carbon\Carbon::parse($interview->interview_date)->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-600 text-[10px] font-bold uppercase tracking-wider">Submitted</span>
                        </div>
                    @endforeach
                    <!-- AJAX Pagination -->
                    <div id="interviews-pagination" class="pt-4"></div>
                </div>
            </div>
        @endif

        <!-- New Submission Form -->
        <div class="premium-card p-10">
            <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                <div class="w-14 h-14 rounded-2xl bg-brand-light/20 flex items-center justify-center text-brand-dark">
                    <i class="fa-solid fa-door-open text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Exit Assessment Form</h2>
                    <p class="text-sm text-slate-500">Please provide honest feedback about your journey at IQC Sense</p>
                </div>
            </div>

            <form action="{{ route('emp.exit_interview.store') }}" method="POST" class="space-y-10">
                @csrf

                <div class="space-y-12">
                    @foreach($questions as $question)
                        <div class="space-y-4">
                            <label class="block text-slate-700 font-bold text-lg leading-relaxed">
                                {{ $loop->iteration }}. {{ $question->question_text }}
                            </label>
                            <input type="hidden" name="question_ids[]" value="{{ $question->question_id }}">
                            <textarea name="answers[]" rows="3"
                                class="premium-input w-full bg-slate-50/50 focus:bg-white transition-all border-slate-200"
                                placeholder="Write your response here..." required></textarea>
                        </div>
                    @endforeach
                </div>

                <div class="pt-10 border-t border-slate-50">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Overall
                        Remarks</label>
                    <textarea name="remarks" rows="4" class="premium-input w-full"
                        placeholder="Any final thoughts or special messages?"></textarea>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="premium-button px-10 py-4">
                        <span>Submit Final Interview</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('emp.exit_interview.data') }}",
            containerSelector: '#interviews-container',
            paginationSelector: '#interviews-pagination',
            renderCallback: function(data) {
                let html = '';
                data.forEach(interview => {
                    const date = new Date(interview.interview_date).toLocaleDateString(undefined, {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    html += `
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700">Interview Ref #${interview.interview_id}</p>
                                    <p class="text-xs text-slate-400">${date}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-600 text-[10px] font-bold uppercase tracking-wider">Submitted</span>
                        </div>
                    `;
                });
                return html;
            }
        });
    </script>
@endsection
