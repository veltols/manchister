@extends('layouts.app')

@section('title', 'Chat')
@section('subtitle', 'Conversation')

@section('content')
    <div class="h-[calc(100vh-12rem)] flex gap-6 animate-fade-in-up">

        <!-- Sidebar: Conversation List (Compact) -->
        <div class="w-80 hidden md:flex flex-col bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-lg font-bold font-display text-premium">Inbox</h2>
                <a href="{{ route('messages.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">View All</a>
            </div>
            <div class="flex-1 overflow-y-auto space-y-2 p-4 custom-scrollbar">
                @foreach($conversations as $conv)
                    <a href="{{ route('messages.show', $conv->chat_id) }}"
                        class="block p-3 rounded-xl {{ $conv->chat_id == $conversation->chat_id ? 'bg-indigo-50 border-indigo-200 ring-1 ring-indigo-100' : 'bg-white hover:bg-slate-50' }} border border-transparent transition-all">
                        @php
                            $user = Auth::user();
                            $myId = $user->employee_id ?? 0;
                            $other = ($conv->a_id == $myId) ? $conv->participantB : $conv->participantA;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold overflow-hidden border border-slate-300">
                                @if($other && $other->employee_profile_pic)
                                    <img src="{{ asset($other->employee_profile_pic) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($other->first_name ?? 'U', 0, 1) }}{{ substr($other->last_name ?? 'U', 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-700 truncate {{ $conv->chat_id == $conversation->chat_id ? 'text-indigo-700' : '' }}">{{ $other->first_name ?? 'User' }}</h4>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Area: Chat Window -->
        <div class="flex-1 bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col overflow-hidden relative">
            <!-- Header -->
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 z-10">
                @php
                    $user = Auth::user();
                    $myId = $user->employee_id ?? 0;
                    $partner = ($conversation->a_id == $myId) ? $conversation->participantB : $conversation->participantA;
                @endphp
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-brand-light/20 flex items-center justify-center text-brand-dark font-bold overflow-hidden border border-brand-light/30">
                        @if($partner && $partner->employee_profile_pic)
                            <img src="{{ asset($partner->employee_profile_pic) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($partner->first_name ?? 'U', 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-premium">{{ $partner->first_name ?? 'User' }}
                            {{ $partner->last_name ?? '' }}</h3>
                        <p class="text-xs text-slate-500 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="messagesArea" class="flex-1 p-0 overflow-hidden bg-slate-50/30">
                 <div style="overflow-y: auto !important; height: 100% !important; padding: 1.5rem; padding-right: 1.5rem !important; display: flex; flex-direction: column-reverse; gap: 1rem;" id="chatMessages">
                    @foreach($conversation->messages->sortByDesc('post_id') as $msg)
                        <div class="flex {{ $msg->added_by == $myId ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-[70%] {{ $msg->added_by == $myId ? 'bg-indigo-600 text-white rounded-br-none shadow-indigo-200' : 'bg-white text-slate-700 border border-slate-200 rounded-bl-none shadow-sm' }} shadow-md rounded-2xl px-5 py-3">
                                <p class="text-sm leading-relaxed">{{ $msg->post_content }}</p>
                                <div
                                    class="text-[10px] mt-1 opacity-70 {{ $msg->added_by == $myId ? 'text-indigo-200 text-right' : 'text-slate-400' }}">
                                    {{ $msg->added_date }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <form action="{{ route('messages.reply', $conversation->chat_id) }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="message_text"
                        class="flex-1 bg-slate-100 border-transparent focus:bg-white focus:border-indigo-300 rounded-xl px-4 py-3 text-sm transition-all outline-none"
                        placeholder="Type your message..." autocomplete="off">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-6 font-bold transition-colors shadow-lg shadow-indigo-100">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                    <button type="button"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl px-4 transition-colors"
                        title="Attach File">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
