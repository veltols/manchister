<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - IQC Sense</title>
    @php
        $favPath = \App\Models\AppSetting::where('key', 'favicon_path')->value('value');
        $favUrl = $favPath ? asset('uploads/' . $favPath) : asset('favicon.ico');

        $logoPath = \App\Models\AppSetting::where('key', 'logo_path')->value('value');
        $logoUrl = $logoPath ? asset('uploads/' . $logoPath) : asset('images/logo.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $favUrl }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#004F68',
                        'brand-light': '#006a8a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background: linear-gradient(135deg, #004F68 0%, #006a8a 45%, #1a8aaa 80%, #0ea5e9 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* SVG Pattern Overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* Decorative Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        .orb-1 {
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float-slow 15s infinite alternate;
        }

        .orb-2 {
            bottom: -150px;
            left: -150px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, transparent 70%);
            animation: float-slow 20s infinite alternate-reverse;
        }

        .orb-3 {
            top: 20%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            animation: float-slow 12s infinite alternate;
        }

        @keyframes float-slow {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(50px, 50px) scale(1.1);
            }
        }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .orb-1 {
                width: 300px;
                height: 300px;
                top: -50px;
                right: -50px;
            }

            .orb-2 {
                width: 400px;
                height: 400px;
                bottom: -100px;
                left: -100px;
            }

            .orb-3 {
                display: none;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .otp-input {
            width: 3.5rem;
            height: 3.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            outline: none;
            border-color: #004F68;
            box-shadow: 0 0 0 4px rgba(0, 79, 104, 0.1);
            transform: translateY(-2px);
        }

        .premium-button {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
        }

        .premium-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .premium-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .premium-button:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 79, 104, 0.4);
        }

        .text-premium {
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .icon-3d {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .icon-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.45) 0%, transparent 100%);
            border-radius: 20px 20px 0 0;
            pointer-events: none;
        }

        .icon-3d:hover {
            transform: scale(1.1) rotate(-5deg) translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }
    </style>
</head>

<body class="min-h-screen font-sans overflow-x-hidden overflow-y-auto">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="relative z-10 w-full max-w-6xl flex items-center gap-12">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex flex-1 flex-col items-center text-white animate-fade-in">
            <div class="float mb-8">
                <div
                    class="w-40 h-40 rounded-3xl bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center shadow-2xl p-6">
                    <img src="{{ $logoUrl }}" alt="IQC Logo" class="w-full h-full object-contain">
                </div>
            </div>

            <h1 class="font-display text-5xl font-bold mb-4 text-center">
                Verify Your<br />
                <span class="text-white">Identity</span>
            </h1>

            <p class="text-white/80 text-center max-w-md mt-4">
                We've sent a 6-digit code to your email. Enter it below to complete your login.
            </p>

            <div class="mt-12 flex gap-8">
                <div class="flex flex-col items-center gap-3">
                    <div class="icon-3d" style="background: linear-gradient(145deg, #0ea5e9, #0284c7);">
                        <i class="fa-solid fa-shield-halved text-2xl text-white"></i>
                    </div>
                    <span class="text-xs text-white/80 font-bold uppercase tracking-widest">Secure</span>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="icon-3d" style="background: linear-gradient(145deg, #f59e0b, #d97706);">
                        <i class="fa-solid fa-clock text-2xl text-white"></i>
                    </div>
                    <span class="text-xs text-white/80 font-bold uppercase tracking-widest">10 Min</span>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="icon-3d" style="background: linear-gradient(145deg, #ec4899, #db2777);">
                        <i class="fa-solid fa-key text-2xl text-white"></i>
                    </div>
                    <span class="text-xs text-white/80 font-bold uppercase tracking-widest">One-Time</span>
                </div>
            </div>
        </div>

        <!-- Right Side - OTP Verification Form -->
        <div class="flex-1 w-full animate-fade-in" style="animation-delay: 0.2s;">
            <div class="lg:hidden flex justify-center mb-8">
                <div
                    class="w-32 h-32 rounded-3xl bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center shadow-2xl p-4">
                    <img src="{{ $logoUrl }}" alt="IQC Logo" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6 md:p-10 max-w-md mx-auto">
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-6">
                        <div class="icon-3d"
                            style="background: linear-gradient(145deg, #004F68, #00384a); width: 60px; height: 60px;">
                            <i class="fa-solid fa-shield-halved text-xl text-white relative z-10"></i>
                        </div>
                    </div>
                    <h2 class="font-display text-3xl font-bold text-premium mb-2">Two-Factor Authentication</h2>
                    <p class="text-slate-500 mb-1">Password verified! ✓</p>
                    <p class="text-slate-600 text-sm">Enter the 6-digit code sent to</p>
                    <p class="text-slate-700 font-semibold">{{ $email }}</p>
                </div>

                <form action="{{ route('login.otp.verify.post') }}" method="POST" id="otpForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- OTP Input Boxes -->
                    <div class="flex justify-center gap-2 md:gap-3">
                        <input type="text" maxlength="1" class="otp-input" id="otp1" data-index="0" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" id="otp2" data-index="1" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" id="otp3" data-index="2" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" id="otp4" data-index="3" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" id="otp5" data-index="4" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" id="otp6" data-index="5" autocomplete="off">
                    </div>

                    <!-- Hidden input for actual OTP submission -->
                    <input type="hidden" name="otp" id="otpValue">

                    <button type="submit"
                        class="premium-button w-full py-4 text-white font-bold rounded-xl shadow-lg relative overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Complete Login
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </button>

                    <div class="text-center space-y-3">
                        <p class="text-slate-600 text-sm mt-4">
                            Code expires in 10 minutes
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center gap-2 text-slate-600 hover:text-brand-dark font-medium transition-colors text-sm">
                                <i class="fa-solid fa-arrow-left"></i>
                                Cancel & Return to Login
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="lg:hidden text-center mt-8">
                <p class="text-white/80 text-sm">© {{date('Y')}} IQC Sense. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // OTP Input Logic
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpForm = document.getElementById('otpForm');
        const otpValue = document.getElementById('otpValue');

        // Auto-focus first input
        otpInputs[0].focus();

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;

                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    e.target.value = '';
                    return;
                }

                // Move to next input
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }

                // Update hidden input
                updateOtpValue();
            });

            input.addEventListener('keydown', (e) => {
                // Move to previous input on backspace
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            // Handle paste
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').slice(0, 6);

                if (!/^\d+$/.test(pastedData)) return;

                pastedData.split('').forEach((char, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                    }
                });

                updateOtpValue();
                otpInputs[Math.min(pastedData.length, 5)].focus();
            });
        });

        function updateOtpValue() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpValue.value = otp;
        }

        // Form submission
        otpForm.addEventListener('submit', (e) => {
            updateOtpValue();
            if (otpValue.value.length !== 6) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Please enter all 6 digits'
                });
            }
        });

        // Show errors/success messages
        window.addEventListener('DOMContentLoaded', () => {
            @if($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: '{{ $errors->first() }}'
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif
        });
    </script>

</body>

</html>