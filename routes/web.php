<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;

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

    // Common / Shared Routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Employee Portal
    Route::prefix('emp')->name('emp.')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [App\Http\Controllers\Employee\ProfileController::class, 'index'])->name('profile.index');
        
        // Tickets
        Route::get('/tickets', [App\Http\Controllers\Employee\SupportTicketController::class, 'index'])->name('tickets.index');
        Route::post('/tickets', [App\Http\Controllers\Employee\SupportTicketController::class, 'store'])->name('tickets.store');

        // My Leaves
        Route::get('/leaves', [App\Http\Controllers\Employee\LeaveController::class, 'index'])->name('leaves.index');
        Route::post('/leaves', [App\Http\Controllers\Employee\LeaveController::class, 'store'])->name('leaves.store');

        // My Tasks
        Route::get('/tasks', [App\Http\Controllers\Employee\TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [App\Http\Controllers\Employee\TaskController::class, 'store'])->name('tasks.store');
        Route::post('/tasks/{id}/status', [App\Http\Controllers\Employee\TaskController::class, 'updateStatus'])->name('tasks.status');

        // Messages
        Route::get('/messages', [App\Http\Controllers\Employee\MessageController::class, 'index'])->name('messages.index');
        Route::post('/messages', [App\Http\Controllers\Employee\MessageController::class, 'store'])->name('messages.store');
        Route::get('/messages/{chat_id}', [App\Http\Controllers\Employee\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{chat_id}/reply', [App\Http\Controllers\Employee\MessageController::class, 'reply'])->name('messages.reply');
    });

    // HR Portal
    Route::prefix('hr')->name('hr.')->group(function () {
         Route::get('/dashboard', [App\Http\Controllers\HR\DashboardController::class, 'index'])->name('dashboard');
         
         // Employees
         Route::get('/employees', [App\Http\Controllers\HR\EmployeeController::class, 'index'])->name('employees.index');
         Route::get('/employees/create', [App\Http\Controllers\HR\EmployeeController::class, 'create'])->name('employees.create');
         Route::post('/employees', [App\Http\Controllers\HR\EmployeeController::class, 'store'])->name('employees.store');
         Route::get('/employees/{id}', [App\Http\Controllers\HR\EmployeeController::class, 'show'])->name('employees.show');

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

         // Disciplinary (DA)
         Route::get('/disciplinary', [App\Http\Controllers\HR\DisciplinaryController::class, 'index'])->name('disciplinary.index');
         Route::post('/disciplinary', [App\Http\Controllers\HR\DisciplinaryController::class, 'store'])->name('disciplinary.store');
         Route::post('/disciplinary/{id}/update', [App\Http\Controllers\HR\DisciplinaryController::class, 'update'])->name('disciplinary.update');

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
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/leave_types', [App\Http\Controllers\Admin\SettingsController::class, 'leaveTypes'])->name('settings.leave_types');
        Route::post('/settings/leave_types', [App\Http\Controllers\Admin\SettingsController::class, 'storeLeaveType'])->name('settings.leave_types.store');
        Route::get('/settings/asset_categories', [App\Http\Controllers\Admin\SettingsController::class, 'assetCategories'])->name('settings.asset_categories');
        Route::get('/settings/asset_categories', [App\Http\Controllers\Admin\SettingsController::class, 'assetCategories'])->name('settings.asset_categories');
        Route::post('/settings/asset_categories', [App\Http\Controllers\Admin\SettingsController::class, 'storeAssetCategory'])->name('settings.asset_categories.store');

        // Users Management
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    });

    // Public / Partner Portal Routes
    Route::prefix('portal')->name('rc.portal.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\RC\PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/register/step1', [App\Http\Controllers\RC\PortalController::class, 'wizardStep1'])->name('wizard.step1');
        Route::post('/register/step1', [App\Http\Controllers\RC\PortalController::class, 'submitStep1'])->name('wizard.submit1');
    });
});
