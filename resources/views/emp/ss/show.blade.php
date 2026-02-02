@extends('layouts.app')

@section('title', 'Service Request Details')
@section('subtitle', $service->ss_ref)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in-up">

        <!-- Left Column: Details -->
        <div class="lg:col-2 space-y-6">
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-handshake-angle text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-display font-bold text-premium">
                                {{ $service->category->category_name ?? '-' }}
                            </h2>
                            <span
                                class="font-mono text-sm text-indigo-600 font-bold tracking-tighter">{{ $service->ss_ref }}</span>
                        </div>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-xs font-bold uppercase shadow-md"
                        style="background: #{{ $service->status->status_color ?? '64748b' }};">
                        {{ $service->status->status_name ?? 'Pending' }}
                    </span>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Request Description</h3>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 text-slate-700 leading-relaxed">
                            {!! nl2br(e($service->ss_description)) !!}
                        </div>
                    </div>

                    @if($service->ss_attachment)
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Attachment</h3>
                            <a href="{{ asset($service->ss_attachment) }}" target="_blank"
                                class="inline-flex items-center gap-3 px-6 py-4 bg-white border border-slate-200 rounded-2xl hover:border-indigo-300 hover:shadow-md transition-all group">
                                <i
                                    class="fa-solid fa-file-pdf text-xl text-red-500 group-hover:scale-110 transition-transform"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-slate-700">View Attachment</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-tighter">Click to open in new tab
                                    </p>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress History -->
            <div class="premium-card p-8">
                <h3 class="text-lg font-display font-bold text-premium mb-8">Activity History</h3>
                <div
                    class="space-y-8 relative before:absolute before:left-6 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                    @forelse($service->logs as $log)
                        <div class="relative pl-16">
                            <div
                                class="absolute left-0 top-0 w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center z-10 shadow-sm">
                                <i class="fa-solid fa-history text-slate-400 text-sm"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="font-bold text-slate-800">{{ $log->log_action }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $log->log_date }}</span>
                                </div>
                                <p class="text-sm text-slate-500 italic">"{{ $log->log_remark }}"</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">By:</span>
                                    <span class="text-xs font-medium text-slate-600">{{ $log->logger->first_name ?? 'System' }}
                                        {{ $log->logger->last_name ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm italic pl-4">No activity logged yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Info Sidebar -->
        <div class="space-y-6">
            <div class="premium-card p-8 bg-gradient-brand text-white overflow-hidden relative">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <h3 class="text-lg font-display font-bold mb-6">Interaction Info</h3>
                    <div class="space-y-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Requester /
                                Sender</span>
                            <span class="font-bold text-white">{{ $service->sender->first_name ?? '-' }}
                                {{ $service->sender->last_name ?? '' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Addressed To</span>
                            <span class="font-bold text-white">{{ $service->receiver->first_name ?? '-' }}
                                {{ $service->receiver->last_name ?? '' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Requested On</span>
                            <span class="font-bold text-white">{{ $service->ss_added_date }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="premium-card p-6 border-brand-dark/20 border-2">
                <h4 class="text-sm font-bold text-premium mb-4 uppercase tracking-widest">Need help?</h4>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">If this request is urgent, please follow up with
                    {{ $service->receiver->first_name ?? 'the target employee' }} via internal messaging.
                </p>
                <a href="{{ route('emp.messages.index') }}"
                    class="inline-flex items-center gap-2 text-brand-dark font-bold text-xs hover:gap-3 transition-all">
                    <span>Go to Messages</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>
@endsection
