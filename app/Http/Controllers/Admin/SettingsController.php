<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicketCategory;
use App\Models\SupportTicketStatus;
use App\Models\AssetCategory;
use App\Models\AssetStatus;
use App\Models\LeaveType;
use App\Models\Priority;
use App\Models\SupportServiceCategory;
use App\Models\CommunicationType;
use App\Models\Employee;

class SettingsController extends Controller
{
    private $config;

    public function __construct() {
        // Configuration for each list type
        $this->config = [
            'tc' => [
                'model' => SupportTicketCategory::class,
                'title' => 'Ticket Categories',
                'pk' => 'category_id',
                'name_field' => 'category_name',
                'fields' => ['category_name' => 'Name']
            ],
            'ts' => [
                'model' => SupportTicketStatus::class,
                'title' => 'Ticket Statuses',
                'pk' => 'status_id',
                'name_field' => 'status_name',
                'fields' => ['status_name' => 'Name']
            ],
            'ac' => [
                'model' => AssetCategory::class,
                'title' => 'Asset Categories',
                'pk' => 'category_id',
                'name_field' => 'category_name',
                'fields' => ['category_name' => 'Name', 'category_alert_days' => 'Alert Days|number']
            ],
            'lt' => [
                'model' => LeaveType::class,
                'title' => 'Leave Types',
                'pk' => 'leave_type_id',
                'name_field' => 'leave_type_name',
                'fields' => ['leave_type_name' => 'Name']
            ],
            'pp' => [
                'model' => Priority::class,
                'title' => 'Priorities',
                'pk' => 'priority_id', // Note: Legacy says theme_id in loop but priority_name. Assuming PK might be priority_id based on Model I saw, or theme_id. The Model I viewed said priority_id is PK.
                'name_field' => 'priority_name',
                'fields' => ['priority_name' => 'Name']
            ],
            'ss' => [
                'model' => SupportServiceCategory::class,
                'title' => 'Service Categories',
                'pk' => 'category_id',
                'name_field' => 'category_name',
                'fields' => [
                    'category_name' => 'Name',
                    'destination_id' => 'Receiver|employee'
                ]
            ],
            'ct' => [
                'model' => CommunicationType::class,
                'title' => 'Communication Types',
                'pk' => 'communication_type_id',
                'name_field' => 'communication_type_name',
                'fields' => [
                    'communication_type_name' => 'Name',
                    'approval_id_1' => 'First Approval|employee',
                    'approval_id_2' => 'Second Approval|employee'
                ]
            ],
        ];
    }

    public function index(Request $request)
    {
        $type = $request->input('type', 'tc');
        
        if (!array_key_exists($type, $this->config)) {
            abort(404);
        }

        $conf = $this->config[$type];
        $model = new $conf['model'];
        
        $records = $model->orderBy($conf['pk'], 'desc')->get();
        
        // Employees for dropdowns
        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();

        return view('admin.settings.index', compact('records', 'type', 'conf', 'employees'));
    }

    public function store(Request $request)
    {
        $type = $request->input('_type');
        if (!array_key_exists($type, $this->config)) {
            abort(404);
        }

        $conf = $this->config[$type];
        $modelClass = $conf['model'];
        $item = new $modelClass();

        foreach ($conf['fields'] as $field => $label) {
            $key = explode('|', $label)[0]; // Just in case I parse label later
            $item->$field = $request->input($field);
        }

        // Handle specific logic if needed, e.g. check checkboxes
        $item->save();

        return redirect()->route('admin.settings.index', ['type' => $type])->with('success', 'Record added successfully.');
    }

    public function update(Request $request, $id)
    {
         $type = $request->input('_type');
        if (!array_key_exists($type, $this->config)) {
            abort(404);
        }

        $conf = $this->config[$type];
        $modelClass = $conf['model'];
        $item = $modelClass::findOrFail($id);

         foreach ($conf['fields'] as $field => $label) {
            $item->$field = $request->input($field);
        }

        $item->save();

        return redirect()->route('admin.settings.index', ['type' => $type])->with('success', 'Record updated successfully.');
    }
}
