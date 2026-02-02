@extends('layouts.app')

@section('title', 'Questions Bank')
@section('subtitle', 'Manage Audit Questions')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold font-display text-premium">Questions List</h2>
            <button onclick="document.getElementById('newQModal').showModal()" class="premium-button px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> Add Question
            </button>
        </div>

        <div class="premium-card overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="p-4 w-2/3">Question</th>
                        <th class="p-4">Added By</th>
                        <th class="p-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($questions as $q)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4 text-slate-700 font-medium">{{ $q->q_text }}</td>
                            <td class="p-4 text-slate-500 text-xs">
                                {{ $q->adder->first_name ?? '-' }}
                            </td>
                            <td class="p-4 text-slate-500 text-xs font-mono">
                                {{ $q->added_date }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $questions->links() }}
            </div>
        </div>
    </div>

    <!-- New Question Modal -->
    <dialog id="newQModal" class="modal rounded-2xl shadow-2xl p-6 w-full max-w-lg backdrop:bg-slate-900/50">
        <form action="{{ route('eqa.questions.store') }}" method="POST">
            @csrf
            <h3 class="font-bold text-lg mb-4 text-premium">Add New Question</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Question
                        Text</label>
                    <textarea name="q_text" rows="4" class="premium-input w-full" required
                        placeholder="Enter the audit question here..."></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button class="premium-button flex-1 py-3">Save Question</button>
                    <button type="button" onclick="document.getElementById('newQModal').close()"
                        class="py-3 px-6 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                </div>
            </div>
        </form>
    </dialog>
@endsection
