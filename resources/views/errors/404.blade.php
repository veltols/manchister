@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center p-6">
    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6 animate-bounce">
        <i class="fa-solid fa-compass text-4xl text-[var(--theme-color)]"></i>
    </div>
    
    <h1 class="text-6xl font-bold text-slate-800 mb-2">404</h1>
    <h2 class="text-2xl font-semibold text-slate-600 mb-4">Lost in the Digital Void?</h2>
    
    <p class="text-slate-500 max-w-md mx-auto mb-8">
        The page you are looking for seems to have gone on an adventure without us. 
        Don't worry, even the best explorers get lost sometimes.
    </p>

   
</div>
@endsection
