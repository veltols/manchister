@extends('layouts.app')

@section('title', 'Communication Requests')
@section('subtitle', 'Manage external party communications')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">External Communications</h2>
                <p class="text-sm text-slate-500 mt-1">Track formal information shared with external parties</p>
            </div>
            <button onclick="openModal('newCommModal')" class="premium-button">
                <i class="fa-solid fa-plus"></i>
                <span>New Request</span>
            </button>
        </div>

        <!-- Communications List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">REF</th>
                            <th class="text-left font-bold text-slate-400">External Party</th>
                            <th class="text-left font-bold text-slate-400">Subject</th>
                            <th class="text-left font-bold text-slate-400">Type</th>
                            <th class="text-center font-bold text-slate-400">Status</th>
                            <th class="text-center font-bold text-slate-400">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($requests as $req)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <span
                                        class="font-mono text-xs font-bold text-teal-600 bg-teal-50 px-2 py-1 rounded">{{ $req->communication_code }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                            <i class="fa-solid fa-building text-[10px]"></i>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">{{ $req->external_party_name }}</span>
                                    </div>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-500 truncate" title="{{ $req->communication_subject }}">
                                        {{ $req->communication_subject }}
                                    </p>
                                </td>
                                <td>
                                    <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2 py-1 rounded-lg">
                                        {{ $req->type->communication_type_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                        style="background: #{{ $req->status->status_color ?? '64748b' }};">
                                        {{ $req->status->communication_status_name ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('emp.communications.show', $req->communication_id) }}"
                                        class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-brand-dark hover:text-white transition-all shadow-sm mx-auto">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20">
                                    <i class="fa-solid fa-earth-americas text-5xl text-slate-100 mb-4"></i>
                                    <p class="text-slate-400 font-medium">No external communication requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- New Comm Modal -->
    <div class="modal" id="newCommModal">
        <div class="modal-backdrop" onclick="closeModal('newCommModal')"></div>
        <div class="modal-content max-w-2xl p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-display font-bold text-premium">New Communication Request</h2>
                <button onclick="closeModal('newCommModal')"
                    class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form action="{{ route('emp.communications.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">External
                            Party Name</label>
                        <input type="text" name="external_party_name" class="premium-input w-full"
                            placeholder="e.g. Ministry of Labor" required>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Communication
                            Type</label>
                        <select name="communication_type_id" class="premium-input w-full" required>
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->communication_type_id }}">{{ $type->communication_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label
                        class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Subject</label>
                    <input type="text" name="communication_subject" class="premium-input w-full"
                        placeholder="Brief summary of the communication" required>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Description</label>
                        <textarea name="communication_description" rows="5" class="premium-input w-full"
                            placeholder="Detail the purpose..." required></textarea>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Information
                            Shared</label>
                        <textarea name="information_shared" rows="5" class="premium-input w-full"
                            placeholder="What specific data was shared?" required></textarea>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('newCommModal')"
                        class="px-6 py-3 font-bold text-slate-500 hover:bg-slate-50 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="premium-button">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
