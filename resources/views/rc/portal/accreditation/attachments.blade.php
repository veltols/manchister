@extends('rc.portal.accreditation._layout')
@php $pageTitle = 'Attachments';
$saveTarget = true; @endphp

@section('acc-content')
    <form id="mainForm" method="POST" action="{{ route('rc.portal.accreditation.attachments.save') }}" enctype="multipart/form-data">
        @csrf

        <p class="text-sm text-slate-500 mb-5">Upload the required documents to complete your accreditation application.</p>

        {{-- Attachments Loop --}}
        @php
            $fields = [
                'delivery_plan' => 'delivery_plan',
                'org_chart' => 'Approved_Organization_Chart',
                'site_plan' => 'Site_Plan',
                'sed_form' => 'SED_Form',
                'atp_logo' => 'Logo'
            ];
        @endphp

        @foreach($fields as $name => $label)
            <div class="premium-card p-5 mb-4" style="border-radius:14px;">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white"
                        style="background:linear-gradient(135deg,#004F68,#006a8a);">
                        <i class="fa-solid fa-file-pdf text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 capitalize-first">{{ str_replace('_', ' ', $label) }}</p>
                        <p class="text-xs text-slate-400">PDF preferred @if($name == 'atp_logo') (Image allowed) @endif</p>
                    </div>
                    @if(!empty($existing->$name))
                        <span class="ml-auto inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-[10px]"></i> Uploaded
                        </span>
                    @endif
                </div>

                @if(!empty($existing->$name))
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 border border-slate-100 mb-3">
                        <i class="fa-solid fa-file text-slate-400 text-sm"></i>
                        <span class="text-xs text-slate-600 font-medium truncate">{{ basename($existing->$name) }}</span>
                        <a href="{{ asset('storage/' . $existing->$name) }}" target="_blank"
                            class="ml-auto text-xs font-bold text-brand hover:underline flex-shrink-0">
                            <i class="fa-solid fa-eye text-[10px] mr-1"></i>View Current
                        </a>
                    </div>
                @endif

                <input type="file" name="{{ $name }}" id="file_{{ $name }}" class="attachment-input block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-xs file:font-bold
                              file:bg-brand/10 file:text-brand
                              hover:file:bg-brand/20 cursor-pointer">
                
                <div id="preview_{{ $name }}" class="mt-2"></div>
            </div>
        @endforeach

        <div class="flex justify-end mt-4 pb-10">
            <button type="submit" class="btn-save shadow-lg shadow-brand/20">
                <i class="fa-solid fa-floppy-disk text-xs"></i> Save All Attachments
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/mammoth/mammoth.browser.min.js') }}"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.initAttachmentPreview) {
                const fields = @json(array_keys($fields));
                fields.forEach(field => {
                    window.initAttachmentPreview({
                        inputSelector: `#file_${field}`,
                        containerSelector: `#preview_${field}`
                    });
                });
            }
        });
    </script>
@endpush
