@extends('layouts.app')

@section('title', 'Organization Chart')
@section('subtitle', 'Departmental Structure')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="{{ route('hr.departments.index') }}"
                    class="hover:text-indigo-600 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Back to Department List
                </a>
            </div>
        </div>

        <div class="premium-card p-10 overflow-x-auto">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold font-display text-premium">Organization Structure</h2>
                <p class="text-slate-500">Visual representation of departments</p>
            </div>

            <div class="tf-tree tf-gap-sm">
                <ul>
                    <li>
                        <span class="tf-nc premium-node">
                            <div class="font-bold text-slate-800 text-lg">General Directorate</div>
                        </span>
                        <ul>
                            @foreach($departments as $dept)
                                <li>
                                    <span class="tf-nc premium-node {{ $dept->children->count() > 0 ? 'has-children' : '' }}">
                                        <div class="font-bold text-slate-700">{{ $dept->department_name }}</div>
                                        @if($dept->department_code)
                                            <div class="text-[10px] text-slate-400 uppercase tracking-wider mt-1">
                                                {{ $dept->department_code }}</div>
                                        @endif
                                    </span>
                                    {{-- Recursive children would go here if we had a parent_id relationship properly set up for
                                    deeply nested departments --}}
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Tree CSS (Simple Implementation) */
            .tf-tree {
                font-size: 12px;
                overflow: auto;
                text-align: center;
            }

            .tf-tree ul {
                display: inline-flex;
                margin: 0;
                padding: 0;
            }

            .tf-tree li {
                align-items: center;
                display: flex;
                flex-direction: column;
                flex-wrap: wrap;
                padding: 0 1em;
                position: relative;
            }

            .tf-tree li ul {
                margin: 2em 0;
            }

            .tf-tree li li:before {
                border-top: 1px solid #cbd5e1;
                content: "";
                display: block;
                height: 0.0625em;
                left: -0.03125em;
                position: absolute;
                top: -1.03125em;
                width: 100%;
            }

            .tf-tree li li:first-child:before {
                left: 50%;
                max-width: 50%;
            }

            .tf-tree li li:last-child:before {
                left: auto;
                max-width: 50%;
                right: 50%;
            }

            .tf-tree li li:only-child:before {
                display: none;
            }

            .tf-tree li li:only-child>.tf-nc:before,
            .tf-tree li li:only-child>.tf-nc:before {
                height: 1.0625em;
                top: -1.0625em;
            }

            .tf-tree li li:before {
                top: -1.03125em;
            }

            .tf-tree .tf-nc {
                display: inline-block;
                padding: 1em;
                border: 1px solid #e2e8f0;
                text-align: center;
                background-color: white;
                border-radius: 0.75rem;
                position: relative;
                box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                min-width: 150px;
            }

            .tf-tree .tf-nc:before,
            .tf-tree .tf-nc:after {
                border-left: 1px solid #cbd5e1;
                content: "";
                display: block;
                height: 1em;
                left: 50%;
                position: absolute;
                width: 0;
            }

            .tf-tree .tf-nc:before {
                top: -1.03125em;
            }

            .tf-tree .tf-nc:after {
                top: calc(100% + 0.03125em);
            }

            .tf-tree li:last-child>.tf-nc:after,
            .tf-tree li:last-child>.tf-nc:after {
                display: none;
            }

            .tf-tree li>.tf-nc:only-child:after {
                display: none;
            }

            /* Premium Styling */
            .premium-node {
                transition: all 0.3s ease;
            }

            .premium-node:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
                border-color: #6366f1;
            }
        </style>
    @endpush
@endsection
