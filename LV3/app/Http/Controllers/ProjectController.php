<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $projects = $user->projektiVoditelj
            ->merge($user->projektiClan)
            ->unique('id');

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();

        return view('projects.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'naziv_projekta' => 'required|string|max:255',
            'opis_projekta' => 'nullable|string',
            'cijena_projekta' => 'nullable|numeric',
            'obavljeni_poslovi' => 'nullable|string',
            'datum_pocetka' => 'required|date',
            'datum_zavrsetka' => 'nullable|date'
        ]);
        $data['voditelj_id'] = auth()->id();

        $project = Project::create($data);

        if ($request->has('clanovi')) {
            $project->clanovi()->sync($request->input('clanovi'));
        }

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();
        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'naziv_projekta' => 'required|string|max:255',
            'opis_projekta' => 'nullable|string',
            'cijena_projekta' => 'nullable|numeric',
            'obavljeni_poslovi' => 'nullable|string',
            'datum_pocetka' => 'required|date',
            'datum_zavrsetka' => 'nullable|date'
        ]);

        if ($project->voditelj_id == auth()->id()) {
            $project->update($data);
            if ($request->has('clanovi')) {
                $project->clanovi()->sync($request->input('clanovi'));
            }
        }
        elseif($project->clanovi->contains(auth()->id())) {
            $project->update(['obavljeni_poslovi' => $request->input('obavljeni_poslovi')]);
        }

        return redirect()->route('projects.index')->with('success', __('Project updated.'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->voditelj_id == auth()->id()) {
            $project->delete();
            return redirect()->route('projects.index')->with('success', __('Project deleted.'));
        }
        abort(403, 'Nemate dozvolu za brisanje ovog projekta.');
    }

}
