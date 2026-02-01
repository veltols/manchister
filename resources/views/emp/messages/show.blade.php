@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->first_name)
@section('subtitle', 'Messages')

@section('content')
<div class="h-[calc(100vh-180px)] flex gap-6">

    <!-- Sidebar / Chat List -->
    <div class="hidden md:flex w-1/3 premium-card flex-col overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-display font-bold text-slate-800">Inbox</h2>
            <a href="{{ route('emp.messages.index') }}" class="text-slate-500 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back
            </a>
        </div>
        <div class="p-8 text-center">
            <a href="{{ route('emp.messages.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                View all conversations
            </a>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="w-full md:w-2/3 premium-card flex flex-col overflow-hidden">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('emp.messages.index') }}" class="md:hidden text-slate-500 hover:text-indigo-600 mr-2">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                    {{ substr($otherUser->first_name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</h3>
                    <div class="text-xs text-green-500 flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        Online
                    </div>
                </div>
            </div>
            <button class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
        </div>

        <!-- Messages Body -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gradient-to-br from-slate-50 via-blue-50/30 to-purple-50/30" id="chatMessages">
            @foreach($messages as $msg)
                @if($msg->added_by == Auth::id())
                    <!-- My Message -->
                    <div class="flex justify-end animate-fade-in">
                        <div class="max-w-[75%]">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-4 rounded-2xl rounded-br-sm shadow-lg">
                                <p class="text-sm leading-relaxed">{{ $msg->post_text }}</p>
                            </div>
                            <div class="text-right text-xs text-slate-400 mt-1 px-2">
                                {{ \Carbon\Carbon::parse($msg->added_date)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Their Message -->
                    <div class="flex justify-start animate-fade-in">
                        <div class="max-w-[75%]">
                            <div class="flex items-end gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-xs font-semibold shadow-md mb-1">
                                    {{ substr($msg->sender->first_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="bg-white text-slate-800 p-4 rounded-2xl rounded-bl-sm shadow-lg border border-slate-100">
                                        <p class="text-sm leading-relaxed">{{ $msg->post_text }}</p>
                                    </div>
                                    <div class="text-left text-xs text-slate-400 mt-1 px-2">
                                        {{ \Carbon\Carbon::parse($msg->added_date)->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Input Area -->
        <form action="{{ route('emp.messages.reply', $conversation->chat_id) }}" method="POST" class="p-6 bg-white border-t border-slate-100">
            @csrf
            <div class="flex gap-3 items-center">
                <button type="button" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-paperclip"></i>
                </button>
                <input type="text" name="message" class="flex-1 premium-input px-6 py-3 text-sm rounded-full" placeholder="Type a message..." required autocomplete="off">
                <button type="submit" class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>

    </div>

</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

<script>
    // Scroll to bottom
    const chatContainer = document.getElementById('chatMessages');
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>
@endsection
