@extends('layouts.app')

@section('title', 'Feedback')
@section('subtitle', 'Portal User Feedback')

@section('content')
    <div class="h-[calc(100vh-12rem)] flex flex-col gap-6 animate-fade-in-up">

        <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-slate-100">
            <h2 class="font-bold text-premium text-lg font-display">Feedback Results</h2>
            <a href="{{ route('admin.feedback.export') }}" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-file-csv mr-2"></i> Export CSV
            </a>
        </div>

        <div class="flex-1 overflow-auto bg-white rounded-xl shadow-sm border border-slate-100 premium-table-wrapper">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-500 font-bold sticky top-0 z-10">
                    <tr>
                        <th class="p-4 border-b border-slate-100">ID</th>
                        <th class="p-4 border-b border-slate-100">Employee</th>
                        <th class="p-4 border-b border-slate-100">Date</th>
                        <!-- Dynamic Columns for Answers -->
                        @for($i = 1; $i <= 17; $i++)
                            <th class="p-4 border-b border-slate-100 min-w-[100px]" title="Answer {{ $i }}">Q{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($feedbacks as $fb)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="p-4 text-slate-400">#{{ $fb->record_id }}</td>
                            <td class="p-4 font-bold text-slate-700">{{ $fb->first_name }} {{ $fb->last_name }}</td>
                            <td class="p-4 text-slate-500">{{ $fb->added_date }}</td>
                            @for($i = 1; $i <= 17; $i++)
                                @php $ans = "a$i"; @endphp
                                <td class="p-4 text-slate-600 max-w-xs truncate" title="{{ $fb->$ans }}">
                                    {{ Str::limit($fb->$ans, 20) }}
                                </td>
                            @endfor
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="p-12 text-center text-slate-400">No feedback records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4">
            {{ $feedbacks->links() }}
        </div>

    </div>
@endsection
