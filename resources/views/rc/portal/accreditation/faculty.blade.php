@extends('rc.portal.accreditation._layout')
@php $pageTitle = 'Faculty Details'; @endphp

@section('acc-content')

    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-slate-500">List all faculty members and their qualifications.</p>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-save">
            <i class="fa-solid fa-plus text-xs"></i> Add Faculty Member
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Experience (yrs)</th>
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
                        <td>{{ $f->years_experience }}</td>
                        <td>{{ $f->certificate_name }}</td>
                        <td>
                            <form method="POST" action="{{ route('rc.portal.accreditation.faculty.delete', $f->faculty_id) }}"
                                onsubmit="return confirm('Delete this faculty member?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <i class="fa-solid fa-trash text-[10px]"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-400 py-8 text-sm">No faculty members added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center"
        style="background:rgba(0,0,0,0.45);">
        <div class="bg-white rounded-2xl w-full max-w-xl mx-4 overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-slate-800 font-bold text-base">Add Faculty Member</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>
            <form method="POST" action="{{ route('rc.portal.accreditation.faculty.save') }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">faculty_name <span class="req">*</span></label>
                        <input type="text" name="faculty_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">faculty_type <span class="req">*</span></label>
                        <select name="faculty_type_id" class="form-select" required>
                            <option value="">— Select —</option>
                            @foreach($facultyTypes as $ft)
                                <option value="{{ $ft->faculty_type_id }}">{{ $ft->faculty_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">educational_qualifications <span class="req">*</span></label>
                        <textarea name="educational_qualifications" class="form-textarea" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">years_of_experience <span class="req">*</span></label>
                        <input type="number" name="years_experience" class="form-input" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">certificate_name <span class="req">*</span></label>
                        <input type="text" name="certificate_name" class="form-input" required>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700">Cancel</button>
                    <button type="submit" class="btn-save"><i class="fa-solid fa-plus text-xs"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
@endsection