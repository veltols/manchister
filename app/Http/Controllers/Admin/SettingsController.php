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
use App\Models\UsersListTheme;

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
            'ult' => [
                'model' => UsersListTheme::class,
                'title' => 'Users List Themes',
                'pk' => 'user_theme_id',
                'name_field' => 'theme_name',
                'fields' => [
                    'theme_name' => 'Theme Name',
                    'color_primary' => 'Primary Color|color',
                    'color_on_primary' => 'On Primary|color',
                    'color_secondary' => 'Secondary Color|color',
                    'color_on_secondary' => 'On Secondary|color',
                    'color_third' => 'Third Color|color',
                    'color_on_third' => 'On Third|color',
                ]
            ],
        ];
    }

    public function index(Request $request)
    {
        $type = $request->input('type', 'tc');

        // Employees for dropdowns (Used in Modals)
        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();

        // Branding Settings
        $logo = \App\Models\AppSetting::where('key', 'logo_path')->value('value');
        $favicon = \App\Models\AppSetting::where('key', 'favicon_path')->value('value');
        $loginBackground = \App\Models\AppSetting::where('key', 'login_background_path')->value('value');

        if ($type === 'branding') {
             return view('admin.settings.index', compact('type', 'employees', 'logo', 'favicon', 'loginBackground'));
        }

        if (!array_key_exists($type, $this->config)) {
            abort(404);
        }

        $conf = $this->config[$type];
        $model = new $conf['model'];
        
        $records = $model->orderBy($conf['pk'], 'desc')->paginate(15);
        
        return view('admin.settings.index', compact('records', 'type', 'conf', 'employees', 'logo', 'favicon', 'loginBackground'));
    }

    public function getData(Request $request)
    {
        $type = $request->input('type', 'tc');
        $perPage = $request->get('per_page', 15);

        if (!array_key_exists($type, $this->config)) {
            return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        $conf = $this->config[$type];
        $model = new $conf['model'];
        
        $records = $model->orderBy($conf['pk'], 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $records->items(),
            'pagination' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
                'from' => $records->firstItem(),
                'to' => $records->lastItem(),
            ]
        ]);
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

    public function updateBranding(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:1024',
            'login_background' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            \App\Models\AppSetting::updateOrCreate(
                ['key' => 'logo_path'],
                ['value' => $fileName]
            );
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            \App\Models\AppSetting::updateOrCreate(
                ['key' => 'favicon_path'],
                ['value' => $fileName]
            );
        }

        return redirect()->back()->with('success', 'Branding updated successfully.');
    }
}
