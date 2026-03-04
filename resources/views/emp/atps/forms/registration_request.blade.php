@extends('layouts.app')

@section('content')
    <div class="p-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand to-brand-dark p-0.5 shadow-lg shadow-brand/20">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                            @if($atp->atp_logo)
                                <img src="{{ asset('uploads/' . $atp->atp_logo) }}" class="w-10 h-10 object-contain">
                            @else
                                <i class="fa-solid fa-building text-2xl text-slate-300"></i>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-premium tracking-tight">{{ $atp->atp_name }}</h1>
                        <p class="text-slate-500 font-medium">Registration Request Details</p>
                    </div>
                </div>
                <a href="{{ route('emp.atps.show', $atp->atp_id) }}"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to ATP
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Registration Info -->
                    <div class="premium-card p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">General Information</h2>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Request
                                    Status</label>
                                <span
                                    class="px-4 py-1.5 rounded-lg text-xs font-bold {{ $regReq && $regReq->is_submitted ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }} uppercase">
                                    {{ $regReq->form_status ?? 'Pending' }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Submitted
                                    Date</label>
                                <p class="font-bold text-premium">{{ $regReq->submitted_date ?? 'Not Submitted' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Locations -->
                    <div class="premium-card overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <h2 class="text-xl font-bold text-premium">Location Details</h2>
                            </div>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Name</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Classrooms</th>
                                    <th
                                        class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                        Floor Map</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($locations as $loc)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-5 font-bold text-premium">{{ $loc->location_name }}</td>
                                        <td class="px-8 py-5 text-sm font-medium text-slate-600">{{ $loc->classrooms_count }}
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            @if($loc->floor_map)
                                                <a href="{{ asset('uploads/' . $loc->floor_map) }}" target="_blank"
                                                    class="text-brand hover:underline font-bold text-sm">View File</a>
                                            @else
                                                <span class="text-slate-300 italic text-xs">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-10 text-center text-slate-400 italic">No locations
                                            recorded</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Qualifications -->
                    <div class="premium-card overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Qualifications Offered</h2>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @forelse($qualifications as $q)
                                <div class="p-8 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-premium mb-1">{{ $q->qualification_name }}</h3>
                                        <div class="flex items-center gap-4">
                                            <span class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                                                <i class="fa-solid fa-building-columns text-[10px]"></i>
                                                {{ $q->qualification_category }}
                                            </span>
                                            <span class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                                                <i class="fa-solid fa-tag text-[10px]"></i>
                                                {{ $q->qualification_type }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Credits</div>
                                        <span
                                            class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-bold text-brand">{{ $q->qulaification_credits ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-10 text-center text-slate-400 italic font-medium">No qualifications listed</div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Sidebar Info -->
                <div class="space-y-8">

                    <!-- Learner Projections -->
                    <div class="premium-card p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Projections</h2>
                        </div>

                        <div class="space-y-6">
                            @php $projYears = [date('Y') + 1, date('Y') + 2, date('Y') + 3]; @endphp
                            @foreach($projYears as $index => $year)
                                @php $field = 'le' . ($index + 7); @endphp
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Year
                                            {{ $year }}</p>
                                        <p class="text-sm font-bold text-premium">Expected Learners</p>
                                    </div>
                                    <div class="text-xl font-black text-brand">{{ $learnerEnrolled->$field ?? 0 }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Faculty Summary -->
                    <div class="premium-card p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i class="fa-solid fa-users-gear"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Faculty</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl">
                                <span class="text-sm font-bold text-slate-600">Trainers</span>
                                <span
                                    class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">{{ $trainers->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl">
                                <span class="text-sm font-bold text-slate-600">Assessors</span>
                                <span
                                    class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">{{ $assessors->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl">
                                <span class="text-sm font-bold text-slate-600">IQA Staff</span>
                                <span
                                    class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">{{ $iqas->count() }}</span>
                            </div>
                        </div>

                        <a href="{{ route('emp.atps.forms.faculty', $atp->atp_id) }}"
                            class="w-full mt-6 py-3 border border-slate-200 rounded-xl text-center text-xs font-bold text-slate-500 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                            View Detailed Faculty
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Actions Panel -->
                    @php 
                        $currStatus = $regReq->form_status ?? 'pending';
                        $isActionable = in_array($currStatus, ['pending', 'submitted', 'rejected']);
                    @endphp
                    
                    @if($isActionable)
                    <div class="premium-card p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                                <i class="fa-solid fa-gavel"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Actions</h2>
                        </div>

                        <div class="space-y-6">
                            <textarea id="rc_comment" rows="4"
                                class="w-full bg-slate-50 border-none rounded-2xl p-6 text-sm font-medium focus:ring-2 focus:ring-brand/20 transition-all"
                                placeholder="Add comments here...">{{ $regReq->rc_comment ?? '' }}</textarea>

                            <div class="grid grid-cols-1 gap-3">
                                <button onclick="updateFormStatus('approved')"
                                    class="p-4 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center gap-2 group">
                                    <i class="fa-solid fa-check text-xs"></i>
                                    <span class="text-xs font-bold uppercase tracking-widest">Approve</span>
                                </button>
                                <button onclick="updateFormStatus('rejected')"
                                    class="p-4 rounded-xl bg-red-50 text-red-600 border border-red-100 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center gap-2 group">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                    <span class="text-xs font-bold uppercase tracking-widest">Reject</span>
                                </button>
                                <button onclick="updateFormStatus('review')"
                                    class="p-4 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 hover:bg-amber-600 hover:text-white transition-all flex items-center justify-center gap-2 group">
                                    <i class="fa-solid fa-rotate text-xs"></i>
                                    <span class="text-xs font-bold uppercase tracking-widest">Amend</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="premium-card p-8 bg-slate-50/50">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <h2 class="text-xl font-bold text-slate-400">Actions Locked</h2>
                        </div>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">
                            This application is currently in <strong>{{ strtoupper($regReq->form_status) }}</strong> status and cannot be modified at this stage.
                        </p>
                        @if($regReq->rc_comment)
                        <div class="mt-6 p-6 bg-white rounded-2xl border border-slate-100">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Previous Comment</label>
                            <p class="text-sm text-slate-600 italic">"{{ $regReq->rc_comment }}"</p>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <script>
        function updateFormStatus(status) {
            const comment = document.getElementById('rc_comment').value;

            if ((status === 'rejected' || status === 'review') && !comment.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Comment Required',
                    text: 'Please provide a comment for this action.',
                    confirmButtonColor: '#10b981'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to change the status to ${status.toUpperCase()}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('emp.atps.forms.initial.status', $atp->atp_id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status,
                            rc_comment: comment,
                            form_type: 'program'
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Update Failed',
                                    text: data.message || 'Something went wrong while updating the status.'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'System Error',
                                text: 'Failed to communicate with the server. Please check the logs.'
                            });
                        });
                }
            });
        }
    </script>
@endsection