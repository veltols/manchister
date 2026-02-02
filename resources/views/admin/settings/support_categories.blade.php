@extends('layouts.app')

@section('title', 'Support Routing')

@section('content')
    <div class="flex gap-6 animate-fade-in-up">
        <div class="w-64 hidden md:block">@include('admin.settings.partials.sidebar_content')</div>

        <div class="flex-1 space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold font-display text-premium">Support Categories (Routing)</h2>
                    <p class="text-sm text-slate-500">Define categories and who receives them.</p>
                </div>
                <button onclick="document.getElementById('newModal').showModal()" class="premium-button px-6 py-2">Add
                    New</button>
            </div>

            <div class="premium-card overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="p-4">Category Name</th>
                            <th class="p-4">Routed To (Receiver)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($cats as $cat)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-bold">{{ $cat->category_name }}</td>
                                <td class="p-4 flex items-center gap-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-[10px] font-bold flex items-center justify-center">
                                        {{ substr($cat->first_name ?? 'U', 0, 1) }}
                                    </div>
                                    {{ $cat->first_name }} {{ $cat->last_name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <dialog id="newModal" class="modal rounded-2xl shadow-2xl p-6 w-full max-w-md backdrop:bg-slate-900/50">
        <form action="{{ route('admin.settings.support_categories.store') }}" method="POST">
            @csrf
            <h3 class="font-bold text-lg mb-4">Add Support Category</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Category Name</label>
                    <input type="text" name="category_name" class="premium-input w-full" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Route To</label>
                    <select name="destination_id" class="premium-input w-full" required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
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
