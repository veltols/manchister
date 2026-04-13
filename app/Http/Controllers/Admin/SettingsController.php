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
use App\Models\IncidentType;
use App\Models\SystemLog;
use App\Models\AppSetting;

class SettingsController extends Controller
{
    private $config;

    public function __construct() {
        // Configuration for each list type
        $this->config = [
            'tc' => [
                'model' => SupportTicketCategory::class,
                'title' => 'Ticket Category',
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
                'title' => 'Asset Category',
                'pk' => 'category_id',
                'name_field' => 'category_name',
                'fields' => ['category_name' => 'Name']
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
                'title' => 'Priority',
                'pk' => 'priority_id', // Note: Legacy says theme_id in loop but priority_name. Assuming PK might be priority_id based on Model I saw, or theme_id. The Model I viewed said priority_id is PK.
                'name_field' => 'priority_name',
                'fields' => ['priority_name' => 'Name']
            ],
            'ss' => [
                'model' => SupportServiceCategory::class,
                'title' => 'Support Services',
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
            'it' => [
                'model' => IncidentType::class,
                'title' => 'Incident Types',
                'pk' => 'incident_type_id',
                'name_field' => 'type_name',
                'fields' => ['type_name' => 'Name']
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
        
        $search = $request->input('search', '');
        $query = $model->newQuery();

        if (!empty($search)) {
            $query->where(function($q) use ($conf, $search) {
                foreach ($conf['fields'] as $field => $label) {
                    $parts = explode('|', $label);
                    $mType = $parts[1] ?? 'text';

                    if ($mType === 'employee') {
                        $relName = null;
                        if ($field === 'destination_id') $relName = 'receiver';
                        elseif ($field === 'approval_id_1') $relName = 'approval1';
                        elseif ($field === 'approval_id_2') $relName = 'approval2';

                        if ($relName) {
                            $q->orWhereHas($relName, function($sq) use ($search) {
                                $sq->where('first_name', 'LIKE', "%{$search}%")
                                   ->orWhere('last_name', 'LIKE', "%{$search}%");
                            });
                        }
                        // Also search by ID
                        $q->orWhere($field, 'LIKE', "%{$search}%");
                    } else {
                        $q->orWhere($field, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        $records = $query->orderBy($conf['pk'], 'desc')->paginate(15);
        $records->appends(['type' => $type, 'search' => $search]);
        
        return view('admin.settings.index', compact('records', 'type', 'conf', 'employees', 'logo', 'favicon', 'loginBackground', 'search'));
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
        $search = $request->input('search', '');
        
        $query = $model->newQuery();

        if (!empty($search)) {
            $query->where(function($q) use ($conf, $search) {
                foreach ($conf['fields'] as $field => $label) {
                    $parts = explode('|', $label);
                    $mType = $parts[1] ?? 'text';

                    if ($mType === 'employee') {
                        $relName = null;
                        if ($field === 'destination_id') $relName = 'receiver';
                        elseif ($field === 'approval_id_1') $relName = 'approval1';
                        elseif ($field === 'approval_id_2') $relName = 'approval2';

                        if ($relName) {
                            $q->orWhereHas($relName, function($sq) use ($search) {
                                $sq->where('first_name', 'LIKE', "%{$search}%")
                                   ->orWhere('last_name', 'LIKE', "%{$search}%");
                            });
                        }
                        // Also search by ID
                        $q->orWhere($field, 'LIKE', "%{$search}%");
                    } else {
                        $q->orWhere($field, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        $records = $query->orderBy($conf['pk'], 'desc')->paginate($perPage);

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
        $modelInstance = new $modelClass();
        $table = $modelInstance->getTable();
        $pk = $conf['pk'];
        $nameField = $conf['name_field'];

        try {
            $request->validate([
                $nameField => "required|string|max:255|unique:{$table},{$nameField}",
            ], [
                "{$nameField}.unique" => 'This entry already exists. Please use a different name.',
                "{$nameField}.required" => 'Name is required.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('admin.settings.index', ['type' => $type])
                ->with('error', $e->validator->errors()->first($nameField) ?: 'This entry already exists.')
                ->withInput();
        }

        $item = new $modelClass();

        foreach ($conf['fields'] as $field => $label) {
            $item->$field = $request->input($field);
        }

        // Handle specific logic if needed, e.g. check checkboxes
        $item->save();

        $this->logAction($item->$pk, 'Setting Created', "New {$conf['title']} entry: " . $item->$nameField, $table);

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
        $modelInstance = new $modelClass();
        $table = $modelInstance->getTable();
        $pk = $conf['pk'];
        $nameField = $conf['name_field'];

        $request->validate([
            $nameField => "required|string|max:255|unique:{$table},{$nameField},{$id},{$pk}",
        ], [
            "{$nameField}.unique" => 'This entry already exists.',
        ]);

        $item = $modelClass::findOrFail($id);

         foreach ($conf['fields'] as $field => $label) {
            $item->$field = $request->input($field);
        }

        $item->save();

        $this->logAction($id, 'Setting Updated', "Updated {$conf['title']} entry: " . $item->$nameField, $table);

        return redirect()->route('admin.settings.index', ['type' => $type])->with('success', 'Record updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->input('_type');
        if (!array_key_exists($type, $this->config)) {
            abort(404);
        }

        $conf = $this->config[$type];
        $modelClass = $conf['model'];
        $item = $modelClass::findOrFail($id);
        
        $item->delete();

        $this->logAction($id, 'Setting Deleted', "Deleted {$conf['title']} entry: " . $item->{$conf['name_field']}, $item->getTable());

        return redirect()->route('admin.settings.index', ['type' => $type])->with('success', 'Record deleted successfully.');
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
            AppSetting::updateOrCreate(
                ['key' => 'logo_path'],
                ['value' => $fileName]
            );
            $this->logAction(0, 'Branding Updated', 'Website logo was updated.', 'app_settings');
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            AppSetting::updateOrCreate(
                ['key' => 'favicon_path'],
                ['value' => $fileName]
            );
            $this->logAction(0, 'Branding Updated', 'Website favicon was updated.', 'app_settings');
        }

        if ($request->hasFile('login_background')) {
            $file = $request->file('login_background');
            $fileName = 'bg_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            AppSetting::updateOrCreate(
                ['key' => 'login_background_path'],
                ['value' => $fileName]
            );
            $this->logAction(0, 'Branding Updated', 'Login background image was updated.', 'app_settings');
        }

        return redirect()->back()->with('success', 'Branding updated successfully.');
    }
    
    private function logAction($refId, $action, $remark, $table = 'settings')
    {
        $log = new SystemLog();
        $log->related_id = $refId;
        $log->related_table = $table;
        $log->log_date = now();
        $log->log_action = $action;
        $log->log_remark = $remark;
        $log->logger_type = 'admin';
        $log->logged_by = auth()->user() ? auth()->user()->user_id : 1;
        $log->save();
    }
}
