@extends('rc.portal.program_registration._layout')
@php $pageTitle = 'Qualification Mapping'; @endphp

@section('acc-content')

    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-slate-500">Map targeted qualifications to the assigned faculty members.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Qualification Name</th>
                    <th>Type</th>
                    <th>Mapped Faculties</th>
                    <th style="text-align:center;"><i class="fa-solid fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($qualifications as $q)
                    <tr>
                        <td class="font-semibold">{{ $q->qualification_name }}</td>
                        <td>{{ $q->qualification_type }}</td>
                        <td>
                            @if(isset($q->mapped_faculty_names) && $q->mapped_faculty_names !== 'None')
                                <div class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-lg inline-block break-all max-w-[200px] truncate">
                                    {{ $q->mapped_faculty_names }}
                                </div>
                            @else
                                <span class="bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1.5 rounded-lg inline-block">None</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $encodedNames = htmlentities(json_encode(isset($q->mapped_faculty_names) ? $q->mapped_faculty_names : 'None'), ENT_QUOTES, 'UTF-8');
                                $encodedIds = htmlentities(json_encode(isset($q->mapped_faculty_ids) ? $q->mapped_faculty_ids : []), ENT_QUOTES, 'UTF-8');
                            @endphp
                            <button onclick="openMapModal({{ $q->qualification_id }}, '{{ addslashes($q->qualification_name) }}', {{ $encodedIds }})" class="btn-save py-1.5 px-3 text-[10px] rounded-lg bg-indigo-600 hover:bg-indigo-700">
                                <i class="fa-solid fa-user-plus"></i> Map Faculty
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-slate-400 py-8 text-sm">No qualifications found. Please add them from the initial registration phase.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Map Modal --}}
    <div id="mapModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="text-slate-800 font-bold text-base">Map Faculty to Qualification</h3>
                <button onclick="closeMapModal()" class="text-slate-400 hover:text-red-500 transition-colors text-xl leading-none">&times;</button>
            </div>
            
            <form method="POST" action="{{ route('rc.portal.program_registration.qualification_mapping.save') }}" class="p-6">
                @csrf
                <input type="hidden" name="qualification_id" id="modal_qual_id">
                
                <div class="form-group mb-6">
                    <label class="form-label">Qualification Name</label>
                    <input type="text" id="modal_qual_name" class="form-input bg-slate-100 font-semibold" readonly disabled>
                </div>
                
                <div class="form-group mb-6">
                    <label class="form-label">Select Faculty Members <span class="req">*</span></label>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 max-h-48 overflow-y-auto mt-2">
                        @if($faculties->isEmpty())
                            <p class="text-xs text-slate-400 italic">No faculty available to map.</p>
                        @endif
                        @foreach($faculties as $f)
                            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-colors border border-transparent hover:border-slate-200">
                                <input type="checkbox" name="faculty_id[]" value="{{ $f->faculty_id }}" class="faculty-checkbox w-4 h-4 text-brand rounded border-slate-300 focus:ring-brand">
                                <span class="text-sm font-semibold text-slate-700">{{ $f->faculty_name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeMapModal()" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancel</button>
                    <button type="submit" class="btn-save bg-indigo-600 hover:bg-indigo-700 border-indigo-700"><i class="fa-solid fa-link"></i> Save Mapping</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openMapModal(id, name, mappedIdsStr) {
            document.getElementById('modal_qual_id').value = id;
            document.getElementById('modal_qual_name').value = name;
            
            const checkboxes = document.querySelectorAll('.faculty-checkbox');
            checkboxes.forEach(cb => cb.checked = false);

            if(mappedIdsStr && mappedIdsStr.length > 0) {
                mappedIdsStr.forEach(fid => {
                    const cb = document.querySelector(`.faculty-checkbox[value="${fid}"]`);
                    if(cb) cb.checked = true;
                });
            }

            document.getElementById('mapModal').classList.remove('hidden');
        }
        function closeMapModal() {
            document.getElementById('mapModal').classList.add('hidden');
        }
    </script>
@endsection
