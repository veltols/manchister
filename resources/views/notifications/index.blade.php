@extends('layouts.app')

@section('title', 'Notifications')
@section('subtitle', 'Your recent alerts')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">

        <div class="premium-card overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <h2 class="font-bold text-premium text-lg font-display">All Notifications</h2>
                <button class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Mark all as
                    read</button>
            </div>

            <div class="divide-y divide-slate-50">
                @forelse($notifications as $notif)
                    <div
                        class="p-6 hover:bg-slate-50/50 transition-colors flex gap-4 {{ $notif->is_seen ? 'opacity-70' : '' }}">
                        <div
                            class="w-10 h-10 rounded-full {{ $notif->is_seen ? 'bg-slate-100 text-slate-400' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-slate-700 text-sm mb-1 leading-relaxed">{{ $notif->notification_text }}</p>
                            <span
                                class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($notif->notification_date)->diffForHumans() }}</span>
                        </div>
                        @if($notif->related_page)
                            <a href="{{ url($notif->related_page) }}"
                                class="flex items-center justify-center w-8 h-8 rounded-full border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all"
                                title="Go to Page">
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400">
                        <i class="fa-regular fa-bell-slash text-4xl mb-4 opacity-20"></i>
                        <p>You have no notifications.</p>
                    </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $notifications->links() }}
            </div>
        </div>

    </div>
@endsection
