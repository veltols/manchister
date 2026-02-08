@extends('layouts.app')

@section('title', 'Organization Chart')
@section('subtitle', 'Departmental Structure')

@section('content')
    <div class="w-full max-w-full space-y-6 animate-fade-in-up overflow-x-hidden">

        <!-- Structure Navigation -->
        <div class="px-1">
            @include('hr.partials.structure_nav')
        </div>

       
        <div id="chartContainer" class="premium-card relative bg-slate-50/30 overflow-hidden min-h-[500px] border border-slate-100 h-[calc(100vh-280px)]">
            
            <!-- Floating Chart Controls (Absolute to prevent shifting) -->
            <div class="absolute top-6 right-6 flex items-center gap-2 p-1.5 bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl shadow-2xl z-40">
                <div class="flex items-center border-r border-slate-100 pr-1.5 mr-1.5">
                    <button id="zoomOut" class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-50 transition-colors" title="Zoom Out">
                        <i class="fa-solid fa-minus text-xs md:text-sm"></i>
                    </button>
                    <span id="zoomLevel" class="text-[10px] md:text-xs font-bold text-slate-400 w-8 md:w-12 text-center select-none font-mono">100%</span>
                    <button id="zoomIn" class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-50 transition-colors" title="Zoom In">
                        <i class="fa-solid fa-plus text-xs md:text-sm"></i>
                    </button>
                    <button id="zoomReset" class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl text-indigo-600 hover:bg-indigo-50 transition-colors ml-0.5" title="Reset Zoom">
                        <i class="fa-solid fa-arrows-to-dot text-xs md:text-sm"></i>
                    </button>
                </div>
                <button id="toggleFullscreen" class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-50 transition-colors" title="Toggle Fullscreen">
                    <i class="fa-solid fa-expand text-xs md:text-sm"></i>
                </button>
                <div class="h-6 w-px bg-slate-100 mx-1"></div>
                <button id="exportPDF" class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl text-indigo-600 hover:bg-indigo-50 transition-colors" title="Export as PDF">
                    <i class="fa-solid fa-file-pdf text-xs md:text-sm"></i>
                </button>
            </div>
            
            {{-- Floating controls removed as per user request --}}

            <!-- Loading State -->
            <div id="loadingOverlay" class="absolute inset-0 z-50 bg-white/90 backdrop-blur-md flex items-center justify-center transition-opacity duration-500">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative w-16 h-16">
                        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
                    </div>
                    <p class="text-sm font-bold text-slate-600 tracking-wide uppercase">Mapping Hierarchy...</p>
                </div>
            </div>

            <div id="chartWrapper" class="w-full h-full cursor-grab active:cursor-grabbing overflow-auto p-8 md:p-12 scroll-smooth">
                <div id="chart_div" class="origin-top transition-transform duration-200 ease-out flex justify-center pb-20"></div>
            </div>

            <!-- Mobile Tooltip Panel -->
            <div class="absolute bottom-4 left-4 right-4 md:left-1/2 md:-translate-x-1/2 md:w-auto lg:hidden bg-slate-900/90 backdrop-blur-lg text-white px-5 py-3 rounded-2xl text-[10px] font-semibold shadow-2xl flex items-center justify-between gap-4 z-40 pointer-events-none border border-white/10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-indigo-500 flex items-center justify-center">
                        <i class="fa-solid fa-mouse-pointer text-[10px]"></i>
                    </div>
                    <span>DRAG TO PAN</span>
                </div>
                <div class="h-4 w-px bg-white/20"></div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500 flex items-center justify-center">
                        <i class="fa-solid fa-magnifying-glass-plus text-[10px]"></i>
                    </div>
                    <span>USE ICONS TO ZOOM</span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            let chart;
            let data;
            let options = {
                'allowHtml': true, 
                'size': 'medium',
                'nodeClass': 'premium-org-node',
                'selectedNodeClass': 'premium-org-node-selected'
            };

            google.charts.load('current', {packages:["orgchart"]});
            google.charts.setOnLoadCallback(initializeChart);

            function initializeChart() {
                data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');
                
                const rows = [
                    @foreach($departments as $dept)
                        @php
                            $deptId = (string)$dept->department_id;
                            $parentId = (string)$dept->main_department_id;
                            
                            if ($parentId == '0' || $parentId == '' || !$departments->contains('department_id', $dept->main_department_id)) {
                                $parentId = '';
                            }

                            $name = e($dept->department_name);
                            $code = $dept->department_code ? '<div class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest mt-1.5">' . e($dept->department_code) . '</div>' : '';
                            
                            $managerHtml = '';
                            if($dept->lineManager) {
                                $initial = substr($dept->lineManager->first_name, 0, 1);
                                $mName = e($dept->lineManager->first_name . ' ' . $dept->lineManager->last_name);
                                $managerHtml = '<div class="mt-4 pt-4 border-t border-slate-100/80 flex items-center gap-2.5 text-left">
                                                    <div class="shrink-0 w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-[11px] font-bold shadow-indigo-200 shadow-lg">'.$initial.'</div>
                                                    <div class="min-w-0">
                                                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter leading-none mb-1">line manager</div>
                                                        <div class="text-[11px] text-slate-700 font-bold truncate">'.$mName.'</div>
                                                    </div>
                                                </div>';
                            } else {
                                $managerHtml = '<div class="mt-4 pt-4 border-t border-slate-100/50">
                                                    <div class="text-[9px] text-slate-300 font-bold uppercase tracking-widest leading-none italic text-left px-1">Unassigned</div>
                                                </div>';
                            }

                            $levelColor = 'bg-brand';
                            if (!$parentId) $levelColor = 'bg-gradient-to-r from-brand to-indigo-600';
                            
                            $html = '<div class="org-node-inner relative overflow-hidden">
                                        <div class="absolute top-0 left-0 right-0 h-1.5 '.$levelColor.'"></div>
                                        <div class="text-sm md:text-base font-extrabold text-slate-800 tracking-tight leading-tight text-left px-0.5 mt-2">' . $name . '</div>
                                        ' . $code . '
                                        ' . $managerHtml . '
                                    </div>';
                        @endphp
                        [{v: @json($deptId), f: @json($html)}, @json($parentId), @json($dept->department_name)],
                    @endforeach
                ];

                data.addRows(rows);
                chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                
                drawChart();

                // Smoothly fade out overlay
                const overlay = document.getElementById('loadingOverlay');
                overlay.style.opacity = '0';
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 500);

                // Initialize Controls
                initControls();
                
                // Add Resize Listener with Debounce
                window.addEventListener('resize', debounce(() => {
                    drawChart();
                }, 150));
            }

            function drawChart() {
                if (chart && data) {
                    chart.draw(data, options);
                }
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            function initControls() {
                const chartDiv = document.getElementById('chart_div');
                const zoomIn = document.getElementById('zoomIn');
                const zoomOut = document.getElementById('zoomOut');
                const zoomReset = document.getElementById('zoomReset');
                const zoomLevelDisplay = document.getElementById('zoomLevel');
                const toggleFullscreen = document.getElementById('toggleFullscreen');
                const container = document.getElementById('chartContainer');
                const wrapper = document.getElementById('chartWrapper');

                let scale = 1;
                const updateZoom = () => {
                    chartDiv.style.transform = `scale(${scale})`;
                    chartDiv.style.transformOrigin = 'top center';
                    zoomLevelDisplay.innerText = Math.round(scale * 100) + '%';
                };

                zoomIn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (scale < 3) { scale += 0.15; updateZoom(); }
                });

                zoomOut.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (scale > 0.2) { scale -= 0.15; updateZoom(); }
                });

                zoomReset.addEventListener('click', (e) => {
                    e.stopPropagation();
                    scale = 1;
                    updateZoom();
                    wrapper.scrollTo({ top: 0, left: wrapper.scrollWidth / 2 - wrapper.clientWidth / 2, behavior: 'smooth' });
                });

                toggleFullscreen.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (!document.fullscreenElement) {
                        container.requestFullscreen().catch(err => {
                            console.error(`Error: ${err.message}`);
                        });
                        toggleFullscreen.innerHTML = '<i class="fa-solid fa-compress text-xs md:text-sm"></i>';
                    } else {
                        document.exitFullscreen();
                        toggleFullscreen.innerHTML = '<i class="fa-solid fa-expand text-xs md:text-sm"></i>';
                    }
                });

                // Advanced Drag-to-Pan (Mouse & Touch)
                let isDown = false;
                let startX, startY, scrollLeft, scrollTop;

                const startDragging = (e) => {
                    if (e.target.closest('button')) return; 
                    isDown = true;
                    wrapper.classList.add('cursor-grabbing');
                    const pageX = e.pageX || (e.touches ? e.touches[0].pageX : 0);
                    const pageY = e.pageY || (e.touches ? e.touches[0].pageY : 0);
                    startX = pageX - wrapper.offsetLeft;
                    startY = pageY - wrapper.offsetTop;
                    scrollLeft = wrapper.scrollLeft;
                    scrollTop = wrapper.scrollTop;
                };

                const stopDragging = () => {
                    isDown = false;
                    wrapper.classList.remove('cursor-grabbing');
                };

                const moveDragging = (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const pageX = e.pageX || (e.touches ? e.touches[0].pageX : 0);
                    const pageY = e.pageY || (e.touches ? e.touches[0].pageY : 0);
                    const x = pageX - wrapper.offsetLeft;
                    const y = pageY - wrapper.offsetTop;
                    const walkX = (x - startX) * 1.8;
                    const walkY = (y - startY) * 1.8;
                    wrapper.scrollLeft = scrollLeft - walkX;
                    wrapper.scrollTop = scrollTop - walkY;
                };

                wrapper.addEventListener('mousedown', startDragging);
                wrapper.addEventListener('touchstart', startDragging, { passive: false });
                window.addEventListener('mouseup', stopDragging);
                window.addEventListener('touchend', stopDragging);
                wrapper.addEventListener('mousemove', moveDragging);
                wrapper.addEventListener('touchmove', moveDragging, { passive: false });

                // PDF Export
                const exportPDF = document.getElementById('exportPDF');
                exportPDF.addEventListener('click', async () => {
                    const { jsPDF } = window.jspdf;
                    const chartDiv = document.getElementById('chart_div');
                    
                    const originalBtn = exportPDF.innerHTML;
                    exportPDF.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs md:text-sm"></i>';
                    exportPDF.disabled = true;

                    // Store current scale
                    const currentScale = scale;
                    
                    try {
                        // Create high-res capture
                        const canvas = await html2canvas(chartDiv, {
                            scale: 3, // Ultra-high resolution
                            useCORS: true,
                            backgroundColor: '#f8fafc',
                            logging: false,
                            onclone: (clonedDoc) => {
                                const clonedChart = clonedDoc.getElementById('chart_div');
                                clonedChart.style.transform = 'scale(1)';
                                clonedChart.style.transformOrigin = 'top center';
                                clonedChart.style.width = 'auto';
                                clonedChart.style.height = 'auto';
                                clonedChart.style.overflow = 'visible';
                                clonedChart.style.display = 'inline-flex';
                                clonedChart.style.margin = '40px';
                                
                                // Add a professional header inside the capture area for "beautification"
                                const header = clonedDoc.createElement('div');
                                header.style.padding = '40px 60px';
                                header.style.borderBottom = '2px solid #e2e8f0';
                                header.style.marginBottom = '40px';
                                header.style.display = 'flex';
                                header.style.alignItems = 'center';
                                header.style.justifyContent = 'space-between';
                                header.style.width = '100%';
                                header.style.background = 'white';
                                
                                header.innerHTML = `
                                    <div style="display: flex; align-items: center; gap: 20px;">
                                        <div style="width: 50px; height: 50px; background: #004F68; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                            <span style="color: white; font-weight: bold; font-family: sans-serif; font-size: 24px;">I</span>
                                        </div>
                                        <div>
                                            <h1 style="margin: 0; font-family: sans-serif; font-size: 32px; font-weight: 800; color: #004F68;">IQC SENSE</h1>
                                            <p style="margin: 4px 0 0; font-family: sans-serif; font-size: 16px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Organization Structure Report</p>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <p style="margin: 0; font-family: sans-serif; font-size: 14px; color: #94a3b8; font-weight: 700;">PRINT DATE</p>
                                        <p style="margin: 0; font-family: sans-serif; font-size: 18px; color: #475569; font-weight: 600;">${new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' })}</p>
                                    </div>
                                `;
                                clonedChart.prepend(header);
                                
                                // Add footer
                                const footer = clonedDoc.createElement('div');
                                footer.style.padding = '20px 60px';
                                footer.style.marginTop = '40px';
                                footer.style.borderTop = '1px solid #f1f5f9';
                                footer.style.fontFamily = 'sans-serif';
                                footer.style.fontSize = '12px';
                                footer.style.color = '#94a3b8';
                                footer.style.textAlign = 'center';
                                footer.innerHTML = 'Confidential Organization Hierarchy | Generated by IQC Sense Enterprise Platform';
                                clonedChart.appendChild(footer);
                            }
                        });

                        const imgData = canvas.toDataURL('image/png');
                        const pdf = new jsPDF({
                            orientation: canvas.width > canvas.height ? 'landscape' : 'portrait',
                            unit: 'px',
                            format: [canvas.width + 80, canvas.height + 80]
                        });

                        pdf.addImage(imgData, 'PNG', 40, 40, canvas.width, canvas.height);
                        pdf.save(`Org_Structure_${new Date().getTime()}.pdf`);
                        
                        Toast.fire({ icon: 'success', title: 'Export Generated Successfully' });
                    } catch (error) {
                        console.error('Export failed:', error);
                        Toast.fire({ icon: 'error', title: 'Export Failed' });
                    } finally {
                        exportPDF.innerHTML = originalBtn;
                        exportPDF.disabled = false;
                    }
                });
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* Google Chart Overrides */
            .google-visualization-orgchart-node {
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                padding: 12px 18px !important;
            }
            .premium-org-node {
                background: white !important;
                border: 1px solid #f1f5f9 !important;
                border-radius: 24px !important;
                box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.05) !important;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
                padding: 0 !important;
                min-width: 140px !important;
                max-width: 240px !important;
            }
            @media (min-width: 768px) {
                .premium-org-node { min-width: 200px !important; }
            }
            .org-node-inner {
                padding: 16px !important;
                border-radius: 24px;
                background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
                border: 1px solid white;
            }
            @media (min-width: 768px) {
                .org-node-inner { padding: 22px !important; }
            }
            .premium-org-node:hover {
                transform: translateY(-10px) scale(1.03);
                border-color: #818cf8 !important;
                box-shadow: 0 25px 40px -10px rgb(99 102 241 / 0.15), 0 10px 15px -8px rgb(99 102 241 / 0.15) !important;
            }
            .premium-org-node-selected {
                border: 2px solid #6366f1 !important;
                background: #fdfdff !important;
                box-shadow: 0 0 0 6px rgb(99 102 241 / 0.1) !important;
            }
            
            /* High-Performance Connectors */
            .google-visualization-orgchart-lineleft { border-left: 2px solid #cbd5e1 !important; }
            .google-visualization-orgchart-lineright { border-right: 2px solid #cbd5e1 !important; }
            .google-visualization-orgchart-linebottom { border-bottom: 2px solid #cbd5e1 !important; }
            .google-visualization-orgchart-connrow-medium { height: 35px !important; }

            /* Fullscreen & Scrollbar Styling */
            #chartContainer:fullscreen { background: #fbfcfe; padding: 2.5rem; overflow: auto; height: 100vh; }
            #chartContainer:fullscreen #chartWrapper { height: calc(100vh - 5rem); }
            
            #chartWrapper::-webkit-scrollbar { width: 5px; height: 5px; }
            #chartWrapper::-webkit-scrollbar-track { background: transparent; }
            #chartWrapper::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
            #chartWrapper::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
            
            .cursor-grab { cursor: grab; }
            .cursor-grabbing { cursor: grabbing; }

            /* Mobile Layout Refinements */
            @media (max-width: 767px) {
                #chartContainer {
                    height: calc(100vh - 340px);
                    margin: 0 -1rem;
                    border-radius: 0;
                    border-left: none;
                    border-right: none;
                }
            }
        </style>
    @endpush
@endsection
