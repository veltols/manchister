@extends('layouts.app')

@section('title', 'Profile Settings')
@section('subtitle', 'Manage your theme and preferences')

@section('content')
<div class="space-y-6">
    <!-- Theme Settings Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="fa-solid fa-palette text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Theme Preference</h3>
                <p class="text-sm text-slate-500">Customize your workspace appearance</p>
            </div>
        </div>

        <form action="{{ route('emp.settings.update') }}" method="POST">
            @csrf
            
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($themes as $theme)
                        @php
                            $isSelected = $user->user_theme_id == $theme->user_theme_id;
                        @endphp
                        
                        <label class="cursor-pointer group relative block">
                            <input type="radio" name="user_theme_id" value="{{ $theme->user_theme_id }}" 
                                class="peer sr-only" {{ $isSelected ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            
                            <div class="relative overflow-hidden rounded-2xl border-2 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg
                                {{ $isSelected ? 'border-brand ring-4 ring-brand/10 shadow-xl' : 'border-slate-100 hover:border-brand/30' }} bg-white">
                                
                                <!-- Color Preview Header -->
                                <div class="h-24 w-full relative flex">
                                    <div class="h-full flex-1" style="background-color: #{{ str_replace('#', '', $theme->color_primary ?? 'ef4444') }};"></div>
                                    <div class="h-full flex-1" style="background-color: #{{ str_replace('#', '', $theme->color_secondary ?? '3b82f6') }};"></div>
                                    <div class="h-full flex-1" style="background-color: #{{ str_replace('#', '', $theme->color_third ?? '10b981') }};"></div>
                                    
                                    <!-- Selected Indicator -->
                                    @if($isSelected)
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/10 backdrop-blur-[2px]">
                                            <div class="bg-white/90 backdrop-blur text-brand rounded-full w-10 h-10 flex items-center justify-center shadow-lg transform scale-100 transition-transform animate-bounce-short">
                                                <i class="fa-solid fa-check text-lg"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Theme Info -->
                                <div class="p-5">
                                    <h4 class="font-bold text-slate-800 text-lg mb-1 group-hover:text-brand transition-colors">
                                        {{ $theme->theme_name ?? 'Theme ' . $theme->user_theme_id }}
                                    </h4>
                                    <div class="flex items-center gap-2 text-xs text-slate-400 font-mono mb-3">
                                        <span>#{{ $theme->user_theme_id }}</span>
                                        <span>•</span>
                                        <span>P: #{{ str_replace('#', '', $theme->color_primary) }}</span>
                                    </div>

                                    <!-- Mini Swatches -->
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg shadow-sm border border-slate-100" style="background-color: #{{ str_replace('#', '', $theme->color_primary) }};" title="Primary"></div>
                                        <div class="w-8 h-8 rounded-lg shadow-sm border border-slate-100" style="background-color: #{{ str_replace('#', '', $theme->color_secondary) }};" title="Secondary"></div>
                                        <div class="w-8 h-8 rounded-lg shadow-sm border border-slate-100" style="background-color: #{{ str_replace('#', '', $theme->color_third) }};" title="Third"></div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $themes->links() }}
                </div>
            
        </form>
    </div>
</div>
@endsection
