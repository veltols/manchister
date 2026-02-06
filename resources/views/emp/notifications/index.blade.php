@extends('layouts.app')

@section('title', 'Notifications')
@section('subtitle', 'Stay updated with your latest activities')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up" x-data="{ 
        selected: [],
        notifications: {{ json_encode($notifications->pluck('notification_id')) }},
        toggleAll() {
            if (this.selected.length === {{ $notifications->count() }}) {
                this.selected = [];
            } else {
                this.selected = {{ json_encode($notifications->pluck('notification_id')) }};
            }
        },
        markRead(ids) {
            if (!ids) return;
            let form = document.getElementById('markReadForm');
            let input = document.getElementById('markReadIds');
            input.value = Array.isArray(ids) ? ids.join(',') : ids;
            form.submit();
        }
    }">

        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-brand text-white flex items-center justify-center shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-bell text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Notification Center</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ $notifications->total() }} alerts recorded</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <template x-if="selected.length > 0">
                    <button @click="markRead(selected)" 
                            class="px-5 py-2.5 rounded-xl bg-gradient-brand text-white text-xs font-bold shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-300 flex items-center gap-2 border border-white/10">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Mark <span x-text="selected.length"></span> Selected as Read</span>
                    </button>
                </template>
                <button @click="markRead(null)" 
                        class="px-6 py-2.5 bg-gradient-brand text-white rounded-xl font-bold shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2 border border-white/10">
                    <i class="fa-solid fa-check-double"></i>
                    <span>Mark All as Read</span>
                </button>
            </div>
        </div>

        <form id="markReadForm" action="{{ route('emp.notifications.mark_read') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="ids" id="markReadIds">
        </form>

        <div class="space-y-4">
            @forelse($notifications as $notif)
                <div
                    class="premium-card p-4 flex items-center gap-6 hover:shadow-lg transition-all border-slate-50 {{ $notif->is_seen == 0 ? 'bg-brand/5 border-l-4 border-l-brand' : 'bg-white' }}">
                    
                    <div class="flex items-center gap-4 shrink-0">
                        <input type="checkbox" :value="{{ $notif->notification_id }}" x-model="selected" 
                               class="w-5 h-5 rounded border-slate-200 text-brand focus:ring-brand cursor-pointer">
                        
                        <div class="notif-icon-wrapper w-12 h-12 rounded-2xl {{ $notif->is_seen == 0 ? 'bg-gradient-brand text-white shadow-lg shadow-brand/30' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center shrink-0 transition-all duration-300">
                             @if($notif->is_seen == 0)
                                <i class="fa-solid fa-bell animate-bounce text-lg"></i>
                             @else
                                <i class="fa-solid fa-circle-check text-lg"></i>
                             @endif
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 py-2">
                        <div class="flex items-center justify-between gap-4 mb-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $notif->notification_date }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">System Alert</span>
                            </div>
                            @if($notif->is_seen == 0)
                                <span class="px-2 py-0.5 rounded-full bg-brand/10 text-brand text-[9px] font-black uppercase tracking-wider">New</span>
                            @endif
                        </div>
                        <p class="notif-text text-slate-700 font-medium line-clamp-2 md:line-clamp-none">
                            {{ $notif->notification_text }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 ml-auto">
                        @if($notif->related_page)
                            <a href="{{ url($notif->related_page) }}"
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md group"
                                title="Go to Page">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        @endif
                        @if($notif->is_seen == 0)
                            <button @click="markRead([{{ $notif->notification_id }}])"
                                class="btn-mark-seen w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                title="Mark as Read">
                                <i class="fa-solid fa-check text-sm"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <div
                        class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6">
                        <i class="fa-solid fa-bell-slash text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-premium">No active notifications</h3>
                    <p class="text-slate-400 mt-2">We'll notify you when something important happens.</p>
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
