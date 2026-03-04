@extends('rc.portal.accreditation._layout')
@php $pageTitle = 'Initial Form';
$saveTarget = true; @endphp

@section('acc-content')
    <form id="mainForm" method="POST" action="{{ route('rc.portal.accreditation.initial_form.save') }}">
        @csrf

        {{-- Basic Information --}}
        <p class="form-section-title">Basic Information</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="form-group">
                <label class="form-label">Institution Name <span class="req">*</span></label>
                <input type="text" name="est_name" class="form-input"
                    value="{{ old('est_name', $existing->est_name ?? $atp->atp_name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Institution Name (AR) <span class="req">*</span></label>
                <input type="text" name="est_name_ar" class="form-input" dir="rtl"
                    value="{{ old('est_name_ar', $existing->est_name_ar ?? $atp->atp_name_ar ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">IQA Lead name <span class="req">*</span></label>
                <input type="text" name="iqa_name" class="form-input"
                    value="{{ old('iqa_name', $existing->iqa_name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" value="{{ $atp->atp_email }}" disabled readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Sector <span class="req">*</span></label>
                <select name="atp_category_id" class="form-select" required>
                    <option value="">— Select —</option>
                    @foreach($sectors as $sector)
                        <option value="{{ $sector->atp_category_id }}"
                            {{ old('atp_category_id', $existing->atp_category_id ?? $atp->atp_category_id) == $sector->atp_category_id ? 'selected' : '' }}>
                            {{ $sector->atp_category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Trade License Expiry date <span class="req">*</span></label>
                <input type="date" name="registration_expiry" class="form-input"
                    value="{{ old('registration_expiry', $existing->registration_expiry ?? '') }}" required>
            </div>

        </div>

        {{-- Address Details --}}
        <p class="form-section-title">Address Details</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="form-group md:col-span-3">
                <label class="form-label">Emirate</label>
                <input type="text" class="form-input" value="{{ $emirateName }}" disabled readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Area <span class="req">*</span></label>
                <input type="text" name="area_name" class="form-input"
                    value="{{ old('area_name', $existing->area_name ?? $atp->area_name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Street <span class="req">*</span></label>
                <input type="text" name="street_name" class="form-input"
                    value="{{ old('street_name', $existing->street_name ?? $atp->street_name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Building NO <span class="req">*</span></label>
                <input type="text" name="building_name" class="form-input"
                    value="{{ old('building_name', $existing->building_name ?? $atp->building_name ?? '') }}" required>
            </div>

        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk text-xs"></i> Save Initial Form
            </button>
        </div>
    </form>
@endsection