<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskManagementController extends Controller
{
    protected function authorizeAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        $tasks = Task::with('user')->get();

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $teachers = User::where('role', 'nastavnik')->get();

        return view('admin.tasks.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title_hr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_hr' => 'required|string',
            'description_en' => 'required|string',
            'study_type' => 'required|in:strucni,preddiplomski,diplomski',
        ]);

        Task::create($data);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        $this->authorizeAdmin();

        $teachers = User::where('role', 'nastavnik')->get();

        return view('admin.tasks.edit', compact('task', 'teachers'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title_hr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_hr' => 'required|string',
            'description_en' => 'required|string',
            'study_type' => 'required|in:strucni,preddiplomski,diplomski',
        ]);

        $task->update($data);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeAdmin();

        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted.');
    }
}
