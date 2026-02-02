@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
    <div class="flex gap-6 animate-fade-in-up">
        @include('admin.settings.sidebar') <!-- Refactored sidebar logic likely, or just simple layout -->

        <div class="flex-1 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold font-display text-premium">Leave Types</h2>
                <button onclick="document.getElementById('newModal').showModal()" class="premium-button px-6 py-2">Add
                    New</button>
            </div>

            <div class="premium-card overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="p-4">Name</th>
                            <th class="p-4">Paid?</th>
                            <th class="p-4">Days/Year</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($types as $type)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-bold">{{ $type->leave_type_name }}</td>
                                <td class="p-4">{{ $type->is_paid ? 'Yes' : 'No' }}</td>
                                <td class="p-4">{{ $type->days_per_year }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <dialog id="newModal" class="modal rounded-2xl shadow-2xl p-6 w-full max-w-md backdrop:bg-slate-900/50">
        <form action="{{ route('admin.settings.leave_types.store') }}" method="POST">
            @csrf
            <h3 class="font-bold text-lg mb-4">Add Leave Type</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Name</label>
                    <input type="text" name="leave_type_name" class="premium-input w-full" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase">Days Per Year</label>
                    <input type="number" name="days_per_year" class="premium-input w-full" value="0">
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_paid" value="1"
                            class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-bold text-slate-700">Is Paid Leave?</span>
                    </label>
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
