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
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($themes as $theme)
                    @php
                        $color = '#' . $theme->color_secondary;
                        $isSelected = $user->user_theme_id == $theme->user_theme_id;
                    @endphp
                    
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="user_theme_id" value="{{ $theme->user_theme_id }}" 
                            class="peer sr-only" {{ $isSelected ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        
                        <div class="relative overflow-hidden rounded-xl border-2 transition-all duration-200
                            {{ $isSelected ? 'border-indigo-500 ring-2 ring-indigo-200 ring-offset-2' : 'border-transparent hover:border-slate-200' }}">
                            
                            <!-- Color Preview -->
                            <div class="h-16 w-full" style="background-color: {{ $color }};"></div>
                            
                            <!-- Theme Name -->
                            <div class="p-3 bg-slate-50 text-center">
                                <span class="text-xs font-semibold text-slate-600 group-hover:text-slate-900">
                                    {{ 'Theme ' . $theme->user_theme_id }}
                                </span>
                            </div>

                            <!-- Checkmark for selected -->
                            @if($isSelected)
                            <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                                <div class="bg-white rounded-full p-1 shadow-sm">
                                    <i class="fa-solid fa-check text-indigo-600 text-sm"></i>
                                </div>
                            </div>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>

            
        </form>
    </div>
</div>
@endsection
