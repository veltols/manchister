@extends('layouts.app')

@section('title', 'User Feedback')
@section('subtitle', 'Comprehensive portal user feedback reports')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Feedback Reports</h2>
                <p class="text-sm text-slate-500 mt-1">Showing {{ $feedbacks->firstItem() }} - {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }} records</p>
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
                            <!-- Dynamic Columns for Answers with specialized styling -->
                            @for($i = 1; $i <= 17; $i++)
                                <th class="text-center min-w-[80px]">
                                    <span class="px-2 py-1 rounded bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider">Q{{ $i }}</span>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody id="feedback-container">
                        @forelse($feedbacks as $fb)
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
                                @for($i = 1; $i <= 17; $i++)
                                    @php $ans = "a$i"; @endphp
                                    <td class="text-center">
                                        @if(is_numeric($fb->$ans) && (int)$fb->$ans >= 1 && (int)$fb->$ans <= 5)
                                            <!-- Rating specialized display -->
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-bold 
                                                {{ (int)$fb->$ans >= 4 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100/50' : 
                                                   ((int)$fb->$ans >= 2 ? 'bg-amber-50 text-amber-600 border border-amber-100/50' : 'bg-rose-50 text-rose-600 border border-rose-100/50') }}">
                                                {{ $fb->$ans }}
                                            </span>
                                        @else
                                            <!-- Text answers -->
                                            <div class="max-w-[150px] mx-auto">
                                                <p class="text-xs text-slate-500 truncate italic cursor-help" title="{{ $fb->$ans }}">
                                                    {{ Str::limit($fb->$ans, 25) }}
                                                </p>
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="20" class="py-20 text-center">
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

    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        function formatDate(dateString) {
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
                            <td colspan="20" class="py-20 text-center">
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
                    `;
                    
                    for (let i = 1; i <= 17; i++) {
                        const ans = fb['a' + i];
                        const isNumeric = !isNaN(parseFloat(ans)) && isFinite(ans);
                        const val = parseInt(ans);
                        
                        if (isNumeric && val >= 1 && val <= 5) {
                            const colorClass = val >= 4 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100/50' : 
                                              (val >= 2 ? 'bg-amber-50 text-amber-600 border border-amber-100/50' : 'bg-rose-50 text-rose-600 border border-rose-100/50');
                            
                            html += `
                                <td class="text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-bold ${colorClass}">
                                        ${val}
                                    </span>
                                </td>
                            `;
                        } else {
                            const truncatedAns = ans && ans.length > 25 ? ans.substring(0, 25) + '...' : (ans || '');
                            html += `
                                <td class="text-center">
                                    <div class="max-w-[150px] mx-auto">
                                        <p class="text-xs text-slate-500 truncate italic cursor-help" title="${ans || ''}">
                                            ${truncatedAns}
                                        </p>
                                    </div>
                                </td>
                            `;
                        }
                    }
                    
                    html += `</tr>`;
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
