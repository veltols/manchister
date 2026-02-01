@extends('layouts.app')

@section('title', 'Training Providers')
@section('subtitle', 'Manage Accredited Training Providers')

@section('content')
<div class="space-y-6">

    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">Training Providers</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $providers->total() }} accredited providers</p>
        </div>
        <a href="{{ route('rc.atps.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Provider</span>
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="premium-card p-4">
        <form action="{{ route('rc.atps.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-search absolute left-4 top-3.5 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Name, Reference, Contact Person..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 outline-none transition-all">
            </div>
            <button type="submit" class="px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-700 transition-colors shadow-lg">
                Search
            </button>
        </form>
    </div>

    <!-- Providers Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Reference</th>
                        <th class="text-left">Details</th>
                        <th class="text-left">Contact Info</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($providers as $atp)
                    <tr>
                        <td><span class="font-mono text-sm font-semibold text-slate-600">#{{ $atp->atp_ref }}</span></td>
                        <td>
                            <div>
                                <h3 class="font-semibold text-slate-800">{{ $atp->atp_name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-phone mr-1"></i>{{ $atp->atp_phone }}</p>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-medium text-slate-700"><i class="fa-solid fa-user text-xs text-slate-400 mr-2"></i>{{ $atp->contact_name }}</span>
                                <span class="text-sm text-slate-500"><i class="fa-solid fa-envelope text-xs text-slate-400 mr-2"></i>{{ $atp->atp_email }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $statusColors = [
                                    'Active' => 'bg-green-50 text-green-700',
                                    'Inactive' => 'bg-red-50 text-red-700',
                                    'Pending' => 'bg-yellow-50 text-yellow-700',
                                    'New' => 'bg-blue-50 text-blue-700'
                                ];
                                $status = $atp->status->status_name ?? 'Unknown';
                                $colorClass = $statusColors[$status] ?? 'bg-slate-50 text-slate-600';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $colorClass }} text-xs font-bold shadow-sm">
                                <i class="fa-solid fa-circle text-[8px]"></i>
                                {{ $status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('rc.atps.show', $atp->atp_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-graduation-cap text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No training providers found</p>
                                <a href="{{ route('rc.atps.create') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">Create First Provider</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($providers->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $providers->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>
@endsection
