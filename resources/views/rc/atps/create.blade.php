@extends('layouts.app')

@section('title', 'New Training Provider')
@section('subtitle', 'Register a new accredited training provider')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('rc.atps.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2">
                <i class="fa-solid fa-arrow-left"></i>Back to List
            </a>
            <h1 class="text-2xl font-display font-bold text-slate-800">New Training Provider</h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="premium-card p-8">
        <form action="{{ route('rc.atps.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Helper: Left Column (Institution Details) -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">
                            <i class="fa-solid fa-building text-indigo-600 mr-2"></i>Institution Details
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Helper: Name -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Institution Name</label>
                                <input type="text" name="atp_name" class="premium-input w-full px-4 py-3 text-sm" placeholder="e.g. Technical Training Institute" required>
                            </div>

                            <!-- Helper: Category -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                                <select name="atp_category_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->atp_category_id }}">
                                            {{ $cat->category_name ?? $cat->atp_category_name ?? 'Category ' . $cat->atp_category_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Helper: Type (New) -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                                <select name="atp_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->atp_type_id }}">
                                            {{ $type->atp_type_name ?? 'Type ' . $type->atp_type_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Helper: Emirate (New) -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Emirate / City</label>
                                <select name="emirate_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                    <option value="">Select Emirate</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->city_id }}">
                                            {{ $city->city_name_en ?? $city->city_name ?? 'City ' . $city->city_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Helper: Right Column (Contact Details) -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">
                            <i class="fa-solid fa-address-card text-purple-600 mr-2"></i>Contact Details
                        </h3>

                        <div class="space-y-4">
                            <!-- Helper: Email -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Main Email Address</label>
                                <div class="relative">
                                    <i class="fa-solid fa-envelope absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="email" name="atp_email" class="premium-input w-full pl-11 pr-4 py-3 text-sm" placeholder="info@example.com" required>
                                </div>
                            </div>

                            <!-- Helper: Phone -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                                <div class="relative">
                                    <i class="fa-solid fa-phone absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="text" name="atp_phone" class="premium-input w-full pl-11 pr-4 py-3 text-sm" placeholder="+971 50 123 4567">
                                </div>
                            </div>
                            
                            <!-- Helper: Contact Person Name -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Contact Person Name</label>
                                <div class="relative">
                                    <i class="fa-solid fa-user absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="text" name="contact_name" class="premium-input w-full pl-11 pr-4 py-3 text-sm" placeholder="Full Name" required>
                                </div>
                            </div>

                            <!-- Helper: Contact Designation (New) -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Contact Designation</label>
                                <div class="relative">
                                    <i class="fa-solid fa-id-badge absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="text" name="contact_name_designation" class="premium-input w-full pl-11 pr-4 py-3 text-sm" placeholder="e.g. Training Manager" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('rc.atps.index') }}" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-check-circle mr-2"></i>Create Provider
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
