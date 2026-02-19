@extends('layouts.app')
@section('title', 'Incident Reporting')
@section('subtitle', 'Log and track workplace incidents.')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-display font-bold text-slate-900">Incidents Log</h1>
                <p class="text-sm text-slate-500 mt-1">Total {{ $incidents->total() }} records found</p>
            </div>
            <div class="flex gap-4">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="searchInput" class="premium-input pl-11 pr-4 py-2.5 w-64"
                        placeholder="Search incidents...">
                </div>
                <button onclick="openModal('reportIncidentModal')" class="premium-button">
                    <i class="fa-solid fa-plus"></i> Report Incident
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left first:pl-6">Date & Time</th>
                            <th class="text-left">Type</th>
                            <th class="text-left">Description</th>
                            <th class="text-left">Attachment</th>
                            <th class="text-left last:pr-6">Reported By</th>
                        </tr>
                    </thead>
                    <tbody id="incidents-container">
                        @forelse($incidents as $incident)
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="first:pl-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-700 text-sm">{{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}</span>
                                        <span
                                            class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($incident->incident_date)->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit bg-indigo-50 text-indigo-700">
                                        {{ $incident->incident_type }}
                                    </span>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-600 truncate" title="{{ $incident->description }}">
                                        {{ $incident->description }}
                                    </p>
                                </td>
                                <td>
                                    @if($incident->attachment)
                                        <a href="{{ asset($incident->attachment) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                            <i class="fa-solid fa-paperclip"></i> View File
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">None</span>
                                    @endif
                                </td>
                                <td class="last:pr-6">
                                    <div class="flex items-center gap-2">
                                        @php
                                            $reporterName = optional(optional($incident->reporter)->employee)->first_name
                                                ?? optional($incident->reporter)->user_email
                                                ?? 'System';
                                        @endphp
                                        <div
                                            class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            {{ substr($reporterName, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-medium text-slate-600">{{ $reporterName }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-shield-virus text-3xl text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No incidents reported yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- AJAX Pagination Container -->
            <div id="incidents-pagination"></div>
        </div>
    </div>

    <!-- Create Incident Modal -->
    <div id="reportIncidentModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('reportIncidentModal')"></div>
        <div class="modal-content max-w-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Report New Incident</h2>
                    <p class="text-slate-500 text-sm">Log a new workplace incident or issue.</p>
                </div>
                <button onclick="closeModal('reportIncidentModal')"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.incidents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Date &
                            Time <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="incident_date" required class="premium-input w-full">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Type
                            <span class="text-rose-500">*</span></label>
                        <select name="incident_type" required class="premium-input w-full appearance-none">
                            <option value="" disabled selected>Select Type...</option>
                            <option value="Security Breach">Security Breach</option>
                            <option value="Workplace Accident">Workplace Accident</option>
                            <option value="System Failure">System Failure</option>
                            <option value="Data Loss">Data Loss</option>
                            <option value="Misconduct">Misconduct</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Description <span
                            class="text-rose-500">*</span></label>
                    <textarea name="description" required rows="4" class="premium-input w-full"
                        placeholder="Describe the incident in detail..."></textarea>
                </div>

                <!-- Attachment -->
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                        <i class="fa-solid fa-paperclip text-indigo-500 mr-1"></i> Attachment <span
                            class="text-slate-400 font-normal normal-case">(Optional)</span>
                    </label>
                    <input type="file" name="attachment" id="incident_attachment"
                        class="premium-input w-full px-4 py-3 text-sm">
                    <div id="incident-attachment-preview"></div>
                    <p class="text-[10px] text-slate-400 mt-1">Images or Documents (PDF, DOCX) up to 10MB.</p>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('reportIncidentModal')"
                        class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="premium-button">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Attachment Preview
            if (window.initAttachmentPreview) {
                window.initAttachmentPreview({
                    inputSelector: '#incident_attachment',
                    containerSelector: '#incident-attachment-preview'
                });
            }

            // AJAX Pagination
            window.ajaxPagination = new AjaxPagination({
                endpoint: '{{ route('admin.incidents.data') }}',
                containerSelector: '#incidents-container',
                paginationSelector: '#incidents-pagination',
                perPage: 10,
                renderCallback: function (incidents) {
                    const container = document.querySelector('#incidents-container');
                    if (incidents.length === 0) {
                        container.innerHTML = `
                                    <tr>
                                        <td colspan="5" class="text-center py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                                    <i class="fa-solid fa-shield-virus text-3xl text-slate-300"></i>
                                                </div>
                                                <p class="text-slate-500 font-medium">No incidents found</p>
                                            </div>
                                        </td>
                                    </tr>`;
                        return;
                    }

                    let html = '';
                    incidents.forEach(incident => {
                        html += `
                                    <tr class="group hover:bg-slate-50 transition-colors">
                                        <td class="first:pl-6">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-700 text-sm">${incident.formatted_date}</span>
                                                <span class="text-xs text-slate-400">${incident.formatted_time}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit bg-indigo-50 text-indigo-700">
                                                ${incident.incident_type}
                                            </span>
                                        </td>
                                        <td class="max-w-xs">
                                            <p class="text-sm text-slate-600 truncate" title="${incident.description.replace(/"/g, '&quot;')}">
                                                ${incident.description}
                                            </p>
                                        </td>
                                        <td>
                                            ${incident.attachment_url ? `
                                                <a href="${incident.attachment_url}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                                    <i class="fa-solid fa-paperclip"></i> View File
                                                </a>
                                            ` : '<span class="text-xs text-slate-400 italic">None</span>'}
                                        </td>
                                        <td class="last:pr-6">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                    ${incident.reporter_name.charAt(0)}
                                                </div>
                                                <span class="text-xs font-medium text-slate-600">${incident.reporter_name}</span>
                                            </div>
                                        </td>
                                    </tr>
                                 `;
                    });
                    container.innerHTML = html;
                }
            });

            // Initialize Pagination Render
            @if($incidents->hasPages())
                window.ajaxPagination.renderPagination({
                    current_page: {{ $incidents->currentPage() }},
                    last_page: {{ $incidents->lastPage() }},
                    from: {{ $incidents->firstItem() ?? 0 }},
                    to: {{ $incidents->lastItem() ?? 0 }},
                    total: {{ $incidents->total() }}
                                    });
            @endif

                    // Search Filter
                    const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function (e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const query = e.target.value;
                        const endpoint = '{{ route('admin.incidents.data') }}' + (query ? '?search=' + encodeURIComponent(query) : '');

                        // Manually update endpoint and reload first page
                        if (window.ajaxPagination) {
                            window.ajaxPagination.endpoint = endpoint;
                            // Hack: if the library has a method to load page 1, use it. 
                            // Assuming loadPage(1) exists or internal fetch method. 
                            // Based on common patterns, trigger a refresh.
                            // If we can't call loadPage, we might need to rely on the library exposing it.
                            // If simple implementation, maybe:
                            if (typeof window.ajaxPagination.fetchData === 'function') {
                                window.ajaxPagination.fetchData(1);
                            } else if (typeof window.ajaxPagination.loadPage === 'function') {
                                window.ajaxPagination.loadPage(1);
                            } else {
                                console.warn('AjaxPagination load method not found');
                            }
                        }
                    }, 500);
                });
            }
        });
    </script>
@endpush