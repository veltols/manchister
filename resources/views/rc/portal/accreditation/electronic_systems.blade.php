@extends('rc.portal.accreditation._layout')
@php $pageTitle = 'Electronic Systems & Platforms';
$saveTarget = true; @endphp

@section('acc-content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 mt-4">
        <div>
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Electronic Systems and Platforms</h2>
            <p class="text-xs text-slate-500 mt-1">List all systems used for learning, student management, etc.</p>
        </div>
        <button onclick="document.getElementById('platformModal').classList.remove('hidden')" class="btn-save text-xs py-1.5 px-3">
            <i class="fa-solid fa-plus mr-1"></i> Add Platform
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Platform Name</th>
                    <th>Purpose</th>
                    <th style="text-align:center;"><i class="fa-solid fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($platforms as $p)
                    <tr>
                        <td class="font-semibold">{{ $p->platform_name }}</td>
                        <td>{{ $p->platform_purpose }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('rc.portal.accreditation.electronic_systems.delete', $p->platform_id) }}" onsubmit="return confirm('Delete this platform?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-slate-400 py-10 text-sm">No electronic systems added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div id="platformModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-slate-800 font-bold text-base">Add Electronic System</h3>
                <button onclick="document.getElementById('platformModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('rc.portal.accreditation.electronic_systems.save') }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label class="form-label">platform_name <span class="req">*</span></label>
                        <input type="text" name="platform_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">platform_purpose <span class="req">*</span></label>
                        <textarea name="platform_purpose" class="form-textarea" rows="4" required></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('platformModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700">Cancel</button>
                    <button type="submit" class="btn-save">Add Platform</button>
                </div>
            </form>
        </div>
    </div>
@endsection