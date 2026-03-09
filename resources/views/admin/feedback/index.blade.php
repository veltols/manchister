@extends('layouts.app')

@section('title', 'User Feedback')
@section('subtitle', 'Comprehensive portal user feedback reports')

@section('content')
    <div class="space-y-6 animate-fade-in-up" x-data="feedbackModal()">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Feedback Reports</h2>
                <p class="text-sm text-slate-500 mt-1">Showing {{ $feedbacks->firstItem() ?? 0 }} - {{ $feedbacks->lastItem() ?? 0 }} of {{ $feedbacks->total() }} records</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.feedback.export') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl shadow-sm hover:shadow-md hover:bg-slate-50 transition-all duration-200">
                    <i class="fa-solid fa-file-csv text-emerald-600"></i>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        <!-- Feedback Results Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="w-16">ID</th>
                            <th class="min-w-[200px]">Employee Details</th>
                            <th class="min-w-[150px]">Date Submitted</th>
                            <th class="text-center min-w-[150px]">UI Rating (Avg)</th>
                            <th class="text-center min-w-[150px]">Technical Issues</th>
                            <th class="text-right min-w-[120px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="feedback-container">
                        @forelse($feedbacks as $fb)
                            @php
                                // Calculate a quick average of the first 3 UI questions if they are numeric
                                $uiSum = 0; $uiCount = 0;
                                foreach(['a1', 'a2', 'a3'] as $q) {
                                    $val = (int)$fb->$q;
                                    if($val > 0) { $uiSum += $val; $uiCount++; }
                                }
                                $uiAvg = $uiCount > 0 ? round($uiSum / $uiCount, 1) : null;
                            @endphp
                            <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                                <td>
                                    <span class="font-mono text-xs font-bold text-slate-400">#{{ $fb->record_id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center text-indigo-500 font-bold text-xs border border-indigo-100/50">
                                            {{ substr($fb->first_name, 0, 1) }}{{ substr($fb->last_name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $fb->first_name }} {{ $fb->last_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm text-slate-600 font-medium">{{ \Carbon\Carbon::parse($fb->added_date)->format('M d, Y') }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold">{{ \Carbon\Carbon::parse($fb->added_date)->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($uiAvg)
                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-bold 
                                            {{ $uiAvg >= 4 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100/50' : 
                                               ($uiAvg >= 2.5 ? 'bg-amber-50 text-amber-600 border border-amber-100/50' : 'bg-rose-50 text-rose-600 border border-rose-100/50') }}">
                                            <i class="fa-solid fa-star text-[10px] mr-1.5"></i> {{ $uiAvg }} / 5
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtolower($fb->a4) === 'yes')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-rose-50 text-rose-600 text-xs font-semibold border border-rose-100/50">
                                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 text-slate-500 text-xs font-semibold border border-slate-200">
                                            <i class="fa-solid fa-check text-[10px]"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openModal({{ json_encode($fb) }})" class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md" title="View Details">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i class="fa-solid fa-comment-slash text-2xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-medium italic">No feedback records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Container -->
            <div id="feedback-pagination">
                @if($feedbacks->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                        {{ $feedbacks->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Feedback Detail Modal (Alpine.js) -->
        <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-0" style="display: none;">
            <!-- Backdrop -->
            <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal"></div>
            
            <!-- Modal Content -->
            <div x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col z-10">
                
                <!-- Modal Header -->
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-white relative z-20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-brand flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-file-lines text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-display font-bold text-premium">Feedback Report #<span x-text="currentData?.record_id"></span></h3>
                            <p class="text-sm text-slate-500" x-text="'Submitted by ' + (currentData?.first_name || '') + ' ' + (currentData?.last_name || '') + ' on ' + formatDate(currentData?.added_date).full"></p>
                        </div>
                    </div>
                    <button @click="closeModal" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                
                <!-- Modal Body (Scrollable) -->
                <div class="p-8 overflow-y-auto bg-slate-50/30 flex-1 space-y-8">
                    
                    <!-- Section 1: User Interface -->
                    <div class="premium-card p-0 overflow-hidden shadow-sm border border-slate-200/60">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center gap-3">
                            <span class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">01</span>
                            <h4 class="font-bold text-slate-700">User Interface & Accessibility</h4>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 bg-white">
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">User-friendliness</p>
                                <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-100 font-semibold text-slate-700" x-text="currentData?.a1 || 'N/A'"></div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Visual Appeal</p>
                                <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-100 font-semibold text-slate-700" x-text="currentData?.a2 || 'N/A'"></div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Login/Logout Experience</p>
                                <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-100 font-semibold text-slate-700" x-text="currentData?.a3 || 'N/A'"></div>
                            </div>
                            
                            <div class="md:col-span-3 pt-4 border-t border-slate-100">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Encountered Technical Issues?</p>
                                <div class="flex items-start gap-4">
                                    <div class="px-4 py-3 rounded-xl border font-semibold inline-flex items-center gap-2" 
                                         :class="isYes(currentData?.a4) ? 'bg-rose-50 border-rose-100 text-rose-700' : 'bg-slate-50 border-slate-100 text-slate-700'">
                                        <i class="fa-solid" :class="isYes(currentData?.a4) ? 'fa-triangle-exclamation' : 'fa-check'"></i>
                                        <span x-text="currentData?.a4 || 'N/A'"></span>
                                    </div>
                                    <template x-if="isYes(currentData?.a4)">
                                        <div class="flex-1 px-4 py-3 bg-rose-50/50 rounded-xl border border-rose-100 text-sm text-slate-700" x-text="currentData?.a5 || 'No details provided.'"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section 2: Feature Experience -->
                    <div class="premium-card p-0 overflow-hidden shadow-sm border border-slate-200/60">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center gap-3">
                            <span class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">02</span>
                            <h4 class="font-bold text-slate-700">Feature-Specific Feedback</h4>
                        </div>
                        <div class="bg-white overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100">
                                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-1/3">Portal Feature</th>
                                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-1/4">Rating</th>
                                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Comments</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <!-- Interactive Calendar -->
                                    <tr x-show="currentData?.a6 || currentData?.a7">
                                        <td class="px-6 py-4"><div class="flex items-center gap-3"><i class="fa-solid fa-calendar-days text-indigo-400"></i> <span class="font-bold text-slate-700 text-sm">Interactive Calendar</span></div></td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-sm font-semibold text-slate-700" x-text="currentData?.a6 || 'N/A'"></span></td>
                                        <td class="px-6 py-4"><p class="text-sm text-slate-600" x-text="currentData?.a7 || '-'"></p></td>
                                    </tr>
                                    <!-- Real-time Messaging -->
                                    <tr x-show="currentData?.a8 || currentData?.a9">
                                        <td class="px-6 py-4"><div class="flex items-center gap-3"><i class="fa-solid fa-comment-dots text-indigo-400"></i> <span class="font-bold text-slate-700 text-sm">Real-time Messaging</span></div></td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-sm font-semibold text-slate-700" x-text="currentData?.a8 || 'N/A'"></span></td>
                                        <td class="px-6 py-4"><p class="text-sm text-slate-600" x-text="currentData?.a9 || '-'"></p></td>
                                    </tr>
                                    <!-- Task Management -->
                                    <tr x-show="currentData?.a10 || currentData?.a11">
                                        <td class="px-6 py-4"><div class="flex items-center gap-3"><i class="fa-solid fa-list-check text-indigo-400"></i> <span class="font-bold text-slate-700 text-sm">Task Management</span></div></td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-sm font-semibold text-slate-700" x-text="currentData?.a10 || 'N/A'"></span></td>
                                        <td class="px-6 py-4"><p class="text-sm text-slate-600" x-text="currentData?.a11 || '-'"></p></td>
                                    </tr>
                                    <!-- IT Ticket System -->
                                    <tr x-show="currentData?.a12 || currentData?.a13">
                                        <td class="px-6 py-4"><div class="flex items-center gap-3"><i class="fa-solid fa-headset text-indigo-400"></i> <span class="font-bold text-slate-700 text-sm">IT Ticket System</span></div></td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-sm font-semibold text-slate-700" x-text="currentData?.a12 || 'N/A'"></span></td>
                                        <td class="px-6 py-4"><p class="text-sm text-slate-600" x-text="currentData?.a13 || '-'"></p></td>
                                    </tr>
                                    <!-- HR Requests Hub -->
                                    <tr x-show="currentData?.a14 || currentData?.a15">
                                        <td class="px-6 py-4"><div class="flex items-center gap-3"><i class="fa-solid fa-users text-indigo-400"></i> <span class="font-bold text-slate-700 text-sm">HR Requests Hub</span></div></td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-sm font-semibold text-slate-700" x-text="currentData?.a14 || 'N/A'"></span></td>
                                        <td class="px-6 py-4"><p class="text-sm text-slate-600" x-text="currentData?.a15 || '-'"></p></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Section 3: Future Enhancements -->
                    <div class="premium-card p-0 overflow-hidden shadow-sm border border-slate-200/60">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center gap-3">
                            <span class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">03</span>
                            <h4 class="font-bold text-slate-700">Future Enhancements & Notes</h4>
                        </div>
                        <div class="p-6 space-y-6 bg-white">
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Requested Features</p>
                                <div class="px-5 py-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 min-h-[80px]" x-text="currentData?.a16 || 'None specified.'"></div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Additional Remarks</p>
                                <div class="px-5 py-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 min-h-[80px]" x-text="currentData?.a17 || 'None specified.'"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        function formatDate(dateString) {
            if (!dateString) return { full: '', time: '' };
            const date = new Date(dateString);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const d = date.getDate();
            const m = months[date.getMonth()];
            const y = date.getFullYear();
            
            let hours = date.getHours();
            let minutes = date.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0'+minutes : minutes;
            
            return {
                full: `${m} ${d < 10 ? '0'+d : d}, ${y}`,
                time: `${hours}:${minutes} ${ampm}`
            };
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('feedbackModal', () => ({
                isOpen: false,
                currentData: null,
                
                openModal(data) {
                    this.currentData = data;
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                
                closeModal() {
                    this.isOpen = false;
                    setTimeout(() => {
                        this.currentData = null;
                        document.body.style.overflow = '';
                    }, 300);
                },
                
                isYes(val) {
                    if (!val) return false;
                    return String(val).toLowerCase() === 'yes';
                },
                
                formatDate(dateString) {
                    return window.formatDate(dateString);
                }
            }));
        });

        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('admin.feedback.data') }}",
            containerSelector: '#feedback-container',
            paginationSelector: '#feedback-pagination',
            perPage: 15,
            renderCallback: function(feedbacks) {
                const container = document.querySelector('#feedback-container');
                
                if (feedbacks.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                        <i class="fa-solid fa-comment-slash text-2xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-medium italic">No feedback records found.</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                let html = '';
                feedbacks.forEach(fb => {
                    const initials = (fb.first_name?.[0] || '') + (fb.last_name?.[0] || '');
                    const dateInfo = formatDate(fb.added_date);
                    
                    // Setup UI Avg
                    let uiSum = 0, uiCount = 0;
                    ['a1', 'a2', 'a3'].forEach(q => {
                        const val = parseInt(fb[q]);
                        if (val > 0) { uiSum += val; uiCount++; }
                    });
                    const uiAvg = uiCount > 0 ? (uiSum / uiCount).toFixed(1) : null;
                    
                    let uiHtml = `<span class="text-slate-400 text-xs italic">N/A</span>`;
                    if (uiAvg !== null) {
                        const colorClass = uiAvg >= 4 ? 'bg-emerald-50 text-emerald-600 border-emerald-100/50' : 
                                          (uiAvg >= 2.5 ? 'bg-amber-50 text-amber-600 border-amber-100/50' : 'bg-rose-50 text-rose-600 border-rose-100/50');
                        uiHtml = `<span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-bold border ${colorClass}"><i class="fa-solid fa-star text-[10px] mr-1.5"></i> ${uiAvg} / 5</span>`;
                    }
                    
                    // Technical issues
                    const hasIssues = String(fb.a4 || '').toLowerCase() === 'yes';
                    const issuesHtml = hasIssues ? 
                        `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-rose-50 text-rose-600 text-xs font-semibold border border-rose-100/50"><i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Yes</span>` :
                        `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 text-slate-500 text-xs font-semibold border border-slate-200"><i class="fa-solid fa-check text-[10px]"></i> No</span>`;
                    
                    const fbJson = JSON.stringify(fb).replace(/"/g, '&quot;');
                    
                    html += `
                        <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                            <td>
                                <span class="font-mono text-xs font-bold text-slate-400">#${fb.record_id}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center text-indigo-500 font-bold text-xs border border-indigo-100/50">
                                        ${initials}
                                    </div>
                                    <span class="font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">${fb.first_name} ${fb.last_name}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-sm text-slate-600 font-medium">${dateInfo.full}</span>
                                    <span class="text-[10px] text-slate-400 font-bold">${dateInfo.time}</span>
                                </div>
                            </td>
                            <td class="text-center">${uiHtml}</td>
                            <td class="text-center">${issuesHtml}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openModal(${fbJson})" class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md" title="View Details">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                container.innerHTML = html;
            }
        });

        // Initialize pagination helper with server-side data for first load
        @if($feedbacks->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $feedbacks->currentPage() }},
                last_page: {{ $feedbacks->lastPage() }},
                from: {{ $feedbacks->firstItem() ?? 0 }},
                to: {{ $feedbacks->lastItem() ?? 0 }},
                total: {{ $feedbacks->total() }}
            });
        @endif
    </script>
    @endpush
@endsection
