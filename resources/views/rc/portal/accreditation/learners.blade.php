@extends('rc.portal.accreditation._layout')
@php $pageTitle = 'Learners Statistics';
$saveTarget = true; @endphp

@section('acc-content')
    {{-- Registered Section --}}
    <div class="flex items-center justify-between mb-3 mt-4">
        <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Learners registered in Vocational Qualifications (if Applicable)</h2>
        <button onclick="openModal('registered')" class="btn-save text-xs py-1.5 px-3">
            <i class="fa-solid fa-plus mr-1"></i> Add Registered
        </button>
    </div>
    <div class="overflow-x-auto mb-10">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Qualification</th>
                    <th>{{ date('Y') - 4 }}</th>
                    <th>{{ date('Y') - 3 }}</th>
                    <th>{{ date('Y') - 2 }}</th>
                    <th>{{ date('Y') - 1 }}</th>
                    <th style="text-align:center;"><i class="fa-solid fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($statistics->where('statistic_type', 'registered') as $s)
                    <tr>
                        @php $q = $qualifications->where('qualification_id', $s->qualification_id)->first(); @endphp
                        <td class="font-semibold">{{ $q->qualification_name ?? 'Unknown' }}</td>
                        <td>{{ $s->y1_value }}</td>
                        <td>{{ $s->y2_value }}</td>
                        <td>{{ $s->y3_value }}</td>
                        <td>{{ $s->y4_value }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('rc.portal.accreditation.learners.delete', $s->statistic_id) }}" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-slate-400 py-6 text-sm">No registered statistics added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Awarded Section --}}
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Awarded Certificates in vocational Qualifications (if Applicable)</h2>
        <button onclick="openModal('awarded')" class="btn-save text-xs py-1.5 px-3">
            <i class="fa-solid fa-plus mr-1"></i> Add Awarded
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Qualification</th>
                    <th>{{ date('Y') - 3 }}</th>
                    <th>{{ date('Y') - 2 }}</th>
                    <th>{{ date('Y') - 1 }}</th>
                    <th>{{ date('Y') }}</th>
                    <th style="text-align:center;"><i class="fa-solid fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($statistics->where('statistic_type', 'awarded') as $s)
                    <tr>
                        @php $q = $qualifications->where('qualification_id', $s->qualification_id)->first(); @endphp
                        <td class="font-semibold">{{ $q->qualification_name ?? 'Unknown' }}</td>
                        <td>{{ $s->y1_value }}</td>
                        <td>{{ $s->y2_value }}</td>
                        <td>{{ $s->y3_value }}</td>
                        <td>{{ $s->y4_value }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('rc.portal.accreditation.learners.delete', $s->statistic_id) }}" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-slate-400 py-6 text-sm">No awarded statistics added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div id="statModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 id="modalTitle" class="text-slate-800 font-bold text-base">Add Statistic</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('rc.portal.accreditation.learners.save') }}" class="p-6">
                @csrf
                <input type="hidden" name="statistic_type" id="modalType">
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label class="form-label">Qualification <span class="req">*</span></label>
                        <select name="qualification_id" class="form-select" required>
                            <option value="">— Select —</option>
                            @foreach($qualifications as $q)
                                <option value="{{ $q->qualification_id }}">{{ $q->qualification_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label id="labelY1" class="form-label"></label>
                            <input type="number" name="y1_value" class="form-input" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label id="labelY2" class="form-label"></label>
                            <input type="number" name="y2_value" class="form-input" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label id="labelY3" class="form-label"></label>
                            <input type="number" name="y3_value" class="form-input" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label id="labelY4" class="form-label"></label>
                            <input type="number" name="y4_value" class="form-input" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700">Cancel</button>
                    <button type="submit" class="btn-save">Add Statistic</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(type) {
            const modal = document.getElementById('statModal');
            const title = document.getElementById('modalTitle');
            const typeInput = document.getElementById('modalType');
            const now = new Date().getFullYear();

            typeInput.value = type;
            if(type === 'registered') {
                title.innerText = 'Add Registered Learners Statistic';
                document.getElementById('labelY1').innerText = now - 4;
                document.getElementById('labelY2').innerText = now - 3;
                document.getElementById('labelY3').innerText = now - 2;
                document.getElementById('labelY4').innerText = now - 1;
            } else {
                title.innerText = 'Add Awarded Certificates Statistic';
                document.getElementById('labelY1').innerText = now - 3;
                document.getElementById('labelY2').innerText = now - 2;
                document.getElementById('labelY3').innerText = now - 1;
                document.getElementById('labelY4').innerText = now;
            }
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('statModal').classList.add('hidden');
        }
    </script>
@endsection