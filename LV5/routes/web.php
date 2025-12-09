<?php

use App\Http\Controllers\Admin\TaskManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');


    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my');


    Route::get('/tasks', [TaskController::class, 'indexForStudent'])->name('tasks.student');

    Route::get('/admin/tasks', [TaskManagementController::class, 'index'])->name('admin.tasks.index');
    Route::get('/admin/tasks/create', [TaskManagementController::class, 'create'])->name('admin.tasks.create');
    Route::post('/admin/tasks', [TaskManagementController::class, 'store'])->name('admin.tasks.store');
    Route::get('/admin/tasks/{task}/edit', [TaskManagementController::class, 'edit'])->name('admin.tasks.edit');
    Route::put('/admin/tasks/{task}', [TaskManagementController::class, 'update'])->name('admin.tasks.update');
    Route::delete('/admin/tasks/{task}', [TaskManagementController::class, 'destroy'])->name('admin.tasks.destroy');

    Route::post('/tasks/{task}/apply', [ApplicationController::class, 'store'])->name('applications.store');

    Route::get('/tasks/{task}/applications', [ApplicationController::class, 'indexForTask'])->name('applications.index');
    Route::post('/applications/{application}/accept', [ApplicationController::class, 'accept'])->name('applications.accept');

    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.my');
    Route::post('/my-applications/reorder', [ApplicationController::class, 'reorder'])->name('applications.reorder');
    Route::delete('/my-applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
});

require __DIR__ . '/auth.php';
