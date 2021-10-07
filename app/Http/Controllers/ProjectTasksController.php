<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class ProjectTasksController extends Controller
{

    public function store(Project $project){

        Gate::authorize('is_project_owner',$project);

        $attributes = \request()->validate([
            'body' => 'required'
        ]);
        $project->addTask($attributes['body']);

        return redirect($project->path());
    }


    public function update(Project $project,Task $task){
        Gate::authorize('is_project_owner',$task->project);

        $task->update(\request()->validate(['body' => 'required']));
        //TODO calling a method on string
        $method = \request('completed')?'complete':'incomplete';
        $task->$method();

        return redirect($project->path());
    }

}
