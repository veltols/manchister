@extends('rc.portal.program_registration._layout')
@php $pageTitle = 'Submit Registration'; @endphp

@section('acc-content')
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-brand/10 text-brand rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-file-shield text-3xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Final Review & Submission</h2>
            <p class="text-slate-500 mt-2">Please review the terms and conditions before submitting your program
                registration request.</p>
        </div>

        <div class="premium-card p-8 mb-8"
            style="border-radius:24px; background: #fff; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
                Terms & Conditions for Submission
            </h3>

            <div class="space-y-6 text-slate-600 leading-relaxed text-sm">
                <div class="flex gap-4">
                    <span class="font-black text-brand shrink-0">01.</span>
                    <p><strong class="text-slate-800">Accuracy of Information:</strong> By submitting your information, you
                        confirm that all details provided are true, accurate, and complete to the best of your knowledge.
                        Any false or misleading information may result in the rejection of your application or
                        certification.</p>
                </div>
                <div class="flex gap-4">
                    <span class="font-black text-brand shrink-0">02.</span>
                    <p><strong class="text-slate-800">Acknowledgment and Acceptance:</strong> You acknowledge and accept
                        that the information you have submitted will be used to process your application or qualification.
                        The Awarding Body is not responsible for any discrepancies resulting from incorrect or incomplete
                        submissions.</p>
                </div>
                <div class="flex gap-4">
                    <span class="font-black text-brand shrink-0">03.</span>
                    <p><strong class="text-slate-800">Verification and Review:</strong> The Awarding Body reserves the right
                        to verify the submitted information and may request additional documentation or clarification if
                        necessary. If the information meets the required standards, your application will proceed to the
                        next stage of the process.</p>
                </div>
                <div class="flex gap-4">
                    <span class="font-black text-brand shrink-0">04.</span>
                    <p><strong class="text-slate-800">Next Level Progression:</strong> Upon successful submission and
                        acceptance of the information, you will proceed to the next level. Any failure to meet the required
                        standards at this stage may result in disqualification or delay.</p>
                </div>
                <div class="flex gap-4">
                    <span class="font-black text-brand shrink-0">05.</span>
                    <p><strong class="text-slate-800">Liability:</strong> You agree to bear full responsibility for the
                        accuracy of the information provided. Any consequences arising from inaccurate information are
                        solely your responsibility.</p>
                </div>
                <div class="flex gap-4">
                    <span class="font-black text-brand shrink-0">06.</span>
                    <p><strong class="text-slate-800">Data Protection:</strong> All personal information will be handled in
                        compliance with UAE data protection laws and will only be used for the purpose of processing your
                        application.</p>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-slate-100">
                <form id="submissionForm" method="POST"
                    action="{{ route('rc.portal.program_registration.submit.process') }}">
                    @csrf
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-6">
                        <input type="checkbox" id="agree" name="terms" value="1" required
                            class="w-5 h-5 rounded border-slate-300 text-brand focus:ring-brand">
                        <label for="agree" class="text-sm font-bold text-slate-700 cursor-pointer">
                            I have read and agree to the Terms & Conditions mentioned above.
                        </label>
                    </div>

                    <button type="button" onclick="confirmSubmission()"
                        class="w-full py-4 bg-brand text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-brand/90 transition-all shadow-xl shadow-brand/20 flex items-center justify-center gap-3">
                        Submit Program Registration
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                    @error('terms')
                        <span class="text-xs font-bold text-red-500 mt-2 block text-center">{{ $message }}</span>
                    @enderror
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmSubmission() {
            const checkbox = document.getElementById('agree');

            if (!checkbox.checked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Agreement Required',
                    text: 'Please read and agree to the Terms & Conditions before submitting.',
                    confirmButtonColor: '#004F68'
                });
                return;
            }

            Swal.fire({
                title: 'Final Submission',
                text: 'Are you sure you want to submit your program registration? This action cannot be undone.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#004F68',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Submit Now!',
                cancelButtonText: 'Review Again'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'We are submitting your registration.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('submissionForm').submit();
                }
            });
        }
    </script>
@endsection