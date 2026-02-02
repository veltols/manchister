@extends('layouts.app')

@section('title', 'New Provider')
@section('subtitle', 'Register External Training Provider')

@section('content')
    <div class="max-w-3xl mx-auto animate-fade-in-up">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <h2 class="font-bold text-lg text-premium">Registration Form</h2>
                <p class="text-sm text-slate-500">Enter the initial details for the Training Provider.</p>
            </div>

            <form action="{{ route('eqa.atps.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ATP
                            Name</label>
                        <input type="text" name="atp_name" class="premium-input w-full" required
                            placeholder="Full Legal Name of Institution">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Contact
                            Person</label>
                        <input type="text" name="contact_name" class="premium-input w-full" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone
                            Number</label>
                        <input type="text" name="atp_phone" class="premium-input w-full" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email
                            Address</label>
                        <input type="email" name="atp_email" class="premium-input w-full" required
                            placeholder="official@example.com">
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a href="{{ route('eqa.atps.index') }}"
                        class="py-3 px-6 rounded-xl text-slate-500 hover:text-slate-700 font-bold hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="premium-button px-8 py-3 shadow-lg shadow-indigo-500/20">Register
                        ATP</button>
                </div>
            </form>
        </div>

    </div>
@endsection
