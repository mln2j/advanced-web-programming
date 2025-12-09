<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected function authorizeRole(string $role): void
    {
        $user = auth()->user();
        if (!$user || ($user->role !== $role && $user->role !== 'admin')) {
            abort(403);
        }
    }

    // nastavnik – forma
    public function create()
    {
        $this->authorizeRole('nastavnik');
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $this->authorizeRole('nastavnik');

        $data = $request->validate([
            'title_hr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_hr' => 'required|string',
            'description_en' => 'required|string',
            'study_type' => 'required|in:strucni,preddiplomski,diplomski',
        ]);

        $data['user_id'] = auth()->id();

        Task::create($data);

        return redirect()->route('tasks.my')->with('success', 'Rad je dodan.');
    }

    // nastavnik – njegovi radovi
    public function myTasks()
    {
        // samo nastavnik, bez admina
        if (!auth()->check() || auth()->user()->role !== 'nastavnik') {
            abort(403);
        }

        $tasks = Task::where('user_id', auth()->id())->with('user')->get();

        return view('tasks.my', compact('tasks'));
    }


    // student – popis radova
    public function indexForStudent()
    {
        $this->authorizeRole('student');

        $tasks = Task::with('user')->get();

        return view('tasks.index_student', compact('tasks'));
    }
}
