<!DOCTYPE html>
<html lang="en">
<head>
     @php
                $favPath = \App\Models\AppSetting::where('key', 'favicon_path')->value('value');
                $favUrl = $favPath ? asset('uploads/' . $favPath) : asset('favicon.ico');
            @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Asset Label - {{ $asset->asset_serial }}</title>
    <link rel="icon" type="image/png" href="{{ $favUrl }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#9333ea',
                        brand: {
                            DEFAULT: '#004F68',
                            dark: '#00384a',
                            light: '#006a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .label-container { 
                border: 2px solid #e2e8f0; 
                box-shadow: none; 
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">

    <!-- Print Controls -->
    <div class="no-print fixed top-6 right-6 flex gap-3 z-50">
        <button onclick="window.print()" class="flex items-center gap-2 bg-gradient-to-r from-[#004F68] to-[#006a8a] text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Label
        </button>
        <button onclick="window.close()" class="flex items-center gap-2 bg-white text-slate-600 px-5 py-2.5 rounded-xl shadow hover:bg-slate-50 font-bold border border-slate-200">
            Close
        </button>
    </div>

    <!-- Label Card -->
    <div class="label-container bg-white rounded-2xl shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 relative">
        <!-- Brand Header -->
        <div class="bg-gradient-to-r from-[#004F68] to-[#006a8a] p-4 text-center">
           
            <div class="flex flex-col items-center justify-center mb-2">
                <img src="{{ $favUrl }}" class="h-10 w-10 object-contain drop-shadow-md bg-white/20 rounded p-1 mb-1">
            </div>
            <h1 class="text-white font-display font-bold text-xl tracking-wide uppercase">{{ config('app.name', 'IQC Sense') }}</h1>
            <p class="text-white/80 text-xs font-medium uppercase tracking-widest mt-0.5">Asset Management</p>
        </div>

        <div class="p-6 text-center">
            <!-- Asset Name -->
            <h2 class="text-2xl font-display font-bold text-slate-800 leading-tight mb-1">{{ $asset->asset_name }}</h2>
            
            <!-- Category Badge -->
            <div class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider mb-6">
                {{ $asset->category->category_name ?? 'General Asset' }}
            </div>

            <!-- Barcode -->
            <div class="flex justify-center mb-4">
                <svg id="barcode" class="w-full max-w-[280px]"></svg>
            </div>

            <!-- Meta Data Grid -->
            <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 mt-2">
                <div class="text-center">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Asset Ref</p>
                    <p class="text-sm font-mono font-bold text-slate-700">{{ $asset->asset_ref }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Purchase Date</p>
                    <p class="text-sm font-semibold text-slate-700">{{ $asset->purchase_date ? $asset->purchase_date->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Footer Strip -->
        <div class="bg-slate-50 p-3 text-center border-t border-slate-100">
            <p class="text-[10px] text-slate-400">Property of {{ config('app.name', 'Company') }} • If found, please return to admin.</p>
        </div>
    </div>

    <script>
        JsBarcode("#barcode", "{{ $asset->asset_serial }}", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: true,
            fontSize: 14,
            font: "Inter",
            textMargin: 8,
            marginTop: 10,
            marginBottom: 10,
            lineColor: "#334155" // slate-700
        });

        // Auto print on load (optional)
        // window.onload = function() { setTimeout(window.print, 500); }
    </script>
</body>
</html>
