@extends('layouts.app')

@section('title', 'Initial Registration Form')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-brand p-0.5 shadow-lg shadow-brand/20">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                        <i class="fa-solid fa-file-signature text-2xl text-brand"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-premium tracking-tight">Initial Registration</h1>
                    <p class="text-slate-500 font-medium">{{ $atp->atp_name }} ({{ $atp->atp_ref }})</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('emp.atps.show', $atp->atp_id) }}"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to ATP
                </a>
                <span
                    class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                    {{ $initForm->form_status ?? 'Pending' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Basic Information -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                            <i class="fa-solid fa-info-circle"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">Basic Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Establishment
                                Name</label>
                            <p class="font-bold text-premium text-lg">{{ $initForm->est_name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Establishment Name
                                (AR)</label>
                            <p class="font-bold text-premium text-lg" dir="rtl">{{ $initForm->est_name_ar ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">IQA Lead
                                Name</label>
                            <p class="font-bold text-premium">{{ $initForm->iqa_name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email
                                Address</label>
                            <p class="font-bold text-premium">{{ $initForm->email_address ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sector /
                                Category</label>
                            <p class="font-bold text-premium">{{ $atp->category->atp_category_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Details -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">Address Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Emirate</label>
                            <p class="font-bold text-premium">{{ $atp->emirate->city_name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Area Name</label>
                            <p class="font-bold text-premium">{{ $initForm->area_name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Street
                                Name</label>
                            <p class="font-bold text-premium">{{ $initForm->street_name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Building
                                NO</label>
                            <p class="font-bold text-premium">{{ $initForm->building_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contacts -->
                <div class="premium-card overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                                <i class="fa-solid fa-address-book"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Contacts List</h2>
                        </div>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Name
                                </th>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Position</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Contact
                                    Info</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($contacts as $contact)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="font-bold text-premium">{{ $contact->contact_name }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span
                                            class="text-xs font-bold text-slate-500 uppercase">{{ $contact->contact_designation }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-xs font-medium text-premium">{{ $contact->contact_email }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-1">{{ $contact->contact_phone }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-slate-400 italic">No contacts found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Targeted Qualifications -->
                <div class="premium-card overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Targeted Qualifications</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-slate-100">
                        @forelse($qualifications as $q)
                            <div class="bg-white p-8 space-y-4">
                                <h3 class="font-bold text-premium leading-tight">{{ $q->qualification_name }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase">Type</label>
                                        <p class="text-xs font-bold text-slate-600">{{ $q->qualification_type }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase">Level</label>
                                        <p class="text-xs font-bold text-slate-600">{{ $q->emirates_level }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase">Credits</label>
                                        <p class="text-xs font-bold text-slate-600">{{ $q->qulaification_credits }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase">Delivery</label>
                                        <p class="text-xs font-bold text-slate-600">{{ $q->mode_of_delivery }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 bg-white p-10 text-center text-slate-400 italic font-medium">No
                                qualifications listed</div>
                        @endforelse
                    </div>
                </div>

                <!-- Faculty Details -->
                <div class="premium-card overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                                <i class="fa-solid fa-users-gear"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Faculty Details</h2>
                        </div>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Name
                                </th>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type
                                </th>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Experience</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($faculty as $f)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="font-bold text-premium">{{ $f->faculty_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-1">
                                            {{ $f->educational_qualifications }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span
                                            class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500 uppercase">{{ $f->faculty_type_name }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="font-bold text-brand">{{ $f->years_experience }} Years</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-slate-400 italic">No faculty records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Sidebar Column -->
            <div class="space-y-8">

                <!-- Statistics -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">Learner Stats</h2>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Registered (last
                                4 years)</h4>
                            <div class="space-y-3">
                                @foreach($learnerStatsReg as $stat)
                                    <div class="p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
                                        <span
                                            class="text-xs font-bold text-slate-600 truncate mr-2">{{ $stat->qualification_name }}</span>
                                        <div class="flex gap-2">
                                            <span
                                                class="text-[10px] font-black text-brand">{{ $stat->y1_value + $stat->y2_value + $stat->y3_value + $stat->y4_value }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Awarded
                                Certificates</h4>
                            <div class="space-y-3">
                                @foreach($learnerStatsAwarded as $stat)
                                    <div class="p-4 bg-emerald-50/50 rounded-2xl flex items-center justify-between">
                                        <span
                                            class="text-xs font-bold text-slate-600 truncate mr-2">{{ $stat->qualification_name }}</span>
                                        <div class="flex gap-2">
                                            <span
                                                class="text-[10px] font-black text-emerald-600">{{ $stat->y1_value + $stat->y2_value + $stat->y3_value + $stat->y4_value }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Electronic Systems -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                            <i class="fa-solid fa-microchip"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">E-Systems</h2>
                    </div>
                    <div class="space-y-4">
                        @forelse($electronicSystems as $sys)
                            <div class="p-4 border border-slate-100 rounded-2xl group hover:border-brand/30 transition-all">
                                <div class="font-bold text-premium mb-1">{{ $sys->platform_name }}</div>
                                <div class="text-[10px] text-slate-400 font-medium leading-relaxed">{{ $sys->platform_purpose }}
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 text-xs italic">No systems listed</p>
                        @endforelse
                    </div>
                </div>

                <!-- Attachments -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                            <i class="fa-solid fa-paperclip"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">Attachments</h2>
                    </div>

                    <div class="space-y-3">
                        @php
                            $attachments = [
                                'delivery_plan' => 'Delivery Plan',
                                'org_chart' => 'Org Chart',
                                'site_plan' => 'Site Plan',
                                'sed_form' => 'SED Form',
                                'atp_logo' => 'Logo'
                            ];
                        @endphp

                        @foreach($attachments as $field => $label)
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 group">
                                <span class="text-xs font-bold text-slate-600">{{ $label }}</span>
                                @if($initForm->$field)
                                    <a href="{{ asset('uploads/' . $initForm->$field) }}" target="_blank"
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm hover:bg-blue-600 hover:text-white transition-all">
                                        <i class="fa-solid fa-download text-[10px]"></i>
                                    </a>
                                @else
                                    <span
                                        class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Missing</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Admin Actions -->
                @php 
                    $currStatus = $initForm->form_status ?? 'pending';
                    $isActionable = in_array($currStatus, ['pending', 'submitted', 'rejected']);
                @endphp
                
                @if($isActionable)
                    <div class="premium-card p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                                <i class="fa-solid fa-gavel"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Application Actions</h2>
                        </div>

                        <div class="space-y-6">
                            <textarea id="rc_comment" rows="4"
                                class="w-full bg-slate-50 border-none rounded-2xl p-6 text-sm font-medium focus:ring-2 focus:ring-brand/20 transition-all"
                                placeholder="Add comments here (required for Reject/Amend)...">{{ $initForm->rc_comment }}</textarea>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                            This application is currently in <strong>{{ strtoupper($initForm->form_status) }}</strong> status
                            and cannot be modified by R&C at this stage.
                        </p>
                        @if($initForm->rc_comment)
                            <div class="mt-6 p-6 bg-white rounded-2xl border border-slate-100">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Previous
                                    Comment</label>
                                <p class="text-sm text-slate-600 italic">"{{ $initForm->rc_comment }}"</p>
                            </div>
                        @endif
                    </div>
                @endif

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
                            form_type: 'initial'
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