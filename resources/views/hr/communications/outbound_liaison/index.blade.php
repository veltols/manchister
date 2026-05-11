@extends('layouts.app')

@section('title', 'Liaison: Outbound Dispatch')
@section('subtitle', 'Finalize dispatch and generate reference codes (Form 2)')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Info Card -->
        <div class="premium-card p-6 bg-brand text-white flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-display font-bold">Ready for Dispatch</h3>
                    <p class="text-xs text-white/70">These requests have been approved by the GM and require a final reference code and document upload.</p>
                </div>
            </div>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Initiator</th>
                            <th class="text-left">Subject</th>
                            <th class="text-center">GM Approval</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($records as $rec)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold">
                                            {{ substr($rec->employee->employee_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700">{{ $rec->employee->employee_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $rec->employee->department->department_name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm text-slate-600 font-medium truncate max-w-xs">{{ $rec->communication_subject }}</span>
                                        <span class="text-[10px] text-slate-400 italic">Approved on {{ $rec->approved_2_date ? date('Y-m-d H:i', $rec->approved_2_date) : 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-green-50 text-green-600">
                                        <i class="fa-solid fa-check-double mr-1"></i> GM Approved
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button onclick="openFinalizeModal({{ $rec->communication_id }}, '{{ addslashes($rec->communication_subject) }}')" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-brand transition-all shadow-lg">
                                        Finalize Dispatch
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-20 text-slate-400">
                                    <div class="flex flex-col items-center opacity-20">
                                        <i class="fa-solid fa-inbox text-6xl mb-4"></i>
                                        <p class="font-bold uppercase tracking-widest text-sm">No requests ready for dispatch</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        </div>
    </div>

    <!-- Finalize Modal -->
    <div class="modal" id="finalizeModal">
        <div class="modal-backdrop" onclick="closeModal('finalizeModal')"></div>
        <div class="modal-content max-w-lg p-8">
            <h2 class="text-2xl font-display font-bold text-premium mb-2">Finalize Dispatch</h2>
            <p id="modalSubject" class="text-slate-500 text-sm mb-8 italic"></p>

            <form id="finalizeForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">External Entity / Party Code</label>
                    <input type="text" name="external_party_code" class="premium-input w-full" placeholder="e.g. MOE, MOH, PRIV" required>
                    <p class="mt-1 text-[10px] text-slate-400 uppercase font-bold italic">Used for Reference Code Generation: [CODE]/{{ date('Y/m') }}/OUT/...</p>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Final Dispatched Document (Signed/Scanned)</label>
                    <input type="file" name="final_file" id="final_file_input" class="premium-input w-full" accept=".pdf,.jpg,.png,.docx" required>
                    <div id="final_file_preview"></div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-brand text-white rounded-2xl font-bold text-xs uppercase shadow-xl shadow-brand/20 hover:scale-[1.02] transition-all">
                        Complete & Dispatch
                    </button>
                </div>
            </form>
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

        function openFinalizeModal(id, subject) {
            document.getElementById('modalSubject').innerText = subject;
            document.getElementById('finalizeForm').action = `/hr/communications/outbound-liaison/${id}/finalize`;
            
            // Clear preview when opening modal
            const previewContainer = document.getElementById('final_file_preview');
            if (previewContainer) previewContainer.innerHTML = '';
            
            openModal('finalizeModal');
        }
    </script>
@endsection
