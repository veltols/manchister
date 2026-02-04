@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand to-brand-dark p-0.5 shadow-lg shadow-brand/20">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden text-brand">
                        <i class="fa-solid {{ $standard->main_icon ?? 'fa-shield-halved' }} text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-premium tracking-tight">{{ $atp->atp_name }}</h1>
                    <p class="text-slate-500 font-medium">{{ $standard->main_title ?? 'Compliance Details' }}</p>
                </div>
            </div>
            <a href="{{ route('emp.atps.forms.sed', $atp->atp_id) }}" 
               class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Back to SED
            </a>
        </div>

        <!-- Compliance List -->
        <div class="premium-card overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-premium">Standard Requirements</h2>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 grayscale opacity-50">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Yes</span>
                    </div>
                    <div class="flex items-center gap-1.5 grayscale opacity-50">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">No</span>
                    </div>
                    <div class="flex items-center gap-1.5 grayscale opacity-50">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">QIP</span>
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-slate-50">
                @forelse($complianceRecords as $rec)
                    @php
                        $statusMap = [
                            1 => ['label' => 'Yes', 'color' => 'emerald'],
                            2 => ['label' => 'No', 'color' => 'red'],
                            3 => ['label' => 'QIP', 'color' => 'amber'],
                            4 => ['label' => 'N/A', 'color' => 'slate'],
                        ];
                        $st = $statusMap[$rec->answer] ?? ['label' => 'Unknown', 'color' => 'slate'];
                    @endphp
                    <div class="p-8 hover:bg-slate-50/30 transition-all">
                        <div class="flex items-start justify-between gap-8 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-black tracking-widest uppercase">
                                        {{ $rec->cat_ref }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-{{ $st['color'] }}-50 text-{{ $st['color'] }}-600 border border-{{ $st['color'] }}-100 uppercase tracking-widest">
                                        {{ $st['label'] }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-premium leading-snug">{{ $rec->cat_description }}</h3>
                            </div>
                        </div>

                        @if($rec->evidence)
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex items-start gap-4">
                                <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-400 shrink-0">
                                    <i class="fa-solid fa-quote-left text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 italic">Evidence Provided</p>
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium italic">"{{ $rec->evidence }}"</p>
                                </div>
                            </div>
                        @endif

                        @if($rec->answer == 3 && $rec->qip_action)
                            <div class="mt-4 p-6 bg-amber-50 rounded-2xl border border-amber-100 flex items-start gap-4">
                                <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-amber-500 shrink-0">
                                    <i class="fa-solid fa-wrench text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1.5 italic font-black">Quality Improvement Plan (QIP)</p>
                                    <p class="text-sm text-amber-700 leading-relaxed font-medium">Action: {{ $rec->qip_action }}</p>
                                    <div class="mt-2 flex items-center gap-4">
                                        <span class="text-[10px] font-bold text-amber-500 tracking-wide">PRIORITY: {{ strtoupper($rec->qip_priority ?? 'MEDIUM') }}</span>
                                        <span class="text-[10px] font-bold text-amber-500 tracking-wide">OWNER: {{ strtoupper($rec->qip_ownership ?? 'N/A') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-20 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="fa-solid fa-magnifying-glass text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-premium">No Records Found</h3>
                        <p class="text-slate-400">Compliance data for this standard has not been processed yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
