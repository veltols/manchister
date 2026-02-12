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
        <div x-data="{ 
            ...areasHandler({{ json_encode($areas) }}), 
            collapsed: false, 
            search: '',
            limit: 10,
            showAll: false,
            get filteredRows() {
                let filtered = this.rows;
                if (this.search) {
                    filtered = filtered.filter(row => 
                        (row.a1 || '').toLowerCase().includes(this.search.toLowerCase()) || 
                        (row.a3 || '').toLowerCase().includes(this.search.toLowerCase())
                    );
                }
                return this.showAll ? filtered : filtered.slice(0, this.limit);
            }
        }" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden group/card">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-gradient-to-r from-slate-50/50 to-white">
                <div class="flex items-center gap-4 cursor-pointer" @click="collapsed = !collapsed">
                    <div class="w-12 h-12 rounded-2xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20 transition-transform duration-500 group-hover/card:scale-110" :class="collapsed ? 'rotate-[-90deg]' : ''">
                        <i class="fa-solid fa-briefcase text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                            <span>Areas to Review</span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold" x-text="rows.length + ' Items'"></span>
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="collapsed ? 'Click to expand section' : 'Detailed evidence and criteria matching'"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    <!-- Section Search -->
                    <div class="relative w-full sm:w-64" x-show="!collapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                        <input type="text" x-model="search" placeholder="Search evidence..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 shadow-sm transition-all hover:border-brand/40">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button @click="saveData()" class="premium-button flex-1 sm:flex-none justify-center h-[42px]">
                            <i class="fa-solid fa-save"></i>
                            <span x-text="saving ? 'Saving...' : 'Save Section'"></span>
                        </button>
                        <button @click="collapsed = !collapsed" class="w-[42px] h-[42px] rounded-xl border border-slate-200 text-slate-400 hover:text-brand hover:border-brand hover:bg-brand/5 transition-all text-sm">
                            <i class="fa-solid" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div x-show="!collapsed" x-collapse>
                <div class="p-6 md:p-8">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr>
                                    <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[40%]">Evidence</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[15%] text-center">Met Criteria</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[40%]">Explanation</th>
                                    <th class="pb-6 w-[5%] text-right pr-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(row, index) in filteredRows" :key="index">
                                    <tr class="group hover:bg-slate-50/20 transition-colors">
                                        <td class="py-6 pr-6 align-top pl-2">
                                            <textarea x-model="row.a1" rows="3" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="Enter evidence details..."></textarea>
                                        </td>
                                        <td class="py-6 pr-6 align-top">
                                            <div class="flex flex-col gap-2">
                                                <button type="button" @click="row.a2 = '1'" 
                                                    class="w-full py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border"
                                                    :class="row.a2 == '1' ? 'bg-emerald-500 text-white border-emerald-500 shadow-emerald-200' : 'bg-white text-slate-400 border-brand/20 hover:border-brand/40 hover:text-slate-600'">
                                                    Yes
                                                </button>
                                                <button type="button" @click="row.a2 = '0'" 
                                                    class="w-full py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border"
                                                    :class="row.a2 == '0' ? 'bg-rose-500 text-white border-rose-500 shadow-rose-200' : 'bg-white text-slate-400 border-brand/20 hover:border-brand/40 hover:text-slate-600'">
                                                    No
                                                </button>
                                                <input type="hidden" x-model="row.a2">
                                            </div>
                                        </td>
                                        <td class="py-6 pr-6 align-top">
                                            <textarea x-model="row.a3" rows="3" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="Provide explanation..."></textarea>
                                        </td>
                                        <td class="py-6 align-top text-right pr-4">
                                            <button @click="removeRow(index)" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 hover:border-rose-100 border border-transparent transition-all mt-1">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-50 pt-8">
                        <button @click="addRow()" class="w-full md:w-auto px-8 py-3.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.98] group/add">
                            <i class="fa-solid fa-plus mr-2 group-hover/add:rotate-90 transition-transform"></i>
                            Add New Entry
                        </button>

                        <div x-show="rows.length > limit" class="flex items-center gap-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="showAll ? 'Showing all entries' : 'Showing first ' + limit + ' entries'"></p>
                            <button @click="showAll = !showAll" class="text-[10px] font-black uppercase tracking-widest text-brand hover:underline px-4 py-2 bg-brand/5 rounded-lg transition-colors">
                                <span x-text="showAll ? 'Show Condensed' : 'Show All (' + rows.length + ')'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Staff Interview -->
        <div x-data="{ 
            ...interviewHandler({{ json_encode($staffInterviews) }}, 'staff'), 
            collapsed: true, 
            search: '',
            limit: 5,
            showAll: false,
            get filteredRows() {
                let filtered = this.rows;
                if (this.search) {
                    filtered = filtered.filter(row => 
                        (row.staff_name || '').toLowerCase().includes(this.search.toLowerCase()) || 
                        (row.staff_role || '').toLowerCase().includes(this.search.toLowerCase()) ||
                        (row.eqa_comment || '').toLowerCase().includes(this.search.toLowerCase())
                    );
                }
                return this.showAll ? filtered : filtered.slice(0, this.limit);
            }
        }" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden group/card">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-gradient-to-r from-slate-50/50 to-white">
                <div class="flex items-center gap-4 cursor-pointer" @click="collapsed = !collapsed">
                    <div class="w-12 h-12 rounded-2xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20 transition-transform duration-500 group-hover/card:scale-110" :class="collapsed ? 'rotate-[-90deg]' : ''">
                        <i class="fa-solid fa-people-roof text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                            <span>Staff Interview</span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold" x-text="rows.length + ' Members'"></span>
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="collapsed ? 'Click to expand section' : 'Interview details and feedback'"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    <!-- Section Search -->
                    <div class="relative w-full sm:w-64" x-show="!collapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                        <input type="text" x-model="search" placeholder="Search staff..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 shadow-sm transition-all hover:border-brand/40">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button @click="saveData()" class="premium-button flex-1 sm:flex-none justify-center h-[42px]">
                            <i class="fa-solid fa-save"></i>
                            <span x-text="saving ? 'Saving...' : 'Save Section'"></span>
                        </button>
                        <button @click="collapsed = !collapsed" class="w-[42px] h-[42px] rounded-xl border border-slate-200 text-slate-400 hover:text-brand hover:border-brand hover:bg-brand/5 transition-all text-sm">
                            <i class="fa-solid" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="!collapsed" x-collapse>
                <div class="p-6 md:p-8">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr>
                                    <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[30%]">Name</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[25%]">Role</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[40%]">Comment</th>
                                    <th class="pb-6 w-[5%] text-right pr-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(row, index) in filteredRows" :key="index">
                                    <tr class="group hover:bg-slate-50/20 transition-colors">
                                        <td class="py-6 pr-6 align-top pl-2">
                                            <textarea x-model="row.staff_name" rows="2" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="Staff Name"></textarea>
                                        </td>
                                        <td class="py-6 pr-6 align-top">
                                            <input type="text" x-model="row.staff_role" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 hover:border-brand/40" placeholder="Role">
                                        </td>
                                        <td class="py-6 pr-6 align-top">
                                            <textarea x-model="row.eqa_comment" rows="2" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="Comments"></textarea>
                                        </td>
                                        <td class="py-6 align-top text-right pr-4">
                                            <button @click="removeRow(index)" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all mt-1">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-50 pt-8">
                        <button @click="addRow()" class="w-full md:w-auto px-8 py-3.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.98] group/add">
                            <i class="fa-solid fa-plus mr-2 group-hover/add:rotate-90 transition-transform"></i>
                            Add Staff Member
                        </button>

                        <div x-show="rows.length > limit" class="flex items-center gap-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="showAll ? 'Showing all members' : 'Showing first ' + limit + ' members'"></p>
                            <button @click="showAll = !showAll" class="text-[10px] font-black uppercase tracking-widest text-brand hover:underline px-4 py-2 bg-brand/5 rounded-lg transition-colors">
                                <span x-text="showAll ? 'Show Condensed' : 'Show All (' + rows.length + ')'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Lead IQA Interview -->
        <div x-data="{ 
            ...interviewHandler({{ json_encode($iqaInterviews) }}, 'iqa'), 
            collapsed: true, 
            search: '',
            limit: 5,
            showAll: false,
            get filteredRows() {
                let filtered = this.rows;
                if (this.search) {
                    filtered = filtered.filter(row => 
                        (row.question || '').toLowerCase().includes(this.search.toLowerCase()) || 
                        (row.answer || '').toLowerCase().includes(this.search.toLowerCase())
                    );
                }
                return this.showAll ? filtered : filtered.slice(0, this.limit);
            }
        }" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden group/card">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-gradient-to-r from-slate-50/50 to-white">
                <div class="flex items-center gap-4 cursor-pointer" @click="collapsed = !collapsed">
                    <div class="w-12 h-12 rounded-2xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20 transition-transform duration-500 group-hover/card:scale-110" :class="collapsed ? 'rotate-[-90deg]' : ''">
                        <i class="fa-solid fa-user-check text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                            <span>Lead IQA Interview</span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold" x-text="rows.length + ' Q&A'"></span>
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="collapsed ? 'Click to expand section' : 'IQA process and verification'"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    <!-- Section Search -->
                    <div class="relative w-full sm:w-64" x-show="!collapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                        <input type="text" x-model="search" placeholder="Search questions..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 shadow-sm transition-all hover:border-brand/40">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button @click="saveData()" class="premium-button flex-1 sm:flex-none justify-center h-[42px]">
                            <i class="fa-solid fa-save"></i>
                            <span x-text="saving ? 'Saving...' : 'Save Section'"></span>
                        </button>
                        <button @click="collapsed = !collapsed" class="w-[42px] h-[42px] rounded-xl border border-slate-200 text-slate-400 hover:text-brand hover:border-brand hover:bg-brand/5 transition-all text-sm">
                            <i class="fa-solid" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="!collapsed" x-collapse>
                <div class="p-6 md:p-8">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr>
                                    <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[45%]">Question</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[50%]">Answer</th>
                                    <th class="pb-6 w-[5%] text-right pr-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(row, index) in filteredRows" :key="index">
                                    <tr class="group hover:bg-slate-50/20 transition-colors">
                                        <td class="py-6 pr-6 align-top pl-2">
                                            <textarea x-model="row.question" rows="2" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="Question asked..."></textarea>
                                        </td>
                                        <td class="py-6 pr-6 align-top">
                                            <textarea x-model="row.answer" rows="2" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="IQA Answer/Verification..."></textarea>
                                        </td>
                                        <td class="py-6 align-top text-right pr-4">
                                            <button @click="removeRow(index)" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all mt-1">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-50 pt-8">
                        <button @click="addRow()" class="w-full md:w-auto px-8 py-3.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.98] group/add">
                            <i class="fa-solid fa-plus mr-2 group-hover/add:rotate-90 transition-transform"></i>
                            Add IQA Record
                        </button>

                        <div x-show="rows.length > limit" class="flex items-center gap-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="showAll ? 'Showing all records' : 'Showing first ' + limit + ' records'"></p>
                            <button @click="showAll = !showAll" class="text-[10px] font-black uppercase tracking-widest text-brand hover:underline px-4 py-2 bg-brand/5 rounded-lg transition-colors">
                                <span x-text="showAll ? 'Show Condensed' : 'Show All (' + rows.length + ')'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Trainers Interview -->
        <div x-data="{ 
            ...interviewHandler({{ json_encode($trainInterviews) }}, 'train'), 
            collapsed: true, 
            search: '',
            limit: 5,
            showAll: false,
            get filteredRows() {
                let filtered = this.rows;
                if (this.search) {
                    filtered = filtered.filter(row => 
                        (row.question || '').toLowerCase().includes(this.search.toLowerCase()) || 
                        (row.answer || '').toLowerCase().includes(this.search.toLowerCase())
                    );
                }
                return this.showAll ? filtered : filtered.slice(0, this.limit);
            }
        }" class="premium-card p-0 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50 overflow-hidden group/card">
            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-gradient-to-r from-slate-50/50 to-white">
                <div class="flex items-center gap-4 cursor-pointer" @click="collapsed = !collapsed">
                    <div class="w-12 h-12 rounded-2xl bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/20 transition-transform duration-500 group-hover/card:scale-110" :class="collapsed ? 'rotate-[-90deg]' : ''">
                        <i class="fa-solid fa-chalkboard-user text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                            <span>Trainers & Assessors Interview</span>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold" x-text="rows.length + ' Q&A'"></span>
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="collapsed ? 'Click to expand section' : 'Teaching and assessment feedback'"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    <!-- Section Search -->
                    <div class="relative w-full sm:w-64" x-show="!collapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                        <input type="text" x-model="search" placeholder="Search questions..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 shadow-sm transition-all hover:border-brand/40">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button @click="saveData()" class="premium-button flex-1 sm:flex-none justify-center h-[42px]">
                            <i class="fa-solid fa-save"></i>
                            <span x-text="saving ? 'Saving...' : 'Save Section'"></span>
                        </button>
                        <button @click="collapsed = !collapsed" class="w-[42px] h-[42px] rounded-xl border border-slate-200 text-slate-400 hover:text-brand hover:border-brand hover:bg-brand/5 transition-all text-sm">
                            <i class="fa-solid" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="!collapsed" x-collapse>
                <div class="p-6 md:p-8">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr>
                                    <th class="pb-6 pl-2 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[45%]">Question</th>
                                    <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest w-[50%]">Answer</th>
                                    <th class="pb-6 w-[5%] text-right pr-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(row, index) in filteredRows" :key="index">
                                    <tr class="group hover:bg-slate-50/20 transition-colors">
                                        <td class="py-6 pr-6 align-top pl-2">
                                            <textarea x-model="row.question" rows="2" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="Question asked..."></textarea>
                                        </td>
                                        <td class="py-6 pr-6 align-top">
                                            <textarea x-model="row.answer" rows="2" class="w-full p-4 rounded-xl border border-brand/20 text-xs font-medium focus:border-brand focus:ring-4 focus:ring-brand/5 transition-all bg-white shadow-sm placeholder:text-slate-300 resize-none hover:border-brand/40" placeholder="Trainer Answer..."></textarea>
                                        </td>
                                        <td class="py-6 align-top text-right pr-4">
                                            <button @click="removeRow(index)" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all mt-1">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-50 pt-8">
                        <button @click="addRow()" class="w-full md:w-auto px-8 py-3.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:border-brand hover:text-brand hover:bg-brand/5 transition-all active:scale-[0.98] group/add">
                            <i class="fa-solid fa-plus mr-2 group-hover/add:rotate-90 transition-transform"></i>
                            Add Trainer Record
                        </button>

                        <div x-show="rows.length > limit" class="flex items-center gap-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="showAll ? 'Showing all records' : 'Showing first ' + limit + ' records'"></p>
                            <button @click="showAll = !showAll" class="text-[10px] font-black uppercase tracking-widest text-brand hover:underline px-4 py-2 bg-brand/5 rounded-lg transition-colors">
                                <span x-text="showAll ? 'Show Condensed' : 'Show All (' + rows.length + ')'"></span>
                            </button>
                        </div>
                    </div>
                </div>
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
