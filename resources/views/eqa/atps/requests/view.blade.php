@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="bg-white border-b border-slate-100 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}" 
                       class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Information Request #{{ $infoRequest->request_id }}</h1>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>{{ $atp->atp_name }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                            <span>{{ date('d M, Y', strtotime($infoRequest->request_date)) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest border
                        {{ $infoRequest->request_status == 'submitted' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                        {{ $infoRequest->request_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto bg-slate-50/50 p-6">
        <div class="max-w-5xl mx-auto space-y-6">
            
            <!-- Request Details Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Requested By</span>
                    <p class="text-sm font-bold text-slate-700">{{ $infoRequest->requester_first_name }} {{ $infoRequest->requester_last_name ?? '' }}</p>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Response Date</span>
                    <p class="text-sm font-bold text-slate-700">{{ $infoRequest->response_date ? date('d M, Y', strtotime($infoRequest->response_date)) : '-' }}</p>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Department</span>
                    <p class="text-sm font-bold text-slate-700">EQA Department</p>
                </div>
            </div>

            <!-- Evidence List -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Requested Evidence</h3>
                    <span class="text-xs font-bold text-slate-400">{{ count($evidenceItems) }} Items</span>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="w-16 px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-center">#</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Requirement</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">ATP Response / Comment</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-right">Evidence</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($evidenceItems as $index => $item)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold text-slate-400 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-700 w-1/3">
                                {{ $item->required_evidence }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $item->answer ?? 'Pending Response' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($item->required_attachment)
                                    <a href="{{ asset('uploads/' . $item->required_attachment) }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-brand/5 text-brand hover:bg-brand hover:text-white transition-all text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fa-solid fa-paperclip"></i> View File
                                    </a>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">No File</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
