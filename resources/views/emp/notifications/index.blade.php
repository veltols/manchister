@extends('layouts.app')

@section('title', 'Notifications')
@section('subtitle', 'Stay updated with your latest activities')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">

        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-display font-bold text-premium">Recent Alerts</h2>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $notifications->total() }}
                recorded</span>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notif)
                <div
                    class="premium-card p-6 flex items-start gap-6 hover:shadow-lg transition-all border-slate-50 {{ $notif->is_seen == 0 ? 'bg-indigo-50/20 border-l-4 border-l-brand-dark' : 'bg-white' }}">
                    <div
                        class="w-12 h-12 rounded-2xl {{ $notif->is_seen == 0 ? 'bg-brand-dark text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center shrink-0 shadow-sm transition-colors">
                        <i class="fa-solid {{ $notif->is_seen == 0 ? 'fa-bell-on animate-pulse' : 'fa-bell' }} text-lg"></i>
                    </div>

                    <div class="flex-1 space-y-2">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $notif->notification_date }}</span>
                        </div>
                        <p class="text-slate-700 leading-relaxed font-medium">
                            {{ $notif->notification_text }}
                        </p>
                        @if($notif->related_page)
                            <div class="pt-2">
                                <a href="{{ url($notif->related_page) }}"
                                    class="inline-flex items-center gap-2 text-brand-dark font-bold text-xs hover:gap-3 transition-all">
                                    <span>View Details</span>
                                    <i class="fa-solid fa-arrow-right-long"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <div
                        class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6">
                        <i class="fa-solid fa-bell-slash text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-premium">Clear skies!</h3>
                    <p class="text-slate-400 mt-2">You don't have any notifications at the moment.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="pt-6">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>
@endsection
