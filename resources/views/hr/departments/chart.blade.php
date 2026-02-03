@extends('layouts.app')

@section('title', 'Organization Chart')
@section('subtitle', 'Departmental Structure')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Structure Navigation -->
        @include('hr.partials.structure_nav')

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
             <!-- Header Content was here, removed redundant back link -->
        </div>

        <div class="premium-card p-10 overflow-x-auto min-h-[600px] flex justify-center bg-white">
            <div id="chart_div"></div>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {packages:["orgchart"]});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('string', 'Manager');
                data.addColumn('string', 'ToolTip');

                var validIds = @json($departments->pluck('department_id')->map(fn($id) => (string)$id)->toArray());
                
                var rows = [
                    @foreach($departments as $dept)
                        @php
                            $deptId = (string)$dept->department_id;
                            $parentId = (string)$dept->main_department_id;
                            
                            // Check if parent exists in our dataset, otherwise make it a root node to prevent crashes
                            // Also handle 0 or null as root
                            if ($parentId == '0' || $parentId == '' || !$departments->contains('department_id', $dept->main_department_id)) {
                                $parentId = '';
                            }

                            // Build HTML Content safe for JS
                            $name = e($dept->department_name);
                            $code = $dept->department_code ? '<div class="text-[10px] text-slate-500 uppercase tracking-wider mt-1">' . e($dept->department_code) . '</div>' : '';
                            
                            $managerHtml = '';
                            if($dept->lineManager) {
                                $initial = substr($dept->lineManager->first_name, 0, 1);
                                $mName = e($dept->lineManager->first_name);
                                $managerHtml = '<div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-center gap-2"><div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">'.$initial.'</div><div class="text-xs text-slate-600">'.$mName.'</div></div>';
                            }

                            $html = '<div class="p-2 min-w-[150px]"><div class="font-bold text-slate-800 text-lg">' . $name . '</div>' . $code . $managerHtml . '</div>';
                        @endphp
                        [{v: @json($deptId), f: @json($html)}, @json($parentId), @json($dept->department_name)],
                    @endforeach
                ];

                data.addRows(rows);

                // Create the chart.
                var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
                
                // Draw the chart, setting the allowHtml option to true for the tooltips.
                chart.draw(data, {
                    'allowHtml': true, 
                    'size': 'medium',
                    'nodeClass': 'premium-org-node',
                    'selectedNodeClass': 'premium-org-node-selected'
                });
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* Override Google Charts default styling for a premium look */
            .google-visualization-orgchart-node {
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
            }
            .premium-org-node {
                background: white !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 12px !important;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
                transition: all 0.3s ease !important;
                padding: 0 !important;
            }
            .premium-org-node:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
                border-color: #6366f1 !important;
            }
            .premium-org-node-selected {
                background: #f8fafc !important;
                border: 2px solid #6366f1 !important;
                box-shadow: 0 0 0 4px rgb(99 102 241 / 0.1) !important;
            }
            /* Connector lines */
            .google-visualization-orgchart-lineleft {
                border-left: 1px solid #cbd5e1 !important;
            }
            .google-visualization-orgchart-lineright {
                border-right: 1px solid #cbd5e1 !important;
            }
            .google-visualization-orgchart-linebottom {
                border-bottom: 1px solid #cbd5e1 !important;
            }
            .google-visualization-orgchart-connrow-medium {
                height: 20px !important;
            }
        </style>
    @endpush
@endsection
