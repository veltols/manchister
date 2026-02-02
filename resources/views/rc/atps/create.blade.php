@extends('layouts.app')

@section('title', 'Add New ATP')
@section('subtitle', 'Register a new training provider')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('emp.ext.atps.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
        </div>

        <div class="premium-card p-10">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-50">
                <div class="w-14 h-14 rounded-2xl bg-brand-light/20 flex items-center justify-center text-brand-dark">
                    <i class="fa-solid fa-certificate text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">New Training Partner</h2>
                    <p class="text-sm text-slate-500">Enter the institution details below</p>
                </div>
            </div>

            <form action="{{ route('emp.ext.atps.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Institution
                        Name</label>
                    <input type="text" name="atp_name" class="premium-input w-full"
                        placeholder="e.g. Manchester Training Center" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Contact
                            Person</label>
                        <input type="text" name="contact_name" class="premium-input w-full" placeholder="Full Name"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone
                            Number</label>
                        <input type="text" name="atp_phone" class="premium-input w-full" placeholder="+971 50 000 0000">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Institution
                        Email</label>
                    <input type="email" name="atp_email" class="premium-input w-full" placeholder="contact@institution.com"
                        required>
                    <p class="text-xs text-slate-400 mt-2">A registration link will be sent to this email.</p>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="premium-button px-10 py-3">
                        <span>Register Partner</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
