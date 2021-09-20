<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
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
        Gate::authorize('is_task_owner',$task);
        $task->update(['body' => \request('body')]);
        if(\request()->has('completed'))
            $task->complete();
        return redirect($project->path());
    }

}
