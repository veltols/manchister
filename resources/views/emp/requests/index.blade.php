@extends('layouts.app')

@section('title', 'Request Center')
@section('subtitle', 'Manage your leaves, permissions, and document requests')

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        <!-- Welcome Header for Request Center -->
        <div class="premium-card p-8 bg-white border-l-4 border-brand-dark relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-brand-dark/5 rounded-full -mr-32 -mt-32"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-display font-bold text-premium">What do you need today?</h2>
                <p class="text-slate-500 mt-2 max-w-2xl">Access all your HR services in one place. Request time off, check
                    your attendance, or apply for official company documents.</p>
            </div>
        </div>

        <!-- Hub Icons Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Leaves -->
            <a href="{{ route('emp.leaves.index') }}"
                class="hub-card group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                    <i class="fa-solid fa-person-running text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-premium mb-2">Leaves</h3>
                <p class="text-sm text-slate-500">Apply for annual, sick, or bereavement leave and track approvals.</p>
                <div class="mt-6 flex items-center text-indigo-600 font-bold text-sm uppercase tracking-widest">
                    <span>View My Leaves</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Permissions -->
            <a href="{{ route('emp.permissions.index') }}"
                class="hub-card group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500">
                    <i class="fa-solid fa-clock text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-premium mb-2">Permissions</h3>
                <p class="text-sm text-slate-500">Request a few hours off for personal errands or medical appointments.</p>
                <div class="mt-6 flex items-center text-amber-600 font-bold text-sm uppercase tracking-widest">
                    <span>Request hours</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Attendance -->
            <a href="{{ route('emp.attendance.index') }}"
                class="hub-card group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    <i class="fa-solid fa-calendar-check text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-premium mb-2">Attendance</h3>
                <p class="text-sm text-slate-500">Check your daily clock-in and clock-out logs and monthly summarize.</p>
                <div class="mt-6 flex items-center text-blue-600 font-bold text-sm uppercase tracking-widest">
                    <span>My History</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Support Services -->
            <a href="{{ route('emp.ss.index') }}"
                class="hub-card group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 mb-6 group-hover:bg-teal-600 group-hover:text-white transition-all duration-500">
                    <i class="fa-solid fa-handshake-angle text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-premium mb-2">Service Requests</h3>
                <p class="text-sm text-slate-500">Request general support or services from other employees.</p>
                <div class="mt-6 flex items-center text-teal-600 font-bold text-sm uppercase tracking-widest">
                    <span>View Requests</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Disciplinary Actions -->
            <a href="{{ route('emp.da.index') }}"
                class="hub-card group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 mb-6 group-hover:bg-red-600 group-hover:text-white transition-all duration-500">
                    <i class="fa-solid fa-file-shield text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-premium mb-2">Disciplinary</h3>
                <p class="text-sm text-slate-500">Review formal warnings or conduct-related records.</p>
                <div class="mt-6 flex items-center text-red-600 font-bold text-sm uppercase tracking-widest">
                    <span>My Records</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- External Communications -->
            <a href="{{ route('emp.communications.index') }}"
                class="hub-card group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500">
                    <i class="fa-solid fa-earth-americas text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-premium mb-2">Communications</h3>
                <p class="text-sm text-slate-500">Manage formal information sharing with external entities.</p>
                <div class="mt-6 flex items-center text-orange-600 font-bold text-sm uppercase tracking-widest">
                    <span>Share Info</span>
                    <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <!-- Document Requests -->
            <div class="hub-card bg-slate-50 p-6 rounded-3xl border border-slate-200 border-dashed relative">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-6">
                    <i class="fa-solid fa-file-contract text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-400 mb-2">HR Documents</h3>
                <p class="text-sm text-slate-400 italic">Coming soon: Request NOCs, Salary Certificates, and more documents.
                </p>
            </div>

        </div>

        <!-- Quick Links to Document Types -->
        <div class="premium-card p-6">
            <h3 class="text-lg font-display font-bold text-premium mb-6">Available Document Types</h3>
            <div class="flex flex-wrap gap-4">
                @foreach($documentTypes as $type)
                    <div
                        class="px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3 hover:bg-white hover:shadow-md transition-all">
                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-brand-dark">
                            <i class="{{ $type->document_type_icon ?? 'fa-solid fa-file' }}"></i>
                        </div>
                        <span class="font-bold text-slate-700 text-sm">{{ $type->document_type_name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <style>
        .hub-card {
            cursor: pointer;
        }
    </style>
@endsection
