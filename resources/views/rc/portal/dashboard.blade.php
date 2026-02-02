@extends('layouts.app')

@section('title', 'Partner Portal')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="glass-panel p-8 text-center">
        <div class="w-20 h-20 bg-secondary text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
            <i class="fa-solid fa-handshake"></i>
        </div>
        <h1 class="text-3xl font-bold text-premium mb-2">Welcome, {{ $atp->atp_name_en }}</h1>
        <p class="text-gray-600 mb-8">This is your Partner Portal. You can manage your accreditation status and details here.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                <h3 class="font-bold text-lg text-secondary mb-2">Registration Status</h3>
                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-sm font-bold">Active</span>
            </div>
             <div class="bg-gray-50 p-6 rounded-lg border border-gray-100 cursor-pointer hover:bg-gray-100">
                <h3 class="font-bold text-lg text-gray-700 mb-2">Update Profile</h3>
                <p class="text-sm text-gray-500">Edit contact details and information.</p>
            </div>
        </div>
    </div>
</div>
@endsection
