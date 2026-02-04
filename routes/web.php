<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\FeedbackController;
use App\Http\Controllers\Employee\NotificationController;
use App\Http\Controllers\Employee\HrDocumentController;
use App\Http\Controllers\Employee\PerformanceController;
use App\Http\Controllers\Employee\ExitInterviewController;
use App\Http\Controllers\Rc\AtpController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Redirect /dashboard to specific portal
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if (session('user_type') == 'atp') {
            return redirect()->route('rc.portal.dashboard');
        }

        if (in_array($user->user_type, ['root', 'sys_admin'])) {
            return redirect()->route('admin.dashboard');
        }

        if (in_array($user->user_type, ['hr', 'admin_hr'])) {
            return redirect()->route('hr.dashboard');
        }

        // Default to Employee Dashboard
        return redirect()->route('emp.dashboard');
    })->name('dashboard');

    // Unified Notification Route (Shared)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Employee Portal
    Route::prefix('emp')->name('emp.')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Employee\ProfileController::class, 'index'])->name('profile.index');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

        // Documents
        Route::get('/documents', [HrDocumentController::class, 'index'])->name('documents.index');

        // Performance
        Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');

        // Exit Interview
        Route::get('/exit-interview', [ExitInterviewController::class, 'index'])->name('exit_interview.index');
        Route::post('/exit-interview', [ExitInterviewController::class, 'store'])->name('exit_interview.store');

        // External / RC Modules (Matching Legacy Structure: emp/ext/...)
        Route::prefix('ext')->name('ext.')->group(function () {
            // ATPs
            Route::get('/atps/list', [App\Http\Controllers\Rc\AtpController::class, 'index'])->name('atps.index');
            Route::get('/atps/new', [App\Http\Controllers\RC\TrainingProviderController::class, 'create'])->name('atps.create');
            Route::post('/atps/new', [App\Http\Controllers\RC\TrainingProviderController::class, 'store'])->name('atps.store');
            Route::get('/atps/view/{id}', [App\Http\Controllers\Rc\AtpController::class, 'show'])->name('atps.show');

            // Strategies (Strategic Plans)
            Route::get('/strategies/list', [App\Http\Controllers\Employee\Ext\StrategicPlanController::class, 'index'])->name('strategies.index');
            Route::get('/strategies/new', [App\Http\Controllers\Employee\Ext\StrategicPlanController::class, 'create'])->name('strategies.create');
            Route::post('/strategies/new', [App\Http\Controllers\Employee\Ext\StrategicPlanController::class, 'store'])->name('strategies.store');
            Route::get('/strategies/view/{id}', [App\Http\Controllers\Employee\Ext\StrategicPlanController::class, 'show'])->name('strategies.show');

            // Operational Projects
            Route::get('/strategies/projects/list', [App\Http\Controllers\Employee\Ext\OperationalProjectController::class, 'index'])->name('strategies.projects.index');
            Route::get('/strategies/projects/new', [App\Http\Controllers\Employee\Ext\OperationalProjectController::class, 'create'])->name('strategies.projects.create');
            Route::post('/strategies/projects/new', [App\Http\Controllers\Employee\Ext\OperationalProjectController::class, 'store'])->name('strategies.projects.store');
            Route::get('/strategies/projects/view/{id}', [App\Http\Controllers\Employee\Ext\OperationalProjectController::class, 'show'])->name('strategies.projects.show');

            // Self Studies
            Route::get('/strategies/self_studies/list', [App\Http\Controllers\Employee\Ext\StrategicStudyController::class, 'index'])->name('strategies.self_studies.index');
            Route::get('/strategies/self_studies/new', [App\Http\Controllers\Employee\Ext\StrategicStudyController::class, 'create'])->name('strategies.self_studies.create');
            Route::post('/strategies/self_studies/new', [App\Http\Controllers\Employee\Ext\StrategicStudyController::class, 'store'])->name('strategies.self_studies.store');
            Route::get('/strategies/self_studies/view/{id}', [App\Http\Controllers\Employee\Ext\StrategicStudyController::class, 'show'])->name('strategies.self_studies.show');
        });

        // Tickets
        Route::get('/tickets', [App\Http\Controllers\Employee\SupportTicketController::class, 'index'])->name('tickets.index');
        Route::post('/tickets', [App\Http\Controllers\Employee\SupportTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{id}', [App\Http\Controllers\Employee\SupportTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{id}/status', [App\Http\Controllers\Employee\SupportTicketController::class, 'updateStatus'])->name('tickets.update_status');

        // Request Center Hub
        Route::get('/requests', [App\Http\Controllers\Employee\RequestController::class, 'index'])->name('requests.index');

        // My Leaves
        Route::get('/leaves', [App\Http\Controllers\Employee\LeaveController::class, 'index'])->name('leaves.index');
        Route::post('/leaves', [App\Http\Controllers\Employee\LeaveController::class, 'store'])->name('leaves.store');

        // My Permissions
        Route::get('/permissions', [App\Http\Controllers\Employee\PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [App\Http\Controllers\Employee\PermissionController::class, 'store'])->name('permissions.store');

        // My Attendance
        Route::get('/attendance', [App\Http\Controllers\Employee\AttendanceController::class, 'index'])->name('attendance.index');

        // My Support Services (General Requests)
        Route::get('/ss', [App\Http\Controllers\Employee\SupportServiceController::class, 'index'])->name('ss.index');
        Route::post('/ss', [App\Http\Controllers\Employee\SupportServiceController::class, 'store'])->name('ss.store');
        Route::get('/ss/{id}', [App\Http\Controllers\Employee\SupportServiceController::class, 'show'])->name('ss.show');

        // My Disciplinary Actions
        Route::get('/da', [App\Http\Controllers\Employee\DisciplinaryActionController::class, 'index'])->name('da.index');
        Route::get('/da/{id}', [App\Http\Controllers\Employee\DisciplinaryActionController::class, 'show'])->name('da.show');

        // My Communications (External Requests)
        Route::get('/communications', [App\Http\Controllers\Employee\CommunicationRequestController::class, 'index'])->name('communications.index');
        Route::post('/communications', [App\Http\Controllers\Employee\CommunicationRequestController::class, 'store'])->name('communications.store');
        Route::get('/communications/{id}', [App\Http\Controllers\Employee\CommunicationRequestController::class, 'show'])->name('communications.show');

        // My Groups & Committees
        Route::get('/groups', [App\Http\Controllers\Employee\GroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/{id}', [App\Http\Controllers\Employee\GroupController::class, 'show'])->name('groups.show');
        Route::post('/groups/{id}/post', [App\Http\Controllers\Employee\GroupController::class, 'post'])->name('groups.post');
        Route::post('/groups/{id}/upload', [App\Http\Controllers\Employee\GroupController::class, 'upload'])->name('groups.upload');

        // My Calendar
        Route::get('/calendar', [App\Http\Controllers\Employee\CalendarController::class, 'index'])->name('calendar.index');

        // My Notifications
        Route::get('/notifications', [App\Http\Controllers\Employee\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', [App\Http\Controllers\Employee\NotificationController::class, 'markRead'])->name('notifications.mark_read');

        // My Tasks
        Route::get('/tasks', [App\Http\Controllers\Employee\TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{id}', [App\Http\Controllers\Employee\TaskController::class, 'show'])->name('tasks.show');
        Route::post('/tasks', [App\Http\Controllers\Employee\TaskController::class, 'store'])->name('tasks.store');
        Route::post('/tasks/{id}/status', [App\Http\Controllers\Employee\TaskController::class, 'updateStatus'])->name('tasks.status');

        // Messages
        Route::get('/messages', [App\Http\Controllers\Employee\MessageController::class, 'index'])->name('messages.index');
        Route::post('/messages', [App\Http\Controllers\Employee\MessageController::class, 'store'])->name('messages.store');
        Route::get('/messages/{chat_id}', [App\Http\Controllers\Employee\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{chat_id}/reply', [App\Http\Controllers\Employee\MessageController::class, 'reply'])->name('messages.reply');

        // Training Providers (ATPs)
        Route::post('/atps/{id}/send-email', [App\Http\Controllers\Employee\AtpController::class, 'sendEmail'])->name('atps.send-email');
        Route::post('/atps/{id}/accredit', [App\Http\Controllers\Employee\AtpController::class, 'accredit'])->name('atps.accredit');
        Route::resource('atps', App\Http\Controllers\Employee\AtpController::class)->names('atps');
    });

    // HR Portal
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HR\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests', [App\Http\Controllers\HR\RequestsController::class, 'index'])->name('requests.index');

        // Notifications
        Route::get('/notifications', [App\Http\Controllers\HR\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', [App\Http\Controllers\HR\NotificationController::class, 'markAsRead'])->name('notifications.mark_as_read');

        // Employees
        Route::get('/employees', [App\Http\Controllers\HR\EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [App\Http\Controllers\HR\EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [App\Http\Controllers\HR\EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{id}', [App\Http\Controllers\HR\EmployeeController::class, 'show'])->name('employees.show');
        Route::post('/employees/{id}/update', [App\Http\Controllers\HR\EmployeeController::class, 'update'])->name('employees.update');
        Route::post('/employees/{id}/update-credentials', [App\Http\Controllers\HR\EmployeeController::class, 'updateCredentials'])->name('employees.update-credentials');

        // Leaves
        Route::get('/leaves', [App\Http\Controllers\HR\LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create', [App\Http\Controllers\HR\LeaveController::class, 'create'])->name('leaves.create'); // Optional if using modal
        Route::post('/leaves', [App\Http\Controllers\HR\LeaveController::class, 'store'])->name('leaves.store');
        Route::post('/leaves/{id}/status', [App\Http\Controllers\HR\LeaveController::class, 'updateStatus'])->name('leaves.status');

        // Permissions (Short Leaves)
        Route::get('/permissions', [App\Http\Controllers\HR\PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [App\Http\Controllers\HR\PermissionController::class, 'store'])->name('permissions.store');

        // Attendance
        Route::get('/attendance', [App\Http\Controllers\HR\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [App\Http\Controllers\HR\AttendanceController::class, 'store'])->name('attendance.store');

        // Departments
        Route::get('/departments', [App\Http\Controllers\HR\DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/chart', [App\Http\Controllers\HR\DepartmentController::class, 'orgChart'])->name('departments.chart');
        Route::post('/departments', [App\Http\Controllers\HR\DepartmentController::class, 'store'])->name('departments.store');
        Route::post('/departments/{id}/update', [App\Http\Controllers\HR\DepartmentController::class, 'update'])->name('departments.update');

        // Assets
        Route::get('/assets', [App\Http\Controllers\HR\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [App\Http\Controllers\HR\AssetController::class, 'store'])->name('assets.store');
        Route::post('/assets/{id}/update', [App\Http\Controllers\HR\AssetController::class, 'update'])->name('assets.update');

        // Documents
        Route::get('/documents', [App\Http\Controllers\HR\DocumentController::class, 'index'])->name('documents.index');
        Route::post('/documents', [App\Http\Controllers\HR\DocumentController::class, 'store'])->name('documents.store');
        Route::delete('/documents/{id}', [App\Http\Controllers\HR\DocumentController::class, 'destroy'])->name('documents.destroy');

        // Groups & Committees
        Route::get('/groups', [App\Http\Controllers\HR\GroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/{id}', [App\Http\Controllers\HR\GroupController::class, 'show'])->name('groups.show');
        Route::post('/groups', [App\Http\Controllers\HR\GroupController::class, 'store'])->name('groups.store');
        Route::post('/groups/member', [App\Http\Controllers\HR\GroupController::class, 'addMember'])->name('groups.member.store');
        Route::post('/groups/post', [App\Http\Controllers\HR\GroupController::class, 'addPost'])->name('groups.post.store');
        Route::post('/groups/file', [App\Http\Controllers\HR\GroupController::class, 'uploadFile'])->name('groups.file.store');

        // Tasks
        Route::get('/tasks', [App\Http\Controllers\HR\TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{id}', [App\Http\Controllers\HR\TaskController::class, 'show'])->name('tasks.show');
        Route::post('/tasks', [App\Http\Controllers\HR\TaskController::class, 'store'])->name('tasks.store');
        Route::post('/tasks/status', [App\Http\Controllers\HR\TaskController::class, 'updateStatus'])->name('tasks.status.update');

        // Calendar
        Route::get('/calendar', [App\Http\Controllers\HR\CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [App\Http\Controllers\HR\CalendarController::class, 'getEvents'])->name('calendar.events');

        // Disciplinary (DA)
        Route::get('/disciplinary', [App\Http\Controllers\HR\DisciplinaryController::class, 'index'])->name('disciplinary.index');
        Route::post('/disciplinary', [App\Http\Controllers\HR\DisciplinaryController::class, 'store'])->name('disciplinary.store');
        Route::post('/disciplinary/{id}/update', [App\Http\Controllers\HR\DisciplinaryController::class, 'update'])->name('disciplinary.update');

        // Messages
        Route::get('/messages', [App\Http\Controllers\HR\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [App\Http\Controllers\HR\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages', [App\Http\Controllers\HR\MessageController::class, 'store'])->name('messages.store');
        Route::post('/messages/{id}/reply', [App\Http\Controllers\HR\MessageController::class, 'reply'])->name('messages.reply');

        // Tickets
        Route::get('/tickets', [App\Http\Controllers\HR\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{id}', [App\Http\Controllers\HR\TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets', [App\Http\Controllers\HR\TicketController::class, 'store'])->name('tickets.store');
        Route::post('/tickets/{id}/status', [App\Http\Controllers\HR\TicketController::class, 'updateStatus'])->name('tickets.status.update');

        // Exit Interviews
        Route::get('/exit-interviews', [App\Http\Controllers\HR\ExitInterviewController::class, 'index'])->name('exit_interviews.index');
        Route::post('/exit-interviews', [App\Http\Controllers\HR\ExitInterviewController::class, 'store'])->name('exit_interviews.store');

        // Performance
        Route::get('/performance', [App\Http\Controllers\HR\PerformanceController::class, 'index'])->name('performance.index');
        Route::post('/performance', [App\Http\Controllers\HR\PerformanceController::class, 'store'])->name('performance.store');
        Route::post('/performance/{id}/update', [App\Http\Controllers\HR\PerformanceController::class, 'update'])->name('performance.update');

        // Communications
        Route::get('/communications', [App\Http\Controllers\HR\CommunicationController::class, 'index'])->name('communications.index');
        Route::post('/communications', [App\Http\Controllers\HR\CommunicationController::class, 'store'])->name('communications.store');

        // Groups
        Route::get('/groups', [App\Http\Controllers\HR\GroupController::class, 'index'])->name('groups.index');
        Route::post('/groups', [App\Http\Controllers\HR\GroupController::class, 'store'])->name('groups.store');
        Route::get('/groups/{id}', [App\Http\Controllers\HR\GroupController::class, 'show'])->name('groups.show');
        Route::post('/groups/{id}/post', [App\Http\Controllers\HR\GroupController::class, 'storePost'])->name('groups.post');
        Route::post('/groups/{id}/member', [App\Http\Controllers\HR\GroupController::class, 'addMember'])->name('groups.add_member');

        // Designations
        Route::get('/designations', [App\Http\Controllers\HR\DesignationController::class, 'index'])->name('designations.index');
        Route::get('/designations/create', [App\Http\Controllers\HR\DesignationController::class, 'create'])->name('designations.create');
        Route::post('/designations', [App\Http\Controllers\HR\DesignationController::class, 'store'])->name('designations.store');
        Route::get('/designations/{id}/edit', [App\Http\Controllers\HR\DesignationController::class, 'edit'])->name('designations.edit');
        Route::post('/designations/{id}', [App\Http\Controllers\HR\DesignationController::class, 'update'])->name('designations.update'); // Using POST for update per legacy style or standard

        // Tasks
        Route::get('/tasks', [App\Http\Controllers\HR\TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/create', [App\Http\Controllers\HR\TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [App\Http\Controllers\HR\TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{id}', [App\Http\Controllers\HR\TaskController::class, 'show'])->name('tasks.show');

        // Tickets
        Route::get('/tickets', [App\Http\Controllers\HR\SupportTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [App\Http\Controllers\HR\SupportTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [App\Http\Controllers\HR\SupportTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{id}', [App\Http\Controllers\HR\SupportTicketController::class, 'show'])->name('tickets.show');

        // Calendar
        Route::get('/calendar', [App\Http\Controllers\HR\CalendarController::class, 'index'])->name('calendar.index');

        // Settings / Profile
        Route::get('/profile/settings', [App\Http\Controllers\HR\SettingsController::class, 'profile'])->name('settings.profile');
        Route::post('/profile/settings', [App\Http\Controllers\HR\SettingsController::class, 'updateProfile'])->name('settings.update');
    });

    // Chat / Messages (Global or HR based)
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [App\Http\Controllers\Employee\MessageController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Employee\MessageController::class, 'show'])->name('show');
        Route::post('/', [App\Http\Controllers\Employee\MessageController::class, 'store'])->name('store');
        Route::post('/{id}/reply', [App\Http\Controllers\Employee\MessageController::class, 'reply'])->name('reply');
    });

    // RC / Training Portal Routes
    Route::prefix('rc')->name('rc.')->group(function () {
        Route::get('/atps', [App\Http\Controllers\RC\TrainingProviderController::class, 'index'])->name('atps.index');
        Route::get('/atps/create', [App\Http\Controllers\RC\TrainingProviderController::class, 'create'])->name('atps.create');
        Route::post('/atps', [App\Http\Controllers\RC\TrainingProviderController::class, 'store'])->name('atps.store');
        Route::get('/atps/{id}', [App\Http\Controllers\RC\TrainingProviderController::class, 'show'])->name('atps.show');
    });

    // Admin Settings
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications');

        // Messages
        Route::get('/messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
        Route::post('/messages/create', [App\Http\Controllers\Admin\MessageController::class, 'create'])->name('messages.create');
        Route::post('/messages', [App\Http\Controllers\Admin\MessageController::class, 'store'])->name('messages.store');

        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/leave_types', [App\Http\Controllers\Admin\SettingsController::class, 'leaveTypes'])->name('settings.leave_types');
        Route::post('/settings/leave_types', [App\Http\Controllers\Admin\SettingsController::class, 'storeLeaveType'])->name('settings.leave_types.store');
        Route::get('/settings/asset_categories', [App\Http\Controllers\Admin\SettingsController::class, 'assetCategories'])->name('settings.asset_categories');
        Route::post('/settings/asset_categories', [App\Http\Controllers\Admin\SettingsController::class, 'storeAssetCategory'])->name('settings.asset_categories.store');

        // System Lists
        Route::get('/settings/asset_statuses', [App\Http\Controllers\Admin\SettingsController::class, 'assetStatuses'])->name('settings.asset_statuses');
        Route::post('/settings/asset_statuses', [App\Http\Controllers\Admin\SettingsController::class, 'storeAssetStatus'])->name('settings.asset_statuses.store');
        Route::get('/settings/support_categories', [App\Http\Controllers\Admin\SettingsController::class, 'supportCategories'])->name('settings.support_categories');
        Route::post('/settings/support_categories', [App\Http\Controllers\Admin\SettingsController::class, 'storeSupportCategory'])->name('settings.support_categories.store');
        Route::get('/settings/priorities', [App\Http\Controllers\Admin\SettingsController::class, 'priorities'])->name('settings.priorities');
        Route::post('/settings/priorities', [App\Http\Controllers\Admin\SettingsController::class, 'storePriority'])->name('settings.priorities.store');

        // Users Management
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');

        // Assets Management (Root/Admin Logic)
        Route::get('/assets', [App\Http\Controllers\Admin\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [App\Http\Controllers\Admin\AssetController::class, 'store'])->name('assets.store');

        // Organization Chart (Moved from HR to Admin as shared/root logic)
        Route::get('/departments', [App\Http\Controllers\HR\DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/chart', [App\Http\Controllers\HR\DepartmentController::class, 'orgChart'])->name('departments.chart');
        Route::post('/departments', [App\Http\Controllers\HR\DepartmentController::class, 'store'])->name('departments.store');
        Route::post('/departments/{id}/update', [App\Http\Controllers\HR\DepartmentController::class, 'update'])->name('departments.update');

        Route::get('/designations', [App\Http\Controllers\HR\DesignationController::class, 'index'])->name('designations.index');
        Route::get('/designations/create', [App\Http\Controllers\HR\DesignationController::class, 'create'])->name('designations.create');
        Route::post('/designations', [App\Http\Controllers\HR\DesignationController::class, 'store'])->name('designations.store');
        Route::get('/designations/{id}/edit', [App\Http\Controllers\HR\DesignationController::class, 'edit'])->name('designations.edit');
        Route::post('/designations/{id}', [App\Http\Controllers\HR\DesignationController::class, 'update'])->name('designations.update');

        // Feedback
        Route::get('/feedback', [App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/feedback/export', [App\Http\Controllers\Admin\FeedbackController::class, 'export'])->name('feedback.export');

        // Support Tickets
        Route::get('/tickets', [App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('tickets.index');
        Route::post('/tickets', [App\Http\Controllers\Admin\SupportTicketController::class, 'store'])->name('tickets.store');
        Route::post('/tickets/{id}/assign', [App\Http\Controllers\Admin\SupportTicketController::class, 'assign'])->name('tickets.assign');
        Route::post('/tickets/{id}/status', [App\Http\Controllers\Admin\SupportTicketController::class, 'updateStatus'])->name('tickets.update_status');
        Route::get('/tickets/{id}', [App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('tickets.show');

        // Assets
        Route::get('/assets', [App\Http\Controllers\Admin\AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [App\Http\Controllers\Admin\AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{id}', [App\Http\Controllers\Admin\AssetController::class, 'show'])->name('assets.show');
        Route::post('/assets/{id}/assign', [App\Http\Controllers\Admin\AssetController::class, 'assign'])->name('assets.assign');

        // Users
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');

        // Settings (Dynamics Lists)
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'store'])->name('settings.store');
        Route::post('/settings/{id}', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    });

    // EQA / Training Providers Routes
    Route::prefix('eqa')->name('eqa.')->group(function () {
        Route::get('/atps', [App\Http\Controllers\EQA\ATPController::class, 'index'])->name('atps.index');
        Route::get('/atps/create', [App\Http\Controllers\EQA\ATPController::class, 'create'])->name('atps.create');
        Route::post('/atps', [App\Http\Controllers\EQA\ATPController::class, 'store'])->name('atps.store');
        Route::get('/atps/{id}', [App\Http\Controllers\EQA\ATPController::class, 'show'])->name('atps.show');
        Route::post('/atps/{id}/send-email', [App\Http\Controllers\EQA\ATPController::class, 'sendRegistrationEmail'])->name('atps.send_email');

        // Forms Routes
        Route::get('/forms/{form_id}/{atp_id}', [App\Http\Controllers\EQA\FormController::class, 'show'])->name('forms.show');
        Route::post('/forms/{form_id}/{atp_id}', [App\Http\Controllers\EQA\FormController::class, 'store'])->name('forms.store');

        Route::get('/questions', [App\Http\Controllers\EQA\QuestionController::class, 'index'])->name('questions.index');
        Route::post('/questions', [App\Http\Controllers\EQA\QuestionController::class, 'store'])->name('questions.store');
    });

    // Public / Partner Portal Routes
    Route::prefix('portal')->name('rc.portal.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\RC\PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/register/step1', [App\Http\Controllers\RC\PortalController::class, 'wizardStep1'])->name('wizard.step1');
        Route::post('/register/step1', [App\Http\Controllers\RC\PortalController::class, 'submitStep1'])->name('wizard.submit1');
    });
});
