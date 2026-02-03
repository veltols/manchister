@extends('layouts.app')

@section('title', 'User Profile')
@section('subtitle', 'View system user details')

@section('content')
    <div class="space-y-6 animate-fade-in-up md:grid md:grid-cols-3 md:gap-6 md:space-y-0">
        
        <!-- Left Column: Profile Card -->
        <div class="md:col-span-1 space-y-6">
            <div class="premium-card p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <div class="relative z-10 mt-12 mb-4">
                    <div class="w-24 h-24 rounded-full bg-white p-1 mx-auto shadow-xl">
                        <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-3xl font-bold text-indigo-600">
                             {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-slate-800">{{ $user->first_name }} {{ $user->last_name }}</h2>
                <p class="text-slate-500 font-medium mb-1">{{ $user->designation->designation_name ?? 'N/A' }}</p>
                <div class="flex justify-center mt-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} text-xs font-bold">
                        <i class="fa-solid fa-circle text-[8px]"></i>
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100 text-left space-y-3">
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">IQC ID</p>
                            <p class="font-mono font-medium">{{ $user->employee_no }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">Email</p>
                            <p class="font-medium truncate max-w-[180px]" title="{{ $user->employee_email }}">{{ $user->employee_email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">Department</p>
                            <p class="font-medium">{{ $user->department->department_name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm text-slate-600">
                         <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">Joined Date</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($user->joined_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="premium-card p-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Account Actions</h3>
                <div class="space-y-3">
                    <button class="w-full py-2.5 px-4 rounded-xl bg-slate-50 text-slate-600 font-semibold hover:bg-slate-100 hover:text-slate-800 transition-colors text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen"></i> Edit Profile
                    </button>
                    <button class="w-full py-2.5 px-4 rounded-xl bg-amber-50 text-amber-600 font-semibold hover:bg-amber-100 transition-colors text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-key"></i> Reset Password
                    </button>
                    <!-- Toggle Status -->
                     <button class="w-full py-2.5 px-4 rounded-xl text-white font-semibold transition-colors text-sm flex items-center justify-center gap-2 {{ $user->is_active ? 'bg-rose-500 hover:bg-rose-600' : 'bg-emerald-500 hover:bg-emerald-600' }}">
                         @if($user->is_active)
                            <i class="fa-solid fa-ban"></i> Deactivate User
                         @else
                             <i class="fa-solid fa-check"></i> Activate User
                         @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs/Details -->
        <div class="md:col-span-2 space-y-6">
            
            <div x-data="{ activeTab: 'assets' }" class="premium-card overflow-hidden min-h-[500px]">
                <div class="flex border-b border-slate-100">
                    <button @click="activeTab = 'assets'"
                        :class="activeTab === 'assets' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                        Assets
                    </button>
                    <button @click="activeTab = 'logs'"
                        :class="activeTab === 'logs' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                        Activity Logs
                    </button>
                </div>

                <!-- Assets Tab -->
                <div x-show="activeTab === 'assets'" class="p-0 animate-fade-in-up">
                    <div class="p-6">
                        <!-- Placeholder for user assets -->
                        <div class="text-center py-12">
                            <div class="w-16 h-16 rounded-full bg-slate-50 mx-auto flex items-center justify-center mb-3">
                                <i class="fa-solid fa-laptop text-2xl text-slate-300"></i>
                            </div>
                            <h3 class="text-slate-800 font-semibold mb-1">No Assets Assigned</h3>
                            <p class="text-sm text-slate-500">This user currently has no assets assigned.</p>
                        </div>
                    </div>
                </div>

                 <!-- Logs Tab -->
                <div x-show="activeTab === 'logs'" class="p-0 animate-fade-in-up" style="display: none;">
                    <div class="p-6">
                        <div class="text-center py-12">
                             <div class="w-16 h-16 rounded-full bg-slate-50 mx-auto flex items-center justify-center mb-3">
                                <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300"></i>
                            </div>
                             <h3 class="text-slate-800 font-semibold mb-1">No Activity Logs</h3>
                            <p class="text-sm text-slate-500">No recent activity found for this user.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
