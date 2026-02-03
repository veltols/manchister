@extends('layouts.app')

@section('title', 'Notifications')
@section('subtitle', 'System Alerts & Messages')

@section('content')
    <div class="bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-white to-slate-50">
            <div class="flex items-center gap-3">
                 <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="fa-regular fa-bell"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-lg text-slate-800">Your Notifications</h3>
                    <p class="text-xs text-slate-500 font-medium">Manage your system alerts and updates</p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider w-48">Date</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Remark</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($notifications as $notification)
                        <tr class="group hover:bg-slate-50 transition-colors cursor-default">
                             <td class="px-8 py-5 text-sm font-medium text-slate-500 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                     <i class="fa-regular fa-calendar text-slate-300"></i>
                                     {{ $notification->notification_date ? $notification->notification_date->format('M d, Y h:i A') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-medium text-slate-700 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $notification->notification_text }}</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                @if($notification->related_page)
                                    <a href="{{ url($notification->related_page) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-all shadow-sm group-hover:scale-105" title="Go to Page">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-300" title="No Link">
                                        <i class="fa-solid fa-ban"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-regular fa-bell-slash text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium text-lg">You have no new notifications.</p>
                                    <p class="text-sm text-slate-400 mt-1">We'll let you know when something important happens.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($notifications->hasPages())
            <div class="p-8 border-t border-slate-100 bg-slate-50/50">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
