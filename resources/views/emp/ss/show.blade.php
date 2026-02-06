@extends('layouts.app')

@section('title', 'Service Request Details')
@section('subtitle', 'View details for request #' . $service->ss_ref)

@section('content')
    <div class="space-y-8 animate-fade-in-up" x-data="{ activeTab: 'details' }">

        <!-- Back Button & Tools -->
        <div class="flex items-center justify-between">
            <a href="{{ route('emp.ss.index') }}"
                class="group flex items-center gap-2 text-slate-500 font-bold hover:text-brand transition-colors">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Back to Requests</span>
            </a>
            <!-- Optional Actions -->
        </div>

        <!-- Premium Hero Banner -->
        <div class="rounded-[2.5rem] bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 p-8 md:p-12 text-white shadow-2xl shadow-indigo-900/20 relative overflow-hidden isolate">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full blur-3xl -z-10"></div>
            <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl -z-10"></div>
            <div class="absolute top-1/2 left-0 w-32 h-64 bg-indigo-400/10 rounded-full blur-2xl -z-10"></div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-lg bg-white/10 border border-white/10 text-[10px] font-bold uppercase tracking-widest backdrop-blur-md">
                            {{ $service->category->category_name ?? 'General Request' }}
                        </span>
                        <span class="text-white/40 text-xs">•</span>
                        <span class="text-xs font-mono font-medium text-white/60">
                            {{ $service->ss_added_date }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-display font-black tracking-tight text-white leading-tight">
                        {{ $service->ss_ref }}
                    </h1>
                    
                    <div class="flex items-center gap-6 pt-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Requested By</p>
                                <p class="text-sm font-bold">{{ $service->sender->first_name ?? 'Unknown' }} {{ $service->sender->last_name ?? '' }}</p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Sent To</p>
                                <p class="text-sm font-bold">{{ $service->receiver->first_name ?? 'Unassigned' }} {{ $service->receiver->last_name ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-4">
                    <div class="px-6 py-3 rounded-2xl bg-white text-slate-900 shadow-xl flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full animate-pulse" style="background-color: #{{ $service->status->status_color ?? '000' }}"></div>
                        <span class="font-bold text-lg" style="color: #{{ $service->status->status_color ?? '000' }}">
                            {{ $service->status->status_name ?? 'Pending' }}
                        </span>
                    </div>
                    @if($service->ss_attachment)
                        <a href="{{ asset($service->ss_attachment) }}" target="_blank" 
                           class="flex items-center gap-2 text-xs font-bold text-white/70 hover:text-white transition-colors bg-white/10 px-4 py-2 rounded-xl hover:bg-white/20">
                            <i class="fa-solid fa-paperclip"></i>
                            View Attachment
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Description Card -->
                <div class="premium-card p-1">
                    <div class="bg-indigo-50/50 p-8 rounded-[1.25rem]">
                        <h3 class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">
                            <i class="fa-solid fa-align-left text-brand"></i>
                            Request Description
                        </h3>
                        <div class="prose prose-slate max-w-none prose-p:font-medium prose-p:text-slate-600">
                            {!! nl2br(e($service->ss_description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Timeline / Activity Logs -->
                <div class="premium-card p-8">
                    <h3 class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-widest mb-8">
                        <i class="fa-solid fa-clock-rotate-left text-brand"></i>
                        Activity Timeline
                    </h3>
                    
                    <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-indigo-100 before:via-slate-200 before:to-transparent">
                        @forelse($service->logs as $log)
                            <div class="relative flex items-start gap-4 group">
                                <div class="absolute left-0 mt-1 ml-5 w-4 h-0.5 bg-indigo-200 group-hover:bg-brand transition-colors"></div>
                                
                                <div class="relative z-10 flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-indigo-50 text-indigo-600 shadow-sm group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                </div>
                                
                                <div class="flex-1 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm group-hover:shadow-md group-hover:border-indigo-100 transition-all">
                                    <div class="flex flex-wrap justify-between gap-2 mb-2">
                                        <span class="font-bold text-slate-800">{{ $log->log_action }}</span>
                                        <span class="text-xs font-mono text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                            {{ \Carbon\Carbon::parse($log->log_date)->format('M d, H:i A') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 mb-3">{{ $log->log_remark }}</p>
                                    <div class="flex items-center gap-2 pt-2 border-t border-slate-50">
                                        <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-500">
                                            {{ $log->logger ? substr($log->logger->first_name, 0, 1) : 'S' }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                            {{ $log->logger->first_name ?? 'System' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="pl-12 py-4">
                                <p class="text-slate-400 italic font-medium">No activity recorded yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Context/Help -->
            <div class="space-y-6">
                <!-- Related Actions if needed -->
                
                <!-- Help Card -->
                <div class="premium-card p-6 bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    
                    <h4 class="text-lg font-bold mb-2">Need to escalate?</h4>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        If this request is urgent or overdue, you can send a direct message to the recipient.
                    </p>
                    
                    <a href="{{ route('emp.messages.index') }}" class="w-full py-3 rounded-xl bg-white/10 border border-white/10 text-white font-bold text-sm hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center gap-2">
                        <i class="fa-regular fa-paper-plane"></i>
                        Open Messages
                    </a>
                </div>

                 @if($service->ss_attachment)
                    <div class="premium-card p-6">
                         <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Files</h4>
                         <a href="{{ asset($service->ss_attachment) }}" target="_blank" class="group block">
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 group-hover:border-indigo-200 group-hover:bg-indigo-50/30 transition-all">
                                <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-slate-700 truncate group-hover:text-indigo-700 transition-colors">Attachment</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Click to Preview</p>
                                </div>
                            </div>
                         </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
