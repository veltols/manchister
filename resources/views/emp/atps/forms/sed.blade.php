@extends('layouts.app')

@section('content')
    <div class="p-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-0.5 shadow-lg shadow-indigo-100">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                            @if($atp->atp_logo)
                                <img src="{{ asset('uploads/' . $atp->atp_logo) }}" class="w-10 h-10 object-contain">
                            @else
                                <i class="fa-solid fa-file-signature text-2xl text-slate-300"></i>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-premium tracking-tight">{{ $atp->atp_name }}</h1>
                        <p class="text-slate-500 font-medium">Self Evaluation Document (SED)</p>
                    </div>
                </div>
                <a href="{{ route('emp.atps.show', $atp->atp_id) }}"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to ATP
                </a>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($standardsData as $std)
                    @php
                        $color = $std['score'] > 80 ? 'emerald' : ($std['score'] > 35 ? 'amber' : 'red');
                    @endphp
                    <a href="{{ route('emp.atps.forms.compliance', [$atp->atp_id, $std['main_id']]) }}"
                        class="premium-card p-6 hover:scale-[1.02] hover:shadow-xl hover:shadow-{{ $color }}-100/20 transition-all duration-300 group">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-{{ $color }}-50 flex items-center justify-center text-{{ $color }}-600 group-hover:bg-{{ $color }}-600 group-hover:text-white transition-all duration-300">
                                <i class="fa-solid {{ $std['icon'] }} text-xl"></i>
                            </div>
                            <div class="text-3xl font-black text-{{ $color }}-600">{{ $std['score'] }}%</div>
                        </div>
                        <h3 class="font-bold text-premium leading-tight">{{ $std['title'] }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">View Compliance Details
                        </p>
                    </a>
                @endforeach

                <!-- Total Score -->
                <div class="premium-card p-6 bg-gradient-to-br from-slate-900 to-slate-800 border-none">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                            <i class="fa-solid fa-bullseye text-xl"></i>
                        </div>
                        <div class="text-3xl font-black text-white">{{ $avgScore }}%</div>
                    </div>
                    <h3 class="font-bold text-white/90 leading-tight">Overall ATPQS Score</h3>
                    <div class="w-full bg-white/10 h-1.5 rounded-full mt-4 overflow-hidden">
                        <div class="bg-brand h-full rounded-full" style="width: {{ $avgScore }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Organization Profile -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="premium-card p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i class="fa-solid fa-sitemap"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Organization Profile</h2>
                        </div>

                        <div class="grid grid-cols-2 gap-8 mb-10">
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Author
                                    of SED</label>
                                <p class="font-bold text-premium">{{ $sedForm->sed_1 ?? '---' }}</p>
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Role</label>
                                <p class="font-bold text-premium">{{ $sedForm->sed_2 ?? '---' }}</p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            @php
                                $sections = [
                                    'sed_4' => 'Overview',
                                    'sed_6' => 'Background Information',
                                    'sed_8' => 'Methodology for Self-Evaluation',
                                    'sed_10' => 'Overall Aims & Objectives',
                                    'sed_12' => 'Overview of Curriculum Delivery',
                                    'sed_14' => 'Future Development Plans'
                                ];
                            @endphp

                            @foreach($sections as $field => $label)
                                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">{{ $label }}</label>
                                    <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap">
                                        {{ $sedForm->$field ?? 'No information provided.' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Actions Panel -->
                @php 
                    $currStatus = $sedForm->form_status ?? 'pending';
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
                                placeholder="Add comments here...">{{ $sedForm->rc_comment ?? '' }}</textarea>

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
                            This application is currently in <strong>{{ strtoupper($sedForm->form_status) }}</strong> status and
                            cannot be modified at this stage.
                        </p>
                        @if($sedForm->rc_comment)
                            <div class="mt-6 p-6 bg-white rounded-2xl border border-slate-100">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Previous
                                    Comment</label>
                                <p class="text-sm text-slate-600 italic">"{{ $sedForm->rc_comment }}"</p>
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
                            form_type: 'sed'
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