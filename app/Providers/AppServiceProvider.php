<?php

namespace App\Providers;

use App\Http\Controllers\ProjectTasksController;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Observers\ProjectObserver;
use App\Observers\ProjectTaskObserver;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Guard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define(
            'is_project_owner',
            fn(User $user,Project $project)
                =>auth()->user()->is($project->owner)
        );
        Gate::define(
            'is_task_owner',
            fn(User $user,Task $task)
                 =>auth()->user()->is($task->project->owner)
        );

        Model::unguard(true);
        Project::observe(ProjectObserver::class);
        Task::observe(ProjectTaskObserver::class);
    }
}
