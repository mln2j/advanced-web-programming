<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Task;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected function authorizeStudent(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'student') {
            abort(403);
        }
    }

    public function store(Task $task)
    {
        $this->authorizeStudent();

        // ograniči na 5 prijava
        $count = Application::where('user_id', auth()->id())->count();
        if ($count >= 5) {
            return back()->with('success', 'Već imate 5 prijava.');
        }

        // ako već postoji prijava, ne radi ništa
        if (Application::where('user_id', auth()->id())->where('task_id', $task->id)->exists()) {
            return back()->with('success', 'Već ste prijavljeni na ovaj rad.');
        }

        // novi prioritet: ako nema prijava, 1; inače max + 1
        $lastPriority = Application::where('user_id', auth()->id())->max('priority');
        $nextPriority = $lastPriority ? $lastPriority + 1 : 1;

        Application::create([
            'user_id'   => auth()->id(),
            'task_id'   => $task->id,
            'status'    => 'pending',
            'priority'  => $nextPriority,
        ]);

        return back()->with('success', 'Prijava je zaprimljena.');
    }


    protected function authorizeTeacher(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['nastavnik','admin'])) {
            abort(403);
        }
    }

    public function indexForTask(Task $task)
    {
        $this->authorizeTeacher();

        // nastavnik smije vidjeti samo svoje radove (osim ako je admin)
        if (auth()->user()->role === 'nastavnik' && $task->user_id !== auth()->id()) {
            abort(403);
        }

        $applications = $task->applications()->with('student')->get();

        return view('applications.index', compact('task', 'applications'));
    }

    public function accept(Application $application)
    {
        $this->authorizeTeacher();

        $task = $application->task;

        if (auth()->user()->role === 'nastavnik' && $task->user_id !== auth()->id()) {
            abort(403);
        }

        // bonus: student mora imati prioritet 1 na ovom radu
        if ($application->priority !== 1) {
            return back()->with('success', 'Studenta možete prihvatiti samo na rad s prioritetom 1.');
        }

        // odbij sve ostale prijave tog studenta
        Application::where('user_id', $application->user_id)
            ->where('id', '!=', $application->id)
            ->update(['status' => 'rejected']);

        // prihvati ovu
        $application->update(['status' => 'accepted']);

        return back()->with('success', 'Student je prihvaćen na ovaj rad.');
    }

    protected function renumberForCurrentUser()
    {
        $apps = Application::where('user_id', auth()->id())
            ->orderBy('priority')
            ->get();

        foreach ($apps as $index => $app) {
            $app->update(['priority' => $index + 1]);
        }
    }



    public function myApplications()
    {
        $this->authorizeStudent();

        $applications = Application::with('task')
            ->where('user_id', auth()->id())
            ->orderBy('priority')
            ->get();

        return view('applications.my', compact('applications'));
    }

    public function reorder(Request $request)
    {
        $this->authorizeStudent();

        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:applications,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            Application::where('id', $id)
                ->where('user_id', auth()->id())
                ->update(['priority' => $index + 1]);
        }

        return response()->json(['status' => 'ok']);
    }


    public function destroy(Application $application)
    {
        $this->authorizeStudent();

        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        $application->delete();
        $this->renumberForCurrentUser();
        return back()->with('success', 'Prijava je obrisana.');
    }



}

