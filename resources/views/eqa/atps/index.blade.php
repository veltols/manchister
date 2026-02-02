@extends('layouts.app')

@section('title', 'Training Providers')
@section('subtitle', 'Manage External Training Providers')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                <a href="{{ route('eqa.atps.index', ['view' => 'list']) }}"
                    class="p-2 rounded-lg {{ $viewType == 'list' ? 'bg-indigo-100 text-indigo-600' : 'bg-white text-slate-400' }}">
                    <i class="fa-solid fa-list"></i>
                </a>
                <a href="{{ route('eqa.atps.index', ['view' => 'grid']) }}"
                    class="p-2 rounded-lg {{ $viewType == 'grid' ? 'bg-indigo-100 text-indigo-600' : 'bg-white text-slate-400' }}">
                    <i class="fa-solid fa-table-cells"></i>
                </a>
            </div>

            <a href="{{ route('eqa.atps.create') }}" class="premium-button px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> New Provider
            </a>
        </div>

        @if($viewType == 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($atps as $atp)
                    <div class="premium-card p-6 flex flex-col gap-4 hover:shadow-lg transition-all border-t-4 border-indigo-500">
                        <div class="flex justify-between items-start">
                            <span class="font-mono text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded">{{ $atp->atp_ref }}</span>
                            <span
                                class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">{{ $atp->status->status_name ?? 'Active' }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-premium">{{ $atp->atp_name }}</h3>
                        <div class="text-sm text-slate-500 space-y-1">
                            <p><i class="fa-regular fa-user w-5 text-center"></i> {{ $atp->contact_name }}</p>
                            <p><i class="fa-regular fa-envelope w-5 text-center"></i> {{ $atp->atp_email }}</p>
                            <p><i class="fa-solid fa-phone w-5 text-center"></i> {{ $atp->atp_phone }}</p>
                        </div>
                        <a href="{{ route('eqa.atps.show', $atp->atp_id) }}"
                            class="mt-auto w-full py-2 rounded-lg border border-slate-200 text-center font-bold text-slate-600 hover:bg-slate-50 transition-colors">Details</a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="premium-card overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="p-4">REF</th>
                            <th class="p-4">Provider Name</th>
                            <th class="p-4">Contact</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Phone</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($atps as $atp)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-4 font-mono text-xs text-slate-500">{{ $atp->atp_ref }}</td>
                                <td class="p-4 font-bold text-slate-800">{{ $atp->atp_name }}</td>
                                <td class="p-4 text-slate-600">{{ $atp->contact_name }}</td>
                                <td class="p-4 text-slate-500">{{ $atp->atp_email }}</td>
                                <td class="p-4 text-slate-500">{{ $atp->atp_phone }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        {{ $atp->status->status_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right flex justify-end gap-2">
                                    <a href="{{ route('eqa.atps.show', $atp->atp_id) }}"
                                        class="text-indigo-600 hover:text-indigo-800 font-bold text-xs uppercase">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <div class="pt-4">
            {{ $atps->appends(['view' => $viewType])->links() }}
        </div>
    </div>
@endsection
