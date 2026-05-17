@extends('layouts.app')

@section('title', 'Finalize Dispatch: ' . ($record->communication_code ?? 'Pending'))
@section('subtitle', 'Review details and dispatch Outbound Communication')

@section('content')
<div class="space-y-6 animate-fade-in-up pb-20">

    <!-- Header -->
    <div class="premium-card p-8 bg-gradient-to-r from-sky-900 to-indigo-900 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-10 p-8 text-9xl">
            <i class="fa-solid fa-paper-plane"></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold uppercase tracking-widest">
                    {{ $record->type->communication_type_name ?? 'Outbound' }}
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold uppercase tracking-widest">
                    {{ $record->priority }} Priority
                </span>
            </div>
            <h1 class="text-3xl font-display font-bold mb-2">{{ $record->communication_subject }}</h1>
            <p class="text-white/70 max-w-2xl">{{ $record->communication_description }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Details Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Initiator details -->
            <div class="premium-card p-6 flex items-center gap-4 bg-slate-50 border border-slate-100">
                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                    {{ substr($record->employee->employee_name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Initiated By</h3>
                    <p class="font-bold text-slate-700">{{ $record->employee->employee_name ?? 'Unknown' }}</p>
                    <p class="text-xs text-slate-500">{{ $record->employee->department->department_name ?? 'No Department' }}</p>
                </div>
            </div>

            <!-- Approvals Log -->
            <div class="premium-card p-6 border-l-4 border-green-500">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Approval Notes</h3>
                
                @if($record->approved_1_notes)
                <div class="mb-4">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Line Manager:</span>
                    <p class="text-sm text-slate-700 italic bg-white p-3 rounded-xl border border-slate-100 mt-1">"{{ $record->approved_1_notes }}"</p>
                </div>
                @endif
                
                @if($record->approved_2_notes)
                <div>
                    <span class="text-[10px] font-bold text-amber-500 uppercase">General Manager:</span>
                    <p class="text-sm text-slate-700 italic bg-white p-3 rounded-xl border border-slate-100 mt-1">"{{ $record->approved_2_notes }}"</p>
                </div>
                @endif
            </div>

            <!-- Original Attachments -->
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Draft Attachments</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($record->attachments as $file)
                        <div onclick="window.previewRemoteFile('{{ asset('storage/' . $file->file_path) }}', '{{ $file->file_name }}')" class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-indigo-300 hover:shadow-md transition-all cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-[120px]">{{ $file->file_name }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase">{{ $file->file_type }}</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-download text-slate-300 group-hover:text-indigo-500"></i>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No attachments provided.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Action Column -->
        <div class="space-y-6">
            <div class="premium-card p-6 border-t-4 border-indigo-500 sticky top-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-6">Finalize Dispatch</h3>
                
                <form action="{{ route('emp.outbound-liaison.finalize', $record->communication_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Entity Code</label>
                        <input type="text" name="external_party_code" class="premium-input w-full" placeholder="e.g. MOE" required>
                        <p class="mt-1 text-[10px] text-slate-400 italic">For Ref Gen: [CODE]/{{ date('Y/m') }}/OUT/...</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Final Signed Document</label>
                        <input type="file" name="final_file" id="final_file_input" class="premium-input w-full p-2 text-xs" accept=".pdf,.jpg,.png,.docx" required>
                        <div id="final_file_preview" class="mt-2"></div>
                    </div>

                    <button type="submit" class="w-full py-3 premium-button bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase hover:scale-[1.02] transition-all">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Finalize Dispatch
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('js/attachment-preview.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.initAttachmentPreview) {
            window.initAttachmentPreview({
                inputSelector: '#final_file_input',
                containerSelector: '#final_file_preview'
            });
        }
    });
</script>
@endsection
