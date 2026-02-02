@extends('layouts.app')

@section('title', 'Chat')
@section('subtitle', 'Conversation')

@section('content')
    <div class="h-[calc(100vh-12rem)] flex gap-6 animate-fade-in-up">

        <!-- Sidebar: Conversation List (Compact) -->
        <div class="w-1/4 hidden md:flex flex-col gap-4 border-r border-slate-100 pr-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-bold font-display text-premium">Inbox</h2>
                <a href="{{ route('messages.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">View
                    All</a>
            </div>
            <div class="flex-1 overflow-y-auto space-y-2 custom-scrollbar">
                @foreach($conversations as $conv)
                    <a href="{{ route('messages.show', $conv->chat_id) }}"
                        class="block p-3 rounded-xl {{ $conv->chat_id == $conversation->chat_id ? 'bg-indigo-50 border-indigo-200' : 'bg-white hover:bg-slate-50' }} border border-transparent transition-all">
                        @php
                            $user = Auth::user();
                            $myId = $user->employee_id ?? 0;
                            $other = ($conv->a_id == $myId) ? $conv->participantB : $conv->participantA;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold overflow-hidden">
                                @if($other && $other->employee_profile_pic)
                                    <img src="{{ asset($other->employee_profile_pic) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($other->first_name ?? 'U', 0, 1) }}{{ substr($other->last_name ?? 'U', 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-700 truncate">{{ $other->first_name ?? 'User' }}</h4>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Area: Chat Window -->
        <div class="flex-1 flex flex-col premium-card p-0 overflow-hidden h-full">
            <!-- Header -->
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white z-10">
                @php
                    $user = Auth::user();
                    $myId = $user->employee_id ?? 0;
                    $partner = ($conversation->a_id == $myId) ? $conversation->participantB : $conversation->participantA;
                @endphp
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-brand-light/20 flex items-center justify-center text-brand-dark font-bold overflow-hidden">
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
            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50 custom-scrollbar flex flex-col-reverse">
                @foreach($conversation->messages->sortByDesc('post_id') as $msg)
                    <div class="flex {{ $msg->added_by == $myId ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-[70%] {{ $msg->added_by == $myId ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-slate-700 border border-slate-200 rounded-bl-none' }} rounded-2xl px-5 py-3 shadow-sm">
                            <p class="text-sm leading-relaxed">{{ $msg->post_content }}</p>
                            <div
                                class="text-[10px] mt-1 opacity-70 {{ $msg->added_by == $myId ? 'text-indigo-200 text-right' : 'text-slate-400' }}">
                                {{ $msg->added_date /* Format nicely if needed */ }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <form action="{{ route('messages.reply', $conversation->chat_id) }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="message_text"
                        class="flex-1 bg-slate-100 border-transparent focus:bg-white focus:border-indigo-300 rounded-xl px-4 py-3 text-sm transition-all outline-none"
                        placeholder="Type your message..." autocomplete="off">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-6 font-bold transition-colors">
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
