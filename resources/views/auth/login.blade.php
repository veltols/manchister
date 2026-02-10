<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IQC Sense</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
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
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background: #004F68;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Animated Background Blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.7;
            animation: blob 20s infinite;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            top: -10%;
            left: -10%;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            top: 50%;
            right: -5%;
            animation-delay: 4s;
        }

        .blob-3 {
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            bottom: -10%;
            left: 30%;
            animation-delay: 8s;
        }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .blob {
                width: 300px;
                height: 300px;
            }

            .blob-1 {
                top: -20%;
                left: -20%;
            }

            .blob-2 {
                top: auto;
                bottom: -10%;
                right: -10%;
            }

            .blob-3 {
                display: none;
            }
        }

        @keyframes blob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(30px, -50px) scale(1.1);
            }

            50% {
                transform: translate(-20px, 30px) scale(0.9);
            }

            75% {
                transform: translate(40px, 20px) scale(1.05);
            }
        }

        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        /* Input Focus Effect */
        .premium-input {
            transition: all 0.3s ease;
        }

        .premium-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 79, 104, 0.2);
            border-color: #004F68;
        }

        /* Button Styles */
        .premium-button {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
            color: white;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
            filter: brightness(1.1);
        }

        /* Premium Text Gradient */
        .text-premium {
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        /* Fade In Animation */
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

        /* Floating Animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen flex items-start pt-12 lg:pt-0 lg:items-center justify-center p-4 font-sans">


    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-6xl flex items-center gap-12">

        <!-- Left Side - Branding -->
        <div class="hidden lg:flex flex-1 flex-col items-center text-white animate-fade-in">
            <div class="float mb-8">
                <div
                    class="w-40 h-40 rounded-3xl bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center shadow-2xl p-6">
                    <img src="{{ asset('images/logo.png') }}" alt="IQC Logo" class="w-full h-full object-contain">
                </div>
            </div>

            <h1 class="font-display text-5xl font-bold mb-4 text-center">
                Welcome to<br />
                <span class="text-white">IQC Sense</span>
            </h1>

            <div class="mt-12 flex gap-6">
                <div class="flex flex-col items-center gap-2">
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-2xl text-cyan-300"></i>
                    </div>
                    <span class="text-sm text-white/70 font-medium">Secure</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-bolt text-2xl text-yellow-300"></i>
                    </div>
                    <span class="text-sm text-white/70 font-medium">Fast</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-gem text-2xl text-pink-300"></i>
                    </div>
                    <span class="text-sm text-white/70 font-medium">Premium</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 w-full animate-fade-in" style="animation-delay: 0.2s;">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-8">
                <div
                    class="w-32 h-32 rounded-3xl bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center shadow-2xl p-4">
                    <img src="{{ asset('images/logo.png') }}" alt="IQC Logo" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6 md:p-10 max-w-md mx-auto">
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-dark mb-4 shadow-lg">
                        <i class="fa-solid fa-user text-2xl text-white"></i>
                    </div>
                    <h2 class="font-display text-3xl font-bold text-premium mb-2">Welcome Back</h2>
                    <p class="text-slate-500">Please enter your credentials to continue</p>
                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                        <i class="fa-solid fa-shield-halved text-green-600"></i>
                        <span class="text-green-700 text-sm font-medium">Protected by Two-Factor Authentication</span>
                    </div>
                </div>

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-user text-brand-dark mr-2"></i>Email
                        </label>
                        <div class="relative">
                            <input type="text" name="username"
                                class="premium-input w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white focus:border-brand-dark focus:ring-4 focus:ring-brand-dark/10 transition-all outline-none text-slate-700 font-medium"
                                placeholder="Enter your email" required autofocus>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-lock text-brand-dark mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password"
                                class="premium-input w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white focus:border-brand-dark focus:ring-4 focus:ring-brand-dark/10 transition-all outline-none text-slate-700 font-medium"
                                placeholder="Enter your password" required>
                        </div>
                    </div>



                    <button type="submit"
                        class="premium-button w-full py-4 text-white font-bold rounded-xl shadow-lg relative overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Sign In
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </button>
                </form>


            </div>

            <!-- Mobile Logo -->
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