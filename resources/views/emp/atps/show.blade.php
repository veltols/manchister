@extends('layouts.app')

@section('title', 'ATP Details')
@section('subtitle', $atp->atp_name)

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up" x-data="{ activeTab: 'apps' }">
    <!-- ATP Header & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Stats & Info -->
        <div class="lg:col-span-3 space-y-8">
            <div class="premium-card p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 rounded-3xl bg-brand/5 flex items-center justify-center text-brand border border-brand/10 shadow-inner">
                            <i class="fa-solid fa-building-columns text-4xl"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $atp->atp_ref }}</div>
                            <h2 class="text-3xl font-bold text-premium tracking-tight">{{ $atp->atp_name }}</h2>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-sm font-medium text-slate-500 flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-brand"></i>
                                    {{ $atp->emirate->city_name ?? 'N/A' }}
                                </span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-sm font-medium text-slate-500">
                                    Added on {{ \Carbon\Carbon::parse($atp->added_date)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3">
                        <span class="px-5 py-2 rounded-2xl text-xs font-bold uppercase tracking-widest
                            {{ $atp->atp_status_id == 1 ? 'bg-amber-50 text-amber-600 border border-amber-100' : 
                               ($atp->atp_status_id == 4 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-blue-50 text-blue-600 border border-blue-100') }}">
                            {{ $atp->status->atp_status_name ?? 'Unknown' }}
                        </span>
                        <div class="text-[10px] font-bold text-slate-400 uppercase flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-brand"></i>
                            Phase Verified
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="premium-card p-6 flex items-center gap-4 border-l-4 border-l-brand">
                    <div class="w-12 h-12 rounded-2xl bg-brand/5 flex items-center justify-center text-brand">
                        <i class="fa-solid fa-file-contract text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Apps</div>
                        <div class="text-xl font-bold text-premium">{{ count($apps) }} Active</div>
                    </div>
                </div>
                <div class="premium-card p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-user-graduate text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Learners</div>
                        <div class="text-xl font-bold text-premium">{{ $leRecords->count() }} Records</div>
                    </div>
                </div>
                <div class="premium-card p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Audit Log</div>
                        <div class="text-xl font-bold text-premium">{{ $logs->count() }} Actions</div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex flex-wrap items-center gap-2 p-1 bg-slate-100/50 rounded-2xl w-full">
                <button @click="activeTab = 'apps'" 
                        :class="activeTab === 'apps' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i>
                    Applications
                </button>
                <button @click="activeTab = 'renewals'" 
                        :class="activeTab === 'renewals' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-rotate"></i>
                    Renewals
                </button>
                <button @click="activeTab = 'cancellations'" 
                        :class="activeTab === 'cancellations' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-ban"></i>
                    Cancellations
                </button>
                <button @click="activeTab = 'le'" 
                        :class="activeTab === 'le' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i>
                    Learner Enrollment
                </button>
                <button @click="activeTab = 'contacts'" 
                        :class="activeTab === 'contacts' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-address-book"></i>
                    Contacts
                </button>
                <button @click="activeTab = 'logs'" 
                        :class="activeTab === 'logs' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-history"></i>
                    Logs
                </button>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- Applications Tab -->
                <div x-show="activeTab === 'apps'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Application Name</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($apps as $app)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-premium">{{ $app['name'] }}</div>
                                            <div class="text-[10px] text-slate-400 mt-1">Added: {{ $app['start_date'] ? \Carbon\Carbon::parse($app['start_date'])->format('d M Y') : 'N/A' }}</div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500">{{ $app['type'] }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                                {{ $app['form_status'] == 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                                {{ $app['form_status'] }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <button class="text-brand hover:underline font-bold text-xs">VIEW</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-sm italic">No records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Renewals Tab -->
                <div x-show="activeTab === 'renewals'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Application</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submitted Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($renewals as $renew)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6 font-bold text-premium">Renewal Registration</td>
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $renew->submitted_date ?? '---' }}</td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-600 uppercase">{{ $renew->form_status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 text-sm italic">No renewal records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Cancellations Tab -->
                <div x-show="activeTab === 'cancellations'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submit Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($cancellations as $cancel)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $cancel->submission_date }}</td>
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $cancel->approved_date }}</td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold bg-red-50 text-red-600 uppercase">{{ $cancel->cancel_status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 text-sm italic">No cancellation requests</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- LE Tab -->
                <div x-show="activeTab === 'le'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Qualification</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cohort</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Learners</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Duration</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($leRecords as $le)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-premium">{{ $le->qualification_name }}</div>
                                            <div class="text-[10px] text-slate-400 mt-1">Submitted: {{ $le->submission_date }}</div>
                                        </td>
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $le->cohort }}</td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-bold text-brand">{{ $le->learners_no }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-xs text-slate-500 font-medium whitespace-nowrap">
                                            {{ $le->start_date }} → {{ $le->end_date }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-sm italic">No enrollment records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Contacts Tab -->
                <div x-show="activeTab === 'contacts'" class="animate-fade-in-up">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($atp->contacts as $contact)
                        <div class="premium-card p-8 group">
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Contact #{{ $loop->iteration }}</h4>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-brand group-hover:text-white transition-all">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Name</div>
                                        <div class="text-sm font-bold text-premium">{{ $contact->contact_name }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Designation</div>
                                        <div class="text-sm font-bold text-premium">{{ $contact->contact_designation }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email</div>
                                        <div class="text-sm font-bold text-premium">{{ $contact->contact_email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-10 text-center text-slate-400 italic">No contacts registered.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Logs Tab -->
                <div x-show="activeTab === 'logs'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Performed By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-5 text-sm font-bold text-premium text-brand">{{ $log->log_action }}</td>
                                        <td class="px-8 py-5 text-xs text-slate-500">{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y H:i') }}</td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-[10px] font-bold text-slate-400">
                                                    {{ substr($log->logger->first_name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="text-xs font-bold text-slate-600">{{ $log->logger->first_name ?? 'System' }} {{ $log->logger->last_name ?? '' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 italic">No logs found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="space-y-8">
            <div class="premium-card p-6 bg-gradient-brand text-white shadow-xl shadow-brand/20">
                <h4 class="text-xs font-bold uppercase tracking-widest opacity-70 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i>
                    Classification
                </h4>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="fa-solid fa-tags text-white shadow-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-medium opacity-60 uppercase tracking-wider">Category</div>
                            <div class="text-sm font-bold">{{ $atp->category->atp_category_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="fa-solid fa-shapes text-white shadow-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-medium opacity-60 uppercase tracking-wider">Type</div>
                            <div class="text-sm font-bold">{{ $atp->type->atp_type_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="premium-card p-6">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 px-1">Quick Actions</h4>
                <div class="space-y-3">
                    <form action="{{ route('emp.atps.send-email', $atp->atp_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full p-3.5 rounded-xl border border-slate-100 text-xs font-bold text-slate-600 hover:border-brand/30 hover:bg-slate-50 transition-all flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            Send Credentials
                        </button>
                    </form>
                    
                    @if($atp->atp_status_id != 4)
                    <form action="{{ route('emp.atps.accredit', $atp->atp_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full p-3.5 rounded-xl border border-slate-100 text-xs font-bold text-slate-600 hover:border-brand/30 hover:bg-slate-50 transition-all flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            Accredit Provider
                        </button>
                    </form>
                    @endif

                    <button class="w-full p-3.5 rounded-xl border border-slate-100 text-xs font-bold text-slate-600 hover:border-brand/30 hover:bg-slate-50 transition-all flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all">
                            <i class="fa-solid fa-print"></i>
                        </div>
                        Print Certificate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
