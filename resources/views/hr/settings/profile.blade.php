@extends('layouts.app')

@section('title', 'Profile Settings')
@section('subtitle', 'Customize your experience')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
        
        <div class="premium-card p-10">
            <h2 class="text-2xl font-display font-bold text-premium mb-8 border-b border-slate-100 pb-4">Theme & Appearance</h2>
            
            <form action="{{ route('hr.settings.update') }}" method="POST" class="space-y-8">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-4">Select Theme Color</label>
                    
                    <div class="grid grid-cols-5 md:grid-cols-8 gap-4">
                        @foreach($themes as $theme)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="user_theme_id" value="{{ $theme->user_theme_id }}" class="peer sr-only" {{ ($user->user_theme_id ?? 1) == $theme->user_theme_id ? 'checked' : '' }}>
                                <div class="w-12 h-12 rounded-full shadow-sm flex items-center justify-center transition-transform peer-checked:scale-110 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-indigo-500" style="background-color: #{{ $theme->color_secondary }};">
                                    <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                                </div>
                                <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-slate-500 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">{{ $theme->theme_name ?? 'Color' }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50">
                    <button type="submit" class="premium-button px-8 py-3">
                        Save Preferences
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
