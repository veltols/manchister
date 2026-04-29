@extends('layouts.app')

@section('title', 'Admin Services')
@section('subtitle', 'General service requests and collaboration')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Action Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">My Service Requests</h2>
                <p class="text-sm text-slate-500 mt-1">Track and manage your requests to other departments</p>
            </div>
            <button onclick="openModal('newSSModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>New Request</span>
            </button>
        </div>

        <!-- SS List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">REF</th>
                            <th class="text-left font-bold text-slate-400">Type</th>
                            <th class="text-left font-bold text-slate-400">Description</th>
                            <th class="text-left font-bold text-slate-400">Sent To</th>
                            <th class="text-center font-bold text-slate-400">Status</th>
                            <th class="text-center font-bold text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50" id="ss-container">
                        @forelse($services as $sv)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <span
                                        class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $sv->ss_ref }}</span>
                                </td>
                                <td>
                                    <span
                                        class="font-bold text-slate-700 text-sm">{{ $sv->category->category_name ?? '-' }}</span>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-500 truncate" title="{{ $sv->ss_description }}">
                                        {{ $sv->ss_description }}
                                    </p>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">
                                            {{ substr($sv->receiver->first_name ?? '?', 0, 1) }}{{ substr($sv->receiver->last_name ?? '?', 0, 1) }}
                                        </div>
                                        <span
                                            class="text-sm text-slate-600 font-medium">{{ $sv->receiver->first_name ?? 'Unknown' }}
                                            {{ $sv->receiver->last_name ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                        style="background: #{{ $sv->status->status_color ?? '64748b' }};">
                                        {{ $sv->status->status_name ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('emp.ss.show', $sv->ss_id) }}"
                                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20">
                                    <i class="fa-solid fa-handshake-angle text-5xl text-slate-100 mb-4"></i>
                                    <p class="text-slate-400 font-medium">No service requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                    <!-- AJAX Pagination -->
                    <div id="ss-pagination"></div>

                    @if (false && $services->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $services->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- New SS Modal -->
    <div class="modal" id="newSSModal">
        <div class="modal-backdrop" onclick="closeModal('newSSModal')"></div>
        <div class="modal-content max-w-lg p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-display font-bold text-premium">New Service Request</h2>
                <button onclick="closeModal('newSSModal')"
                    class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form id="newSSForm" action="{{ route('emp.ss.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateSSForm(event)">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Request Type</label>
                    <select name="category_id" id="ss_category_id" class="premium-input w-full" required onchange="handleCategoryChange(this)">
                        <option value="">Select Type</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}" 
                                    data-receiver-id="{{ $cat->destination_id }}"
                                    data-receiver-name="{{ $cat->receiver ? ($cat->receiver->first_name . ' ' . $cat->receiver->last_name) : '' }}">
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="assigned_receiver_display" class="hidden p-4 rounded-xl border transition-all">
                    <div id="receiver_found_state" class="hidden">
                        <label class="block text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Assigned Receiver</label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-200 flex items-center justify-center text-amber-700 font-bold">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <p class="font-bold text-amber-900" id="assigned_receiver_name">-</p>
                                <p class="text-[10px] text-amber-700 italic">This request will be automatically routed to the pre-assigned service manager.</p>
                            </div>
                        </div>
                    </div>
                    <div id="receiver_missing_state" class="hidden">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600 font-bold">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <p class="font-bold text-rose-900 uppercase tracking-tight text-xs">Receiver not assigned for this request</p>
                                <p class="text-[10px] text-rose-700 italic">Please contact your administrator to configure a receiver for this service type.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label
                        class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Description</label>
                    <textarea name="ss_description" rows="4" class="premium-input w-full"
                        placeholder="Describe your request in detail..." required></textarea>
                </div>

                <div>
                    <label
                        class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Attachment
                        (Optional)</label>
                    <input type="file" name="ss_attachment" id="ss_attachment" class="premium-input w-full text-xs">
                    <div id="ss-attachment-preview"></div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('newSSModal')"
                        class="px-6 py-3 font-bold text-slate-500 hover:bg-slate-50 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script src="{{ asset('libs/mammoth/mammoth.browser.min.js') }}"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('emp.ss.data') }}",
            containerSelector: '#ss-container',
            paginationSelector: '#ss-pagination',
            renderCallback: function(data) {
                let html = '';
                data.forEach(sv => {
                    const statusColor = sv.status ? sv.status.status_color : '64748b';
                    const statusName = sv.status ? sv.status.status_name : 'Pending';
                    const initial = ((sv.receiver.first_name || '?')[0] + (sv.receiver.last_name || '?')[0]).toUpperCase();

                    html += `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td>
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">${sv.ss_ref}</span>
                            </td>
                            <td>
                                <span class="font-bold text-slate-700 text-sm">${sv.category ? sv.category.category_name : '-'}</span>
                            </td>
                            <td class="max-w-xs">
                                <p class="text-sm text-slate-500 truncate" title="${sv.ss_description}">
                                    ${sv.ss_description}
                                </p>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">
                                        ${initial}
                                    </div>
                                    <span class="text-sm text-slate-600 font-medium">${sv.receiver.first_name || 'Unknown'} ${sv.receiver.last_name || ''}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                    style="background: #${statusColor};">
                                    ${statusName}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="/emp/ss/${sv.ss_id}"
                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
                return html;
            }
        });

        // Initialize Attachment Preview
        window.initAttachmentPreview({
            inputSelector: '#ss_attachment',
            containerSelector: '#ss-attachment-preview'
        });

        function handleCategoryChange(select) {
            const selectedOption = select.options[select.selectedIndex];
            const receiverId = selectedOption.getAttribute('data-receiver-id');
            const receiverName = selectedOption.getAttribute('data-receiver-name');
            
            const displayContainer = document.getElementById('assigned_receiver_display');
            const foundState = document.getElementById('receiver_found_state');
            const missingState = document.getElementById('receiver_missing_state');
            const receiverNameEl = document.getElementById('assigned_receiver_name');

            if (select.value === '') {
                displayContainer.classList.add('hidden');
                return;
            }

            displayContainer.classList.remove('hidden');
            foundState.classList.add('hidden');
            missingState.classList.add('hidden');
            displayContainer.className = 'p-4 rounded-xl border transition-all'; // reset classes

            if (receiverId && receiverId !== 'null' && receiverId !== '' && receiverId !== '0') {
                // Pre-assigned receiver exists
                displayContainer.classList.add('bg-amber-50', 'border-amber-100');
                foundState.classList.remove('hidden');
                receiverNameEl.innerText = receiverName;
            } else {
                // Missing
                displayContainer.classList.add('bg-rose-50', 'border-rose-100');
                missingState.classList.remove('hidden');
            }
        }

        function validateSSForm(event) {
            const categorySelect = document.getElementById('ss_category_id');
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const receiverId = selectedOption.getAttribute('data-receiver-id');

            if (!receiverId || receiverId === 'null' || receiverId === '0' || receiverId === '') {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Service Unavailable',
                    text: 'Receiver not assigned for this request. Please contact admin.',
                    confirmButtonColor: '#f43f5e'
                });
                return false;
            }
            return true;
        }
    </script>
@endsection
