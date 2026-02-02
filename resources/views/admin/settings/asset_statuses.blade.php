@extends('layouts.app')

@section('title', 'Asset Statuses')

@section('content')
    <div class="flex gap-6 animate-fade-in-up">
        <!-- Simplified: In real app use @include for sidebar or main layout with slot -->
        <div class="w-64 hidden md:block">@include('admin.settings.partials.sidebar_content')</div>

        <div class="flex-1 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold font-display text-premium">Asset Statuses</h2>
                <button onclick="document.getElementById('newModal').showModal()" class="premium-button px-6 py-2">Add
                    New</button>
            </div>

            <div class="premium-card overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="p-4">Status Name</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($statuses as $status)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-bold">{{ $status->status_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <dialog id="newModal" class="modal rounded-2xl shadow-2xl p-6 w-full max-w-md backdrop:bg-slate-900/50">
        <form action="{{ route('admin.settings.asset_statuses.store') }}" method="POST">
            @csrf
            <h3 class="font-bold text-lg mb-4">Add Asset Status</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Name</label>
                    <input type="text" name="status_name" class="premium-input w-full" required>
                </div>
                <div class="flex gap-3 pt-2">
                    <button class="premium-button flex-1 py-2">Save</button>
                    <button type="button" onclick="document.getElementById('newModal').close()"
                        class="py-2 px-4 rounded-lg border border-slate-200">Cancel</button>
                </div>
            </div>
        </form>
    </dialog>
@endsection
