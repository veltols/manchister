@extends('rc.portal.program_registration._layout')
@php $pageTitle = 'Faculty Details (Program Registration)'; @endphp

@section('acc-content')

    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-slate-500">Upload CVs and Certificates for the faculty members.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Experience</th>
                    <th>CV</th>
                    <th>Certificate</th>
                    <th style="text-align:center;"><i class="fa-solid fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($faculties as $f)
                    <tr>
                        <td class="font-semibold">{{ $f->faculty_name }}</td>
                        <td>{{ optional($facultyTypes->firstWhere('faculty_type_id', $f->faculty_type_id))->faculty_type_name ?? $f->faculty_type_id }}
                        </td>
                        <td>{{ $f->years_experience }} yrs</td>
                        <td>
                            @if($f->faculty_cv)
                                <a href="{{ asset('storage/' . $f->faculty_cv) }}" target="_blank"
                                    class="text-brand font-semibold text-xs hover:underline flex items-center gap-1"><i
                                        class="fa-solid fa-file-pdf"></i> View CV</a>
                            @else
                                <span class="text-slate-400 text-xs italic">Not uploaded</span>
                            @endif
                        </td>
                        <td>
                            @if($f->faculty_certificate)
                                <a href="{{ asset('storage/' . $f->faculty_certificate) }}" target="_blank"
                                    class="text-brand font-semibold text-xs hover:underline flex items-center gap-1"><i
                                        class="fa-solid fa-file-invoice"></i> View Cert</a>
                            @else
                                <span class="text-slate-400 text-xs italic">Not uploaded</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button onclick="openUploadModal({{ $f->faculty_id }}, '{{ addslashes($f->faculty_name) }}')"
                                class="btn-save py-1.5 px-3 text-[10px] rounded-lg">
                                <i class="fa-solid fa-upload"></i> Upload Docs
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-400 py-8 text-sm">No faculty members found. Please add
                            them from the initial registration phase.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Upload Modal --}}
    <div id="uploadModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="text-slate-800 font-bold text-base">Upload Documents</h3>
                <button onclick="closeUploadModal()"
                    class="text-slate-400 hover:text-red-500 transition-colors text-xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('rc.portal.program_registration.faculty.upload_cv') }}"
                enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="faculty_id" id="modal_faculty_id">

                <div class="form-group mb-6">
                    <label class="form-label">Faculty Name</label>
                    <input type="text" id="modal_faculty_name" class="form-input bg-slate-100" readonly disabled>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Faculty CV (PDF/DOC) <span class="req">*</span></label>
                    <div class="relative">
                        <input type="file" name="faculty_cv" id="faculty_cv" class="form-input py-2 pl-10" accept=".pdf,.doc,.docx"
                            required>
                        <i class="fa-solid fa-file-pdf absolute left-3.5 top-3.5 text-slate-400"></i>
                    </div>
                    <div id="preview_cv" class="mt-2"></div>
                </div>

                <div class="form-group mb-6">
                    <label class="form-label">Faculty Certificate (PDF/IMG) <span class="req">*</span></label>
                    <div class="relative">
                        <input type="file" name="faculty_certificate" id="faculty_certificate" class="form-input py-2 pl-10"
                            accept=".pdf,.jpg,.jpeg,.png" required>
                        <i class="fa-solid fa-certificate absolute left-3.5 top-3.5 text-slate-400"></i>
                    </div>
                    <div id="preview_certificate" class="mt-2"></div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeUploadModal()"
                        class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancel</button>
                    <button type="submit" class="btn-save"><i class="fa-solid fa-cloud-arrow-up"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openUploadModal(id, name) {
            document.getElementById('modal_faculty_id').value = id;
            document.getElementById('modal_faculty_name').value = name;
            document.getElementById('uploadModal').classList.remove('hidden');
        }
        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }
    </script>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.initAttachmentPreview) {
                // Initialize preview for Faculty CV
                window.initAttachmentPreview({
                    inputSelector: '#faculty_cv',
                    containerSelector: '#preview_cv'
                });
                
                // Initialize preview for Faculty Certificate
                window.initAttachmentPreview({
                    inputSelector: '#faculty_certificate',
                    containerSelector: '#preview_certificate'
                });
            }
        });
    </script>
@endpush