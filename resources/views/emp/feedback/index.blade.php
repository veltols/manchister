@extends('layouts.app')

@section('title', 'Feedback Form')
@section('subtitle', 'Help us improve your digital workspace experience')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Introduction Header -->
        <div class="premium-card p-10 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div
                    class="w-24 h-24 rounded-3xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner">
                    <i class="fa-solid fa-comment-dots text-4xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-display font-bold text-premium mb-3">Your Voice Matters</h2>
                    <p class="text-slate-500 leading-relaxed text-lg">
                        As a valued staff member, your feedback is crucial for enhancing this platform.
                        Please spare a few minutes to share your experience and suggestions.
                    </p>
                </div>
            </div>
            <!-- Decorative background -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-50/50 rounded-full blur-3xl"></div>
        </div>

        <form action="{{ route('emp.feedback.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: User Interface -->
            <div class="premium-card p-8 space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">01</span>
                    <h3 class="text-xl font-display font-bold text-premium">User Interface & Accessibility</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">User-friendliness</label>
                        <select name="a1" class="premium-input w-full" required>
                            <option value="">Select Rating</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Average">Average</option>
                            <option value="Poor">Poor</option>
                            <option value="very_Poor">Very Poor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Visual
                            Appeal</label>
                        <select name="a2" class="premium-input w-full" required>
                            <option value="">Select Rating</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Average">Average</option>
                            <option value="Poor">Poor</option>
                            <option value="very_Poor">Very Poor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Login/Logout
                            Experience</label>
                        <select name="a3" class="premium-input w-full" required>
                            <option value="">Select Rating</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Good">Good</option>
                            <option value="Average">Average</option>
                            <option value="Poor">Poor</option>
                            <option value="very_Poor">Very Poor</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-50">
                    <label class="block text-sm font-bold text-slate-700 mb-4">Did you encounter any technical
                        issues?</label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <select name="a4" class="premium-input w-full" required>
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                        <div class="md:col-span-3">
                            <textarea name="a5" rows="2" class="premium-input w-full"
                                placeholder="If yes, please explain the issue..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Feature Experience -->
            <div class="premium-card p-8 space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">02</span>
                    <h3 class="text-xl font-display font-bold text-premium">Feature-Specific Feedback</h3>
                </div>

                <div class="overflow-x-auto -mx-8">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th
                                    class="text-left px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Portal Feature</th>
                                <th
                                    class="text-left px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Rating</th>
                                <th
                                    class="text-left px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Comments / Suggestions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <!-- Calendar -->
                            <tr>
                                <td class="px-8 py-6">
                                    <span class="font-bold text-slate-700">Interactive Calendar</span>
                                </td>
                                <td class="px-8 py-6">
                                    <select name="a6" class="premium-input w-40">
                                        <option value="Average">Average</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </td>
                                <td class="px-8 py-6">
                                    <textarea name="a7" rows="1" class="premium-input w-full text-sm"
                                        placeholder="Optional comments..."></textarea>
                                </td>
                            </tr>
                            <!-- Chat -->
                            <tr>
                                <td class="px-8 py-6">
                                    <span class="font-bold text-slate-700">Real-time Messaging</span>
                                </td>
                                <td class="px-8 py-6">
                                    <select name="a8" class="premium-input w-40">
                                        <option value="Average">Average</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </td>
                                <td class="px-8 py-6">
                                    <textarea name="a9" rows="1" class="premium-input w-full text-sm"
                                        placeholder="Optional comments..."></textarea>
                                </td>
                            </tr>
                            <!-- Tasks -->
                            <tr>
                                <td class="px-8 py-6">
                                    <span class="font-bold text-slate-700">Task Management</span>
                                </td>
                                <td class="px-8 py-6">
                                    <select name="a10" class="premium-input w-40">
                                        <option value="Average">Average</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </td>
                                <td class="px-8 py-6">
                                    <textarea name="a11" rows="1" class="premium-input w-full text-sm"
                                        placeholder="Optional comments..."></textarea>
                                </td>
                            </tr>
                            <!-- IT Tickets -->
                            <tr>
                                <td class="px-8 py-6">
                                    <span class="font-bold text-slate-700">IT Ticket System</span>
                                </td>
                                <td class="px-8 py-6">
                                    <select name="a12" class="premium-input w-40">
                                        <option value="Average">Average</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </td>
                                <td class="px-8 py-6">
                                    <textarea name="a13" rows="1" class="premium-input w-full text-sm"
                                        placeholder="Optional comments..."></textarea>
                                </td>
                            </tr>
                            <!-- HR Requests -->
                            <tr>
                                <td class="px-8 py-6">
                                    <span class="font-bold text-slate-700">HR Requests Hub</span>
                                </td>
                                <td class="px-8 py-6">
                                    <select name="a14" class="premium-input w-40">
                                        <option value="Average">Average</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Good">Good</option>
                                        <option value="Poor">Poor</option>
                                    </select>
                                </td>
                                <td class="px-8 py-6">
                                    <textarea name="a15" rows="1" class="premium-input w-full text-sm"
                                        placeholder="Optional comments..."></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section 3: Future & Notes -->
            <div class="premium-card p-8 space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">03</span>
                    <h3 class="text-xl font-display font-bold text-premium">Future Enhancements</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">What new feature(s) would you like to be
                            added to the portal?</label>
                        <textarea name="a16" rows="4" class="premium-input w-full"
                            placeholder="e.g. Mobile app, Dark mode toggle, more HR forms..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Additional Notes / General
                            Remarks</label>
                        <textarea name="a17" rows="3" class="premium-input w-full"
                            placeholder="Anything else you'd like to share?"></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Action -->
            <div class="flex justify-center pt-6 pb-20">
                <button type="submit" class="premium-button px-12 py-5 text-lg">
                    <span>Submit Feedback Form</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>

    </div>
@endsection
