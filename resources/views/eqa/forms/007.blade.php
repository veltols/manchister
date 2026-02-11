@extends('layouts.app')

@section('title', 'Module 007')
@section('subtitle', 'Evidence Collection Log')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">
        
        <!-- Header & Instructions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-widest">Evidence Collection Log</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Module 007 - Visit Evidence & Interviews</p>
            </div>
            
            <!-- Back Button as Premium Button -->
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'visit']) }}" 
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Visit</span>
            </a>
        </div>

        <!-- SED Info (Read Only) Premium Card -->
        <div class="premium-card p-8 bg-brand shadow-lg shadow-brand/30 border-brand relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-bl-[100px] transition-all group-hover:bg-white/10"></div>
            
            <h3 class="text-sm font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2 relative z-10">
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <span>SED Information</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-white uppercase tracking-widest">Author of SED</label>
                    <div class="p-4 bg-white/10 rounded-xl border border-white/10 text-sm font-bold text-white shadow-sm group-hover:bg-white/20 group-hover:border-white/20 transition-colors">
                        {{ $sed_data->sed_1 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-white uppercase tracking-widest">Role</label>
                    <div class="p-4 bg-white/10 rounded-xl border border-white/10 text-sm font-bold text-white shadow-sm group-hover:bg-white/20 group-hover:border-white/20 transition-colors">
                        {{ $sed_data->sed_2 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-white uppercase tracking-widest">Last Internal Audit</label>
                    <div class="p-4 bg-white/10 rounded-xl border border-white/10 text-sm font-bold text-white shadow-sm group-hover:bg-white/20 group-hover:border-white/20 transition-colors">
                        {{ $sed_data->sed_3 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-white uppercase tracking-widest">Submitted Date</label>
                    <div class="p-4 bg-white/10 rounded-xl border border-white/10 text-sm font-bold text-white shadow-sm group-hover:bg-white/20 group-hover:border-white/20 transition-colors">
                        {{ $sed_data->submitted_date ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Areas to Review -->
        <div x-data="areasHandler({{ json_encode($areas) }})" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-slate-50/50 to-white">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <span>Areas to Review</span>
                </h3>
                <button @click="saveData()" class="premium-button w-full md:w-auto justify-center">
                    <i class="fa-solid fa-save"></i>
                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                </button>
            </div>
            
            <div class="p-6 md:p-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[40%]">Evidence</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[15%] text-center">Met Criteria</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[40%]">Explanation</th>
                            <th class="pb-6 w-[5%]"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-6 pr-6 align-top pl-2">
                                    <textarea x-model="row.a1" rows="3" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Enter evidence details..."></textarea>
                                </td>
                                <td class="py-6 pr-6 align-top">
                                    <div class="flex flex-col gap-2">
                                        <!-- Yes Button -->
                                        <button type="button" 
                                            @click="row.a2 = '1'" 
                                            class="w-full py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border"
                                            :class="row.a2 == '1' ? 'bg-emerald-500 text-white border-emerald-500 shadow-emerald-200' : 'bg-white text-slate-400 border-slate-200 hover:border-slate-300 hover:text-slate-600'">
                                            Yes
                                        </button>
                                        <!-- No Button -->
                                        <button type="button" 
                                            @click="row.a2 = '0'" 
                                            class="w-full py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border"
                                            :class="row.a2 == '0' ? 'bg-rose-500 text-white border-rose-500 shadow-rose-200' : 'bg-white text-slate-400 border-slate-200 hover:border-slate-300 hover:text-slate-600'">
                                            No
                                        </button>
                                        <!-- Keep hidden input for form value if needed, or rely on Alpine model binding -->
                                        <input type="hidden" x-model="row.a2">
                                    </div>
                                </td>
                                <td class="py-6 pr-6 align-top">
                                    <textarea x-model="row.a3" rows="3" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Provide explanation..."></textarea>
                                </td>
                                <td class="py-6 align-top text-right">
                                    <button @click="removeRow(index)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all mt-2">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <button @click="addRow()" class="mt-6 w-full py-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.99] group">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-400 group-hover:bg-brand group-hover:text-white mr-2 transition-all">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                    </span>
                    Add New Row
                </button>
            </div>
        </div>

        <!-- Section 2: Staff Interview -->
        <div x-data="interviewHandler({{ json_encode($staffInterviews) }}, 'staff')" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-slate-50/50 to-white">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <span>Staff Interview</span>
                </h3>
                <button @click="saveData()" class="premium-button w-full md:w-auto justify-center">
                    <i class="fa-solid fa-save"></i>
                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                </button>
            </div>
            
            <div class="p-6 md:p-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[30%]">Name</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[25%]">Role</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[40%]">Comment</th>
                            <th class="pb-6 w-[5%]"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-6 pr-6 align-top pl-2">
                                    <textarea x-model="row.staff_name" rows="2" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Staff Name"></textarea>
                                </td>
                                <td class="py-6 pr-6 align-top">
                                    <input type="text" x-model="row.staff_role" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 hover:border-slate-300" placeholder="Role">
                                </td>
                                <td class="py-6 pr-6 align-top">
                                    <textarea x-model="row.eqa_comment" rows="2" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Comments"></textarea>
                                </td>
                                <td class="py-6 align-top text-right">
                                    <button @click="removeRow(index)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all mt-2">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <button @click="addRow()" class="mt-6 w-full py-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.99] group">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-400 group-hover:bg-brand group-hover:text-white mr-2 transition-all">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                    </span>
                    Add New Row
                </button>
            </div>
        </div>

        <!-- Section 3: Lead IQA Interview -->
        <div x-data="interviewHandler({{ json_encode($iqaInterviews) }}, 'iqa')" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-slate-50/50 to-white">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <span>Lead IQA Interview</span>
                </h3>
                <button @click="saveData()" class="premium-button w-full md:w-auto justify-center">
                    <i class="fa-solid fa-save"></i>
                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                </button>
            </div>
            
            <div class="p-6 md:p-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[45%]">Question</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[50%]">Answer</th>
                            <th class="pb-6 w-[5%]"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-6 pr-6 align-top pl-2">
                                    <textarea x-model="row.question" rows="3" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Enter question..."></textarea>
                                </td>
                                <td class="py-6 pr-6 align-top">
                                    <textarea x-model="row.answer" rows="3" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Enter answer..."></textarea>
                                </td>
                                <td class="py-6 align-top text-right">
                                    <button @click="removeRow(index)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all mt-2">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <button @click="addRow()" class="mt-6 w-full py-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.99] group">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-400 group-hover:bg-brand group-hover:text-white mr-2 transition-all">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                    </span>
                    Add New Row
                </button>
            </div>
        </div>

        <!-- Section 4: Trainers Interview -->
        <div x-data="interviewHandler({{ json_encode($trainInterviews) }}, 'train')" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-slate-50/50 to-white">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <span>Trainers & Assessors Interview</span>
                </h3>
                <button @click="saveData()" class="premium-button w-full md:w-auto justify-center">
                    <i class="fa-solid fa-save"></i>
                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                </button>
            </div>
            
            <div class="p-6 md:p-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[45%]">Question</th>
                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[50%]">Answer</th>
                            <th class="pb-6 w-[5%]"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-6 pr-6 align-top pl-2">
                                    <textarea x-model="row.question" rows="3" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Enter question..."></textarea>
                                </td>
                                <td class="py-6 pr-6 align-top">
                                    <textarea x-model="row.answer" rows="3" class="w-full p-4 rounded-xl border border-slate-200 text-xs font-medium focus:border-brand focus:ring-0 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-slate-300" placeholder="Enter answer..."></textarea>
                                </td>
                                <td class="py-6 align-top text-right">
                                    <button @click="removeRow(index)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all mt-2">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <button @click="addRow()" class="mt-6 w-full py-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.99] group">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-400 group-hover:bg-brand group-hover:text-white mr-2 transition-all">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                    </span>
                    Add New Row
                </button>
            </div>
        </div>

    </div>

    <!-- Alpine.js Logic -->
    <script>
        function areasHandler(initialData) {
            return {
                rows: initialData.length ? initialData : [],
                saving: false,
                
                addRow() {
                    this.rows.push({
                        record_id: 0,
                        a1: '',
                        a2: '',
                        a3: ''
                    });
                },
                removeRow(index) {
                    this.rows.splice(index, 1);
                },
                saveData() {
                    this.saving = true;
                    
                    // Prepare data
                    let areas = this.rows.map(r => r.record_id);
                    let a1s = this.rows.map(r => r.a1);
                    let a2s = this.rows.map(r => r.a2);
                    let a3s = this.rows.map(r => r.a3);

                    axios.post('{{ route("eqa.forms.save_007") }}', {
                        atp_id: '{{ $atp->atp_id }}',
                        areas: areas,
                        a1s: a1s,
                        a2s: a2s,
                        a3s: a3s
                    })
                    .then(response => {
                        this.saving = false;
                        if(response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: 'Areas to review have been saved successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Optional: reload if needed, or just let them stay on page
                            // window.location.reload(); 
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong while saving.',
                            });
                        }
                    })
                    .catch(error => {
                        this.saving = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing your request.',
                        });
                    });
                }
            }
        }

        function interviewHandler(initialData, type) {
            return {
                rows: initialData.length ? initialData : [],
                saving: false,
                type: type, // 'staff', 'iqa', 'train'

                addRow() {
                    if(this.type === 'staff') {
                        this.rows.push({ interview_id: 0, staff_name: '', staff_role: '', eqa_comment: '' });
                    } else {
                        this.rows.push({ interview_id: 0, question: '', answer: '' });
                    }
                },
                removeRow(index) {
                    this.rows.splice(index, 1);
                },
                saveData() {
                    this.saving = true;
                    
                    let data = {
                        atp_id: '{{ $atp->atp_id }}',
                    };

                    // Map IDs
                    data.interview_ids = this.rows.map(r => r.interview_id);

                    if(this.type === 'staff') {
                        data.staff_names = this.rows.map(r => r.staff_name);
                        data.staff_roles = this.rows.map(r => r.staff_role);
                        data.eqa_comments = this.rows.map(r => r.eqa_comment);
                    } else if(this.type === 'iqa') {
                        data.iqa_questions = this.rows.map(r => r.question);
                        data.iqa_answers = this.rows.map(r => r.answer);
                        data.iqa_ids = this.rows.map(r => r.interview_id); // Redundant mapping for controller expectation
                    } else if(this.type === 'train') {
                        data.train_questions = this.rows.map(r => r.question);
                        data.train_answers = this.rows.map(r => r.answer);
                        data.train_ids = this.rows.map(r => r.interview_id); // Redundant mapping for controller expectation
                    }

                    axios.post('{{ route("eqa.forms.save_007") }}', data)
                    .then(response => {
                        this.saving = false;
                        if(response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: 'Interview data has been saved successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // window.location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong while saving.',
                            });
                        }
                    })
                    .catch(error => {
                        this.saving = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing your request.',
                        });
                    });
                }
            }
        }
    </script>
@endsection
