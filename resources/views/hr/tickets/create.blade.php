@extends('layouts.app')

@section('title', 'New Ticket')
@section('subtitle', 'Submit a support request')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('hr.tickets.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
        </div>

        <div class="premium-card p-10">
            <h2 class="text-2xl font-display font-bold text-premium mb-6">Details</h2>

            <form action="{{ route('hr.tickets.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Subject</label>
                    <input type="text" name="ticket_subject" class="premium-input w-full"
                        placeholder="Brief summary of the issue..." required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Category</label>
                        <div class="relative">
                            <select name="category_id" class="premium-input w-full appearance-none" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Priority</label>
                        <div class="relative">
                            <select name="priority_id" class="premium-input w-full appearance-none" required>
                                <option value="">Select Priority</option>
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri->priority_id }}">{{ $pri->priority_name }}</option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="ticket_description" rows="6" class="premium-input w-full"
                        placeholder="Detailed description of your request..." required></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" class="premium-button w-full py-3">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
