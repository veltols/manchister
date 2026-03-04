@extends('rc.portal.accreditation._layout')
@php $pageTitle = 'Targeted Qualifications'; @endphp

@section('acc-content')

    {{-- Add New Button --}}
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-slate-500">List all qualifications your institution intends to offer.</p>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-save">
            <i class="fa-solid fa-plus text-xs"></i> Add Qualification
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Qualification Name</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Emirates Level</th>
                    <th>Credits</th>
                    <th>Mode of Delivery</th>
                    <th style="text-align:center;"><i class="fa-solid fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($qualifications as $q)
                    <tr>
                        <td class="font-semibold">{{ $q->qualification_name }}</td>
                        <td>{{ $q->qualification_type }}</td>
                        <td>{{ $q->qualification_category }}</td>
                        <td>{{ $q->emirates_level }}</td>
                        <td>{{ $q->qulaification_credits }}</td>
                        <td>{{ $q->mode_of_delivery }}</td>
                        <td>
                            <form method="POST"
                                action="{{ route('rc.portal.accreditation.qualifications.delete', $q->qualification_id) }}"
                                onsubmit="return confirm('Delete this qualification?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <i class="fa-solid fa-trash text-[10px]"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-400 py-8 text-sm">
                            No qualifications added yet.
                            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                                class="btn-save ml-3 text-xs px-3 py-1.5">Add Now</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center"
        style="background:rgba(0,0,0,0.45);">
        <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-slate-800 font-bold text-base">Add New Qualification</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>
            <form method="POST" action="{{ route('rc.portal.accreditation.qualifications.save') }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group md:col-span-2">
                        <label class="form-label">qualification_name <span class="req">*</span></label>
                        <input type="text" name="qualification_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">qualification_type <span class="req">*</span></label>
                        <select name="qualification_type" class="form-select" required>
                            <option value="">— Select —</option>
                            <option value="Principal">Principal</option>
                            <option value="Award">Award</option>
                            <option value="CBMC">CBMC</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">qualification_category <span class="req">*</span></label>
                        <select name="qualification_category" class="form-select" required>
                            <option value="">— Select —</option>
                            <option value="National">National</option>
                            <option value="Recognized_Program">Recognized Program</option>
                            <option value="International_Qualification">International Qualification</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">qualification_Emirates Level <span class="req">*</span></label>
                        <select name="emirates_level" class="form-select" required>
                            <option value="">— Select —</option>
                            @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}">{{ $i }}</option> @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">qulaification_credits <span class="req">*</span></label>
                        <input type="text" name="qulaification_credits" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">mode_of_delivery <span class="req">*</span></label>
                        <select name="mode_of_delivery" class="form-select" required>
                            <option value="">— Select —</option>
                            <option value="Online">Online</option>
                            <option value="Onsite">Onsite</option>
                            <option value="Blended">Blended</option>
                        </select>
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