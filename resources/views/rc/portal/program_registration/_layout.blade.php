{{--
Shared program registration sub-form layout
--}}
@extends('layouts.app')

@section('title', $pageTitle ?? 'Program Registration')
@section('subtitle', 'Request for Program Registration')

@push('styles')
    <style>
        .acc-tab-nav {
            display: flex;
            gap: 0.5rem;
            padding: 0.5rem 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            overflow-x: auto;
        }

        .acc-tab-link {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.75rem 1.25rem;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-decoration: none;
            border-radius: 12px;
            white-space: nowrap;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .acc-tab-link:hover {
            background: #f1f5f9;
            color: #004F68;
        }

        .acc-tab-link.active {
            background: #004F68;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 79, 104, 0.2);
        }

        .acc-tab-link i {
            font-size: 13px;
            opacity: 0.7;
        }

        .acc-tab-link.active i {
            opacity: 1;
        }

        .acc-form-panel {
            background: white;
            padding: 3rem;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            font-size: 10px;
            font-weight: 900;
            color: #94a3b8;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }

        .form-label span.req {
            color: #f43f5e;
            margin-left: 4px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.85rem 1.1rem;
            border: 2px solid #f1f5f9;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #004F68;
            background: white;
            box-shadow: 0 0 0 4px rgba(0, 79, 104, 0.08);
        }

        .form-input:disabled,
        .form-input[readonly] {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
            border-color: #f1f5f9;
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.85rem 2rem;
            background: linear-gradient(135deg, #004F68, #00384a);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 8px 15px rgba(0, 79, 104, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 79, 104, 0.3);
            color: white;
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }

        .btn-danger:hover {
            background: #ffe4e6;
            transform: scale(0.98);
        }

        /* Premium table */
        .acc-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .acc-table thead th {
            font-size: 10px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 1rem 1.25rem;
            text-align: left;
        }

        .acc-table tbody tr {
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .acc-table tbody tr:hover {
            transform: scale(1.01) translateY(-2px);
            z-index: 10;
        }

        .acc-table tbody td {
            padding: 1.25rem;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            background: white;
        }

        .acc-table tbody td:first-child {
            border-left: 1px solid #f1f5f9;
            border-radius: 18px 0 0 18px;
        }

        .acc-table tbody td:last-child {
            border-right: 1px solid #f1f5f9;
            border-radius: 0 18px 18px 0;
        }
    </style>
@endpush

@section('content')
    <div class="animate-fade-in-up"
        style="box-shadow:0 4px 24px rgba(0,79,104,0.08); border-radius:16px; overflow:hidden; border:1px solid rgba(0,79,104,0.06);">

        {{-- Back + Title Bar --}}
        <div class="flex items-center justify-between px-8 py-6"
            style="background: linear-gradient(135deg, #004F68 0%, #00384a 50%, #002233 100%); position: relative; overflow: hidden;">

            {{-- Decorative pattern --}}
            <div
                style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity: 0.3;">
            </div>

            <div class="flex items-center gap-5 relative z-10">
                <a href="{{ route('rc.portal.wizard.step1') }}"
                    class="w-10 h-10 rounded-xl flex items-center justify-center text-white/50 hover:text-white hover:bg-white/10 border border-white/10 backdrop-blur-md transition-all">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h2 class="text-white font-black text-xl tracking-tight leading-none">
                        {{ $pageTitle ?? 'Program Registration' }}
                    </h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span
                            class="px-2 py-0.5 rounded bg-white/10 text-white/40 text-[9px] font-black uppercase tracking-widest border border-white/5">{{ $atp->atp_ref }}</span>
                        <span class="text-white/60 text-[11px] font-bold">{{ $atp->atp_name }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="acc-tab-nav">
            @php
                $tabs = [
                    ['route' => 'rc.portal.program_registration.faculty', 'icon' => 'fa-users', 'label' => 'Faculty Details'],
                    ['route' => 'rc.portal.program_registration.qualification_mapping', 'icon' => 'fa-link', 'label' => 'Qualification Mapping'],
                    ['route' => 'rc.portal.program_registration.submit', 'icon' => 'fa-rocket', 'label' => 'Submit Registration'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <a href="{{ route($tab['route']) }}"
                    class="acc-tab-link {{ request()->routeIs($tab['route']) ? 'active' : '' }}">
                    <i class="fa-solid {{ $tab['icon'] }}"></i>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Form Content --}}
        <div class="acc-form-panel">
            @yield('acc-content')
        </div>

    </div>
@endsection