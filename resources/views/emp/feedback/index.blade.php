@extends('layouts.app')

@section('title', 'Feedback Form')
@section('subtitle', 'Help us improve your digital workspace experience')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up pb-12">

        <!-- Introduction Header (Premium Glassmorphism) -->
        <div class="relative overflow-hidden rounded-3xl p-10 shadow-[0_8px_30px_rgba(0,79,104,0.12)] border border-white/60"
             style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(240,249,253,0.85) 100%); backdrop-filter: blur(20px);">
             
            <!-- Decorative Orbs -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>
             
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div class="w-24 h-24 rounded-3xl flex items-center justify-center shadow-lg relative overflow-hidden group"
                     style="background: linear-gradient(135deg, #004F68 0%, #0088b3 100%);">
                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <i class="fa-solid fa-comment-dots text-4xl text-white drop-shadow-md relative z-10"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-display font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#004F68] to-[#0088b3] mb-3">
                        Your Voice Matters
                    </h2>
                    <p class="text-slate-600 leading-relaxed text-lg font-medium">
                        As a valued staff member, your feedback is crucial for enhancing this platform.
                        Please spare a few minutes to share your experience and suggestions.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('emp.feedback.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: User Interface -->
            <div class="premium-card p-0 overflow-hidden shadow-[0_8px_24px_rgba(0,0,0,0.04)] border-0">
                <!-- Section Header -->
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-brand flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-sm tracking-wider">01</span>
                    </div>
                    <h3 class="text-xl font-display font-bold text-[#004F68]">User Interface & Accessibility</h3>
                </div>

                <div class="p-8 space-y-8 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Select 1 -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#006a8a] transition-colors">User-friendliness</label>
                            <div class="relative">
                                <select name="a1" class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-3.5 px-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium hover:bg-white text-sm" required>
                                    <option value="" disabled selected>Select Rating</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Average">Average</option>
                                    <option value="Poor">Poor</option>
                                    <option value="very_Poor">Very Poor</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Select 2 -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#006a8a] transition-colors">Visual Appeal</label>
                            <div class="relative">
                                <select name="a2" class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-3.5 px-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium hover:bg-white text-sm" required>
                                    <option value="" disabled selected>Select Rating</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Average">Average</option>
                                    <option value="Poor">Poor</option>
                                    <option value="very_Poor">Very Poor</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Select 3 -->
                        <div class="relative group">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#006a8a] transition-colors">Login/Logout Experience</label>
                            <div class="relative">
                                <select name="a3" class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-3.5 px-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium hover:bg-white text-sm" required>
                                    <option value="" disabled selected>Select Rating</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Average">Average</option>
                                    <option value="Poor">Poor</option>
                                    <option value="very_Poor">Very Poor</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 mt-4 border-t border-slate-100">
                        <label class="block text-[13px] font-bold text-slate-700 mb-4 ml-1">Did you encounter any technical issues?</label>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                            <div class="relative group">
                                <select name="a4" class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 py-3.5 px-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium hover:bg-white text-sm" required>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <div class="md:col-span-3">
                                <textarea name="a5" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-700 py-3.5 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium hover:bg-white text-sm placeholder:text-slate-400 resize-none" placeholder="If yes, please explain the issue in detail here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Feature Experience -->
            <div class="premium-card p-0 overflow-hidden shadow-[0_8px_24px_rgba(0,0,0,0.04)] border-0">
                <!-- Section Header -->
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-brand flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-sm tracking-wider">02</span>
                    </div>
                    <h3 class="text-xl font-display font-bold text-[#004F68]">Feature-Specific Feedback</h3>
                </div>

                <div class="bg-white overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200">
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-1/3">Portal Feature</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-1/4">Rating</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Comments / Suggestions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $features = [
                                        ['name' => 'Interactive Calendar', 'icon' => 'fa-calendar-days', 'ratingName' => 'a6', 'commentName' => 'a7'],
                                        ['name' => 'Real-time Messaging', 'icon' => 'fa-comment-dots', 'ratingName' => 'a8', 'commentName' => 'a9'],
                                        ['name' => 'Task Management', 'icon' => 'fa-list-check', 'ratingName' => 'a10', 'commentName' => 'a11'],
                                        ['name' => 'IT Ticket System', 'icon' => 'fa-headset', 'ratingName' => 'a12', 'commentName' => 'a13'],
                                        ['name' => 'HR Requests Hub', 'icon' => 'fa-users', 'ratingName' => 'a14', 'commentName' => 'a15'],
                                    ];
                                @endphp

                                @foreach ($features as $feature)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-[#006a8a] group-hover:scale-110 transition-transform">
                                                    <i class="fa-solid {{ $feature['icon'] }}"></i>
                                                </div>
                                                <span class="font-bold text-slate-700 text-sm">{{ $feature['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="relative">
                                                <select name="{{ $feature['ratingName'] }}" class="w-full appearance-none bg-white border border-slate-200 text-slate-700 py-2.5 px-4 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium text-sm shadow-sm" required>
                                                    <option value="" disabled selected>Rating</option>
                                                    <option value="Excellent">Excellent</option>
                                                    <option value="Good">Good</option>
                                                    <option value="Average">Average</option>
                                                    <option value="Poor">Poor</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <input type="text" name="{{ $feature['commentName'] }}" class="w-full bg-white border border-slate-200 text-slate-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium text-sm placeholder:text-slate-300 shadow-sm" placeholder="Optional notes...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Section 3: Future & Notes -->
            <div class="premium-card p-0 overflow-hidden shadow-[0_8px_24px_rgba(0,0,0,0.04)] border-0">
                <!-- Section Header -->
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-brand flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-sm tracking-wider">03</span>
                    </div>
                    <h3 class="text-xl font-display font-bold text-[#004F68]">Future Enhancements</h3>
                </div>

                <div class="p-8 space-y-8 bg-white">
                    <div class="relative group mt-2">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 group-focus-within:text-[#006a8a] transition-colors">What new feature(s) would you like to be added to the portal?</label>
                        <textarea name="a16" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-700 py-4 px-5 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium hover:bg-white text-sm placeholder:text-slate-400 resize-none shadow-sm" placeholder="e.g. Mobile app native features, Dark mode toggle, new integrations..."></textarea>
                    </div>

                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1 group-focus-within:text-[#006a8a] transition-colors">Additional Notes / General Remarks</label>
                        <textarea name="a17" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-700 py-4 px-5 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#006a8a]/20 focus:border-[#006a8a] transition-all font-medium hover:bg-white text-sm placeholder:text-slate-400 resize-none shadow-sm" placeholder="Anything else you'd like to share with our development team?"></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Action -->
            <div class="flex justify-center pt-8 pb-12">
                <button type="submit" class="premium-button group px-14 py-4 rounded-2xl shadow-[0_8px_20px_rgba(0,106,138,0.3)] hover:shadow-[0_12px_28px_rgba(0,106,138,0.4)] text-[15px] flex items-center gap-4 transition-all duration-300 transform hover:-translate-y-1">
                    <span class="font-bold tracking-wide">Submit Feedback Securely</span>
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition-colors">
                        <i class="fa-solid fa-paper-plane text-sm group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform duration-300"></i>
                    </div>
                </button>
            </div>
        </form>

    </div>
@endsection
