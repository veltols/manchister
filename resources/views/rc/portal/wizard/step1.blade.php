@extends('layouts.app')

@section('title', 'Registration Wizard')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <div class="glass-panel p-8">
        <h1 class="text-2xl font-bold text-premium mb-6">Initial Registration Form</h1>
        
        <form action="{{ route('rc.portal.wizard.submit1') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div class="p-4 bg-yellow-50 text-yellow-800 border-l-4 border-yellow-400 rounded">
                    Please complete the initial information to proceed with your accreditation request.
                </div>
                
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                     <input type="text" value="{{ $atp->atp_name_en }}" class="w-full border rounded-md p-2 bg-gray-100" readonly>
                </div>
                
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Brief Description of Activities</label>
                     <textarea name="description" rows="4" class="w-full border rounded-md p-2 bg-gray-50 outline-none" placeholder="Describe your training services..."></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-secondary text-white px-6 py-2 rounded shadow hover:bg-secondary-hover">
                    Submit & Continue <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
