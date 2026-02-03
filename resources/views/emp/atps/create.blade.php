@extends('layouts.app')

@section('title', 'Add New Training Provider')
@section('subtitle', 'Register a new Educational Institution')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <div class="premium-card">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand/5 flex items-center justify-center text-brand border border-brand/10">
                    <i class="fa-solid fa-plus text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-premium">New Registration</h3>
                    <p class="text-xs text-slate-400">Please fill in all the required information below.</p>
                </div>
            </div>
            <a href="{{ route('emp.atps.index') }}" class="text-xs font-bold text-slate-400 hover:text-brand transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                Back to List
            </a>
        </div>

        <form action="{{ route('emp.atps.store') }}" method="POST" class="p-8 space-y-8">
            @csrf
            
            <!-- Institution Info -->
            <div class="space-y-6">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-building-columns text-[10px]"></i>
                    Institution Information
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group col-span-full">
                        <label class="premium-label">Institution Name</label>
                        <input type="text" name="atp_name" required value="{{ old('atp_name') }}"
                               class="premium-input w-full" placeholder="Enter registration name">
                        @error('atp_name') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="premium-label">Category</label>
                        <select name="atp_category_id" required class="premium-input w-full">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->atp_category_id }}" {{ old('atp_category_id') == $category->atp_category_id ? 'selected' : '' }}>
                                    {{ $category->atp_category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('atp_category_id') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="premium-label">Type</label>
                        <select name="atp_type_id" required class="premium-input w-full">
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->atp_type_id }}" {{ old('atp_type_id') == $type->atp_type_id ? 'selected' : '' }}>
                                    {{ $type->atp_type_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('atp_type_id') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Details -->
            <div class="space-y-6 pt-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-[10px]"></i>
                    Contact Person
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="premium-label">Full Name</label>
                        <input type="text" name="contact_name" required value="{{ old('contact_name') }}"
                               class="premium-input w-full" placeholder="Person in charge">
                        @error('contact_name') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="premium-label">Designation</label>
                        <input type="text" name="contact_name_designation" required value="{{ old('contact_name_designation') }}"
                               class="premium-input w-full" placeholder="Job Title">
                        @error('contact_name_designation') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="premium-label">Email Address</label>
                        <input type="email" name="atp_email" required value="{{ old('atp_email') }}"
                               class="premium-input w-full" placeholder="institution@example.com">
                        @error('atp_email') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="premium-label">Phone Number</label>
                        <input type="text" name="atp_phone" required value="{{ old('atp_phone') }}"
                               class="premium-input w-full" placeholder="+123 456 789">
                        @error('atp_phone') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="space-y-6 pt-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-[10px]"></i>
                    Location Details
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group col-span-full">
                        <label class="premium-label">Emirate / City</label>
                        <select name="emirate_id" required class="premium-input w-full">
                            <option value="">Select Location</option>
                            @foreach($emirates as $emirate)
                                <option value="{{ $emirate->city_id }}" {{ old('emirate_id') == $emirate->city_id ? 'selected' : '' }}>
                                    {{ $emirate->city_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('emirate_id') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-8 mt-8 border-t border-slate-50 flex items-center justify-end gap-4">
                <button type="reset" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-400 hover:text-slate-600 transition-all uppercase tracking-widest">
                    Reset Form
                </button>
                <button type="submit" class="premium-button px-10 py-3 shadow-lg hover:shadow-brand/20 transition-all uppercase tracking-widest">
                    Complete Registration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
