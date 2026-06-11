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
    <title>Print Asset Label - {{ $asset->asset_ref }}</title>
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
    <div class="bg-white rounded-2xl p-8 label-container shadow-xl max-w-sm mx-auto">
        <div class="flex flex-col items-center justify-center gap-4">
            <!-- Company Header -->
            {{-- <div class="flex items-center gap-3 w-full border-b border-slate-200 pb-4">
                <img src="{{ $logoUrl }}" alt="Logo" class="h-8 object-contain">
                <div>
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-widest">Asset Label</p>
                    <p class="text-[10px] text-slate-400">IQC Asset Management</p>
                </div>
            </div> --}}

            <!-- Asset Info -->
            {{-- <div class="w-full text-center">
                <p class="text-lg font-bold text-slate-800">{{ $asset->asset_name }}</p>
                <p class="text-xs text-slate-500">{{ $asset->category->category_name ?? 'General' }}</p>
            </div> --}}

            <!-- Barcode using asset_ref -->
            <div class="flex justify-center w-full">
                <svg id="barcode"></svg>
            </div>

            <!-- Ref Number -->
            <div class="w-full text-center border-t border-slate-100 pt-3">
                <p class="text-[11px] text-slate-400 uppercase tracking-widest">Reference No.</p>
                <p class="text-base font-mono font-bold text-slate-700">{{ $asset->asset_ref }}</p>
                @if($asset->assigned_to && $asset->assignee)
                <p class="text-xs text-indigo-600 font-semibold mt-1">Assigned: {{ $asset->assignee->first_name }} {{ $asset->assignee->last_name }}</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        JsBarcode("#barcode", "{{ $asset->asset_ref }}", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: false,
            fontSize: 14,
            font: "Inter",
            textMargin: 8,
            marginTop: 10,
            marginBottom: 10,
            lineColor: "#334155"
        });

        // Auto print on load (optional)
        // window.onload = function() { setTimeout(window.print, 500); }
    </script>
</body>
</html>
