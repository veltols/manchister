@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass-panel p-6 mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fa-solid fa-bell text-secondary"></i> Your Notifications
        </h1>
        <button class="text-sm text-secondary hover:underline">Mark all as read</button>
    </div>

    <div class="space-y-3">
        @forelse($notifications as $n)
        <div class="glass-panel p-4 flex gap-4 items-start {{ $n->is_seen ? 'opacity-70 bg-gray-50' : 'bg-white border-blue-200 border ' }}">
            <div class="mt-1">
                 @if($n->is_seen)
                    <i class="fa-regular fa-bell text-gray-400"></i>
                 @else
                    <i class="fa-solid fa-bell text-secondary fa-beat-fade"></i>
                 @endif
            </div>
            <div class="flex-1">
                <p class="text-gray-800 font-medium">{{ $n->notification_text }}</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-400">{{ $n->notification_date }}</span>
                    @if($n->related_page)
                    <a href="{{ url($n->related_page) }}" class="text-xs text-secondary hover:underline">View Details <i class="fa-solid fa-arrow-right"></i></a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="glass-panel p-10 text-center text-gray-400">
            <i class="fa-regular fa-bell-slash text-4xl mb-3"></i>
            <p>No notifications found.</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
