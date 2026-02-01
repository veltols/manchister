@extends('layouts.app')

@section('title', 'Communications')
@section('subtitle', 'External communications log')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">Communications Log</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $records->total() }} total logs</p>
        </div>
        <button onclick="openModal('addCommModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>New Log</span>
        </button>
    </div>

    <!-- Filter -->
    <div>
        <form action="{{ route('hr.communications.index') }}" method="GET">
            <select name="type_id" onchange="this.form.submit()" class="premium-input px-4 py-3 text-sm">
                <option value="">All Types</option>
                @foreach($types as $t)
                    <option value="{{ $t->communication_type_id }}" {{ request('type_id') == $t->communication_type_id ? 'selected' : '' }}>
                        {{ $t->communication_type_name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">TRX Code</th>
                        <th class="text-left">Entity / Party</th>
                        <th class="text-left">Subject</th>
                        <th class="text-center">Type</th>
                        <th class="text-left">Date</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                    <tr>
                        <td><span class="font-mono text-sm font-semibold text-indigo-600">{{ $rec->communication_code }}</span></td>
                        <td><span class="font-semibold text-slate-800">{{ $rec->external_party_name }}</span></td>
                        <td><span class="text-sm text-slate-600">{{ $rec->communication_subject }}</span></td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium">
                                <i class="fa-solid fa-tag text-xs"></i>
                                {{ $rec->type->communication_type_name ?? '-' }}
                            </span>
                        </td>
                        <td><span class="text-sm text-slate-600">{{ $rec->requested_date }}</span></td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ $rec->status->communication_status_name ?? 'Unknown' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-bullhorn text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No communication logs found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $records->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

<!-- Create Modal -->
<div class="modal" id="addCommModal">
    <div class="modal-backdrop" onclick="closeModal('addCommModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-slate-800">New Communication Log</h2>
            <button onclick="closeModal('addCommModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('hr.communications.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">External Party / Entity</label>
                    <input type="text" name="external_party_name" class="premium-input w-full px-4 py-3 text-sm" placeholder="e.g. Ministry of Labour" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                    <select name="communication_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        @foreach($types as $t)
                            <option value="{{ $t->communication_type_id }}">{{ $t->communication_type_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Subject</label>
                    <input type="text" name="communication_subject" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description / Notes</label>
                    <textarea name="communication_description" rows="3" class="premium-input w-full px-4 py-3 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addCommModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Save Log</button>
            </div>
        </form>
    </div>
</div>

@endsection
