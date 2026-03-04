@extends('layouts.app')

@section('title', 'Add New Training Provider')
@section('subtitle', 'Register a new Accredited Training Provider')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">New Training Provider</h2>
                <p class="text-sm text-slate-500 mt-1">Fill in all required fields to register a new ATP.</p>
            </div>
            <a href="{{ route('emp.atps.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="premium-card p-4 border-l-4 border-red-400 flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-red-400 mt-0.5 shrink-0"></i>
                <div>
                    <p class="text-sm font-bold text-red-600">Please fix the errors below:</p>
                    <ul class="mt-1 list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="premium-card overflow-hidden">

            {{-- Card Header --}}
            <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                <div class="w-9 h-9 rounded-xl bg-brand/10 flex items-center justify-center text-brand">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-700">Registration Details</h3>
                    <p class="text-[11px] text-slate-400">All fields marked with <span class="text-red-500">*</span> are
                        required</p>
                </div>
            </div>

            <form action="{{ route('emp.atps.store') }}" method="POST" class="p-8">
                @csrf

                <div class="space-y-6">

                    {{-- ── Field 1: Institution Name (full width) ─────────────── --}}
                    <div>
                        <label class="premium-label">
                            Institution Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="atp_name" id="atp_name" required value="{{ old('atp_name') }}"
                            class="premium-input w-full @error('atp_name') border-red-400 ring-1 ring-red-400 @enderror"
                            placeholder="Enter institution name">
                        @error('atp_name')
                            <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ── Fields 2 & 3: Contact Person + Designation (half/half) ─ --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="premium-label">
                                Contact Person <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="contact_name" id="contact_name" required
                                value="{{ old('contact_name') }}"
                                class="premium-input w-full @error('contact_name') border-red-400 ring-1 ring-red-400 @enderror"
                                placeholder="Full name">
                            @error('contact_name')
                                <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="premium-label">
                                Contact Person Designation <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="contact_name_designation" id="contact_name_designation" required
                                value="{{ old('contact_name_designation') }}"
                                class="premium-input w-full @error('contact_name_designation') border-red-400 ring-1 ring-red-400 @enderror"
                                placeholder="e.g. IQA Manager">
                            @error('contact_name_designation')
                                <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Fields 4 & 5: Email + Phone (half/half) ─────────────── --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="premium-label">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="atp_email" id="atp_email" required value="{{ old('atp_email') }}"
                                class="premium-input w-full @error('atp_email') border-red-400 ring-1 ring-red-400 @enderror"
                                placeholder="institution@example.com">
                            @error('atp_email')
                                <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="premium-label">
                                Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="atp_phone" id="atp_phone" required value="{{ old('atp_phone') }}"
                                class="premium-input w-full @error('atp_phone') border-red-400 ring-1 ring-red-400 @enderror"
                                placeholder="e.g. 0501234567" inputmode="numeric"
                                oninput="this.value=this.value.replace(/[^0-9+\s\-]/g,'')">
                            @error('atp_phone')
                                <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Field 6: Emirate (full width) ────────────────────────── --}}
                    <div>
                        <label class="premium-label">
                            Emirate <span class="text-red-500">*</span>
                        </label>
                        <select name="emirate_id" id="emirate_id" required
                            class="premium-input w-full @error('emirate_id') border-red-400 ring-1 ring-red-400 @enderror">
                            <option value="">— Please Select —</option>
                            @foreach($emirates as $emirate)
                                <option value="{{ $emirate->city_id }}" {{ old('emirate_id') == $emirate->city_id ? 'selected' : '' }}>
                                    {{ $emirate->city_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('emirate_id')
                            <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ── Fields 7 & 8: Category + Type (half/half) ───────────── --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="premium-label">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="atp_category_id" id="atp_category_id" required
                                class="premium-input w-full @error('atp_category_id') border-red-400 ring-1 ring-red-400 @enderror">
                                <option value="">— Please Select —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->atp_category_id }}" {{ old('atp_category_id') == $cat->atp_category_id ? 'selected' : '' }}>
                                        {{ $cat->atp_category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('atp_category_id')
                                <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="premium-label">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select name="atp_type_id" id="atp_type_id" required
                                class="premium-input w-full @error('atp_type_id') border-red-400 ring-1 ring-red-400 @enderror">
                                <option value="">— Please Select —</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->atp_type_id }}" {{ old('atp_type_id') == $type->atp_type_id ? 'selected' : '' }}>
                                        {{ $type->atp_type_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('atp_type_id')
                                <p class="text-[10px] text-red-500 mt-1.5 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Divider ──────────────────────────────────────────────── --}}
                    <hr class="border-slate-100">

                    {{-- ── Form Actions ─────────────────────────────────────────── --}}
                    <div class="flex items-center justify-end gap-4 pt-2">
                        <button type="reset"
                            class="px-6 py-3 rounded-xl text-sm font-bold text-slate-400 hover:text-slate-600 transition-all uppercase tracking-widest">
                            Reset
                        </button>
                        <button type="submit" id="submitBtn"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200 uppercase tracking-widest text-sm">
                            <i class="fa-solid fa-check"></i>
                            Add
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection