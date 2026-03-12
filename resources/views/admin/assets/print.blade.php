<!DOCTYPE html>
<html lang="en">
<head>
     @php
                $favPath = \App\Models\AppSetting::where('key', 'favicon_path')->value('value');
        $favUrl = $favPath ? asset('uploads/' . $favPath) : asset('favicon.ico');

        $logoPath = \App\Models\AppSetting::where('key', 'logo_path')->value('value');
        $logoUrl = $logoPath ? asset('uploads/' . $logoPath) : asset('images/logo.png');
            @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Asset Label - {{ $asset->asset_serial }}</title>
    <link rel="icon" type="image/png" href="{{ $favUrl }}">
    <script src="{{ asset('libs/tailwindcss/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('libs/fonts/fonts.css') }}">
    <script src="{{ asset('libs/jsbarcode/JsBarcode.all.min.js') }}"></script>
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
    <div class="bg-white p-8 label-container">
        <div class="flex flex-col items-center justify-center">
            <!-- Barcode -->
            <div class="flex justify-center">
                <svg id="barcode"></svg>
            </div>
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
