<?php

namespace App\Http\Controllers;

use App\Models\Project;


use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;
        return view('projects.index', compact('projects'));
    }

    public function create(){
        return view('projects.create');
    }

    public function store()
    {
        //validate
        $attributes = $this->validateRequest();
        //insert
        $project = auth()->user()->projects()->create($attributes);

        //redirect
        return redirect($project->path());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Project $project)
    {
        Gate::authorize('is_project_owner',$project);
        $project = $project->load(['tasks','activity']);
        return view('projects.show',compact('project'));
    }

    public function update(Project $project){
        Gate::authorize('is_project_owner',$project);
        $attributes = $this->validateRequest($project);

        $project->update($attributes);

        return redirect($project->path());
    }
    public function edit(Project $project){
        Gate::authorize('is_project_owner',$project);
        return view('projects.edit',compact('project'));
    }

    /**
     * @throws \Throwable
     */
    public function destroy(Project $project){
        Gate::authorize('is_project_owner',$project);
        $project->deleteOrFail();
        return redirect()->route('projects.index');
    }




    protected function validateRequest(Project $project = null){
        $project = $project ?? new Project();
        return request()->validate([
            'title' => [$project->exists?'sometimes':'','required'],
            'description' => [$project->exists?'sometimes':'','required'],
            'notes' => ['nullable']
        ]);
    }

}
