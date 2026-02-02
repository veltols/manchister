@extends('layouts.app')

@section('title', 'Assets Inventory')
@section('subtitle', 'Manage IT Assets')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                <a href="{{ route('admin.assets.index') }}"
                    class="px-4 py-2 rounded-lg text-sm font-bold {{ request('status') == 'all' || !request('status') ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">All</a>
                <a href="{{ route('admin.assets.index', ['status' => 'expiring']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-bold {{ request('status') == 'expiring' ? 'bg-amber-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">Expiring
                    Soon</a>
                <a href="{{ route('admin.assets.index', ['status' => 'expired']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-bold {{ request('status') == 'expired' ? 'bg-rose-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-50' }}">Expired</a>
            </div>
            <button onclick="document.getElementById('newAssetModal').showModal()" class="premium-button px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> Add Asset
            </button>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="p-4 border-b border-slate-100">REF</th>
                            <th class="p-4 border-b border-slate-100">Asset Name</th>
                            <th class="p-4 border-b border-slate-100">Serial</th>
                            <th class="p-4 border-b border-slate-100">Category</th>
                            <th class="p-4 border-b border-slate-100">Expiry</th>
                            <th class="p-4 border-b border-slate-100">Assigned To</th>
                            <th class="p-4 border-b border-slate-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($assets as $asset)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 font-mono text-slate-500 text-xs">{{ $asset->asset_ref }}</td>
                                <td class="p-4 font-bold text-slate-700">{{ $asset->asset_name }}</td>
                                <td class="p-4 text-slate-500 font-mono text-xs">{{ $asset->asset_serial }}</td>
                                <td class="p-4 text-slate-600">{{ $asset->category->category_name ?? '-' }}</td>
                                <td class="p-4">
                                    @if($asset->expiry_date)
                                        <span
                                            class="text-xs font-bold {{ \Carbon\Carbon::parse($asset->expiry_date)->isPast() ? 'text-rose-600' : 'text-emerald-600' }}">
                                            {{ \Carbon\Carbon::parse($asset->expiry_date)->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($asset->assignedTo)
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-[10px] font-bold">
                                                {{ substr($asset->assignedTo->first_name, 0, 1) }}
                                            </div>
                                            <span class="text-slate-700 text-xs">{{ $asset->assignedTo->first_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <button class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Assign</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $assets->links() }}
            </div>
        </div>
    </div>

    <!-- New Asset Modal -->
    <dialog id="newAssetModal" class="modal rounded-2xl shadow-2xl p-0 w-full max-w-2xl backdrop:bg-slate-900/50">
        <div class="bg-white">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-lg text-premium">Add New Asset</h3>
                <button onclick="document.getElementById('newAssetModal').close()"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="p-8">
                <form action="{{ route('admin.assets.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Asset
                            Name</label>
                        <input type="text" name="asset_name" class="premium-input w-full" required>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category</label>
                        <select name="category_id" class="premium-input w-full" required>
                            @foreach(\App\Models\AssetCategory::orderBy('category_name')->get() as $cat)
                                <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Status</label>
                        <select name="status_id" class="premium-input w-full" required>
                            @foreach(\App\Models\AssetStatus::orderBy('status_name')->get() as $st)
                                <option value="{{ $st->status_id }}">{{ $st->status_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Serial
                            Number</label>
                        <input type="text" name="asset_serial" class="premium-input w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Expiry
                            Date</label>
                        <input type="date" name="expiry_date" class="premium-input w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Purchase
                            Date</label>
                        <input type="date" name="purchase_date" class="premium-input w-full">
                    </div>

                    <div class="md:col-span-2 pt-4 flex gap-4">
                        <button type="submit" class="premium-button flex-1 py-3">Save Asset</button>
                        <button type="button" onclick="document.getElementById('newAssetModal').close()"
                            class="py-3 px-6 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>
@endsection
