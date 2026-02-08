<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something Went Wrong</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-50 via-cyan-50/10 to-blue-50/10 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- Error Icon -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-triangle-exclamation text-4xl text-red-600"></i>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-2xl font-bold text-slate-800 mb-3">Something Went Wrong</h1>
            <p class="text-slate-600 mb-6">
                We're sorry, but something unexpected happened. Our team has been notified and we're working to fix the issue.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3">
                <button onclick="window.location.reload()" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200">
                    <i class="fa-solid fa-rotate-right mr-2"></i>
                    Try Again
                </button>
                <a href="{{ url('/') }}" 
                    class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-3 px-6 rounded-xl transition-colors duration-200 inline-block">
                    <i class="fa-solid fa-home mr-2"></i>
                    Go to Homepage
                </a>
            </div>

            <!-- Support Info -->
            <div class="mt-6 pt-6 border-t border-slate-200">
                <p class="text-sm text-slate-500">
                    If the problem persists, please contact support.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
