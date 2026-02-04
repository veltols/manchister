@extends('layouts.app')

@section('title', 'Notifications')
@section('subtitle', 'Your System Alerts & Activity')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i class="fa-solid fa-bell text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Notifications Center</h2>
                    <p class="text-sm text-slate-500 font-medium">Stay updated with the latest alerts and tasks</p>
                </div>
            </div>
            <button onclick="markAllAsRead()" class="px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-slate-600 hover:text-indigo-600 hover:border-indigo-600 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-check-double"></i>
                Mark All as Read
            </button>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">Status</th>
                            <th class="text-left font-bold text-slate-400">Details</th>
                            <th class="text-left font-bold text-slate-400">Date & Time</th>
                            <th class="text-right font-bold text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($notifications as $notification)
                            <tr class="group hover:bg-slate-50/80 transition-all {{ !$notification->is_seen ? 'bg-indigo-50/30' : '' }}" id="notif-{{ $notification->notification_id }}">
                                <td class="w-20">
                                    <div class="flex justify-center">
                                        @if(!$notification->is_seen)
                                            <span class="relative flex h-3 w-3">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-600"></span>
                                            </span>
                                        @else
                                            <i class="fa-solid fa-circle-check text-slate-200 text-sm"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1 py-4">
                                        <p class="font-bold text-slate-700 leading-tight group-hover:text-indigo-600 transition-colors">{{ $notification->notification_text }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">System Alert</span>
                                            @if($notification->is_seen)
                                                <span class="text-[10px] text-slate-400 font-medium italic">Already seen</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-slate-500 whitespace-nowrap">
                                        <div class="font-bold text-sm">{{ $notification->notification_date ? $notification->notification_date->format('M d, Y') : 'N/A' }}</div>
                                        <div class="text-[10px] uppercase font-bold text-slate-400">{{ $notification->notification_date ? $notification->notification_date->format('h:i A') : '' }}</div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2 pr-4">
                                        @if($notification->related_page)
                                            <a href="{{ url($notification->related_page) }}" onclick="markRead({{ $notification->notification_id }})" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-all shadow-sm flex items-center justify-center group-hover:scale-110" title="View Details">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                                            </a>
                                        @endif
                                        @if(!$notification->is_seen)
                                            <button onclick="markRead({{ $notification->notification_id }})" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-emerald-500 hover:border-emerald-500 transition-all shadow-sm flex items-center justify-center group-hover:scale-110" title="Mark as Seen">
                                                <i class="fa-solid fa-check text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-24 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                                            <i class="fa-solid fa-bell-slash text-3xl text-slate-200"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 font-bold text-lg">No active notifications</p>
                                            <p class="text-slate-400 text-sm">We'll notify you when something important happens.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($notifications->hasPages())
                <div class="p-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function markRead(id) {
                $.ajax({
                    url: "{{ route('hr.notifications.mark_as_read') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        notification_id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            if (id === 0) {
                                location.reload();
                            } else {
                                const row = $('#notif-' + id);
                                row.removeClass('bg-indigo-50/30');
                                row.find('.relative.flex.h-3').replaceWith('<i class="fa-solid fa-circle-check text-slate-200 text-sm"></i>');
                                row.find('button[title="Mark as Seen"]').fadeOut();
                            }
                        }
                    }
                });
            }

            function markAllAsRead() {
                Swal.fire({
                    title: 'Mark all as read?',
                    text: "This will clear all unread notification markers.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, mark all',
                    borderRadius: '20px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        markRead(0);
                    }
                });
            }
        </script>
    @endpush
@endsection
